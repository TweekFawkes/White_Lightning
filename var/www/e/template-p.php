<?php
    require_once ('m/includes/config.inc.php');
    
    $php_date = date("m.d.y");
    $php_time = date("H:i:s");
    
    if( isset($_SERVER['REMOTE_ADDR']) )
    {
        $php_remote_addr = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
        $php_remote_addr = "unknown";
    }
        
    if( isset($_SERVER['HTTP_REFERER']) )
    {
        $php_http_referer = $_SERVER['HTTP_REFERER'];
    }
    else
    {
        $php_http_referer = "unknown";
    }
    
    if( isset($_SERVER['HTTP_USER_AGENT']) )
    {
        $php_http_user_agent = $_SERVER['HTTP_USER_AGENT'];
    }
    else
    {
        $php_http_user_agent = "unknown";
    }
    
    if ( defined('THROW_ID') )
    {
        $throw_id = THROW_ID;
    }
    else
    {
        $throw_id = "31337";
    }
    
    require_once (MYSQL);
    
    $q = "INSERT INTO loads (" .
    "throw_id, php_date, php_time, php_remote_addr, php_http_referer, php_http_user_agent" .
    ") VALUES (" .
    "'$throw_id', '$php_date', '$php_time', '$php_remote_addr', '$php_http_referer', '$php_http_user_agent')";

    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
    if (mysqli_affected_rows($dbc) == 1)
    {
      // INSERT works
      $load_id = mysqli_insert_id($dbc);
    }
    else
    {
      // INSERT failed
      $load_id = NULL;
    }
    mysqli_close($dbc);    
?>