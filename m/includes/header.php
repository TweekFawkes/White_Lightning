<?php

ob_start(); // Start output buffering
session_start(); // Initialize a session

// Check for a $page_title value:
if (!isset($page_title)) {
	$page_title = 'WHITELIGHTNING';
} else {
	$page_title = "WL -> " . $page_title;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $page_title; ?></title>
<style type="text/css" media="screen">@import "includes/layout.css";</style>
</head>
<body>
<div id="Header"><a href="<?php echo BASE_URL ?>">WhiteLightning</a><br />
	
	<div id="TopMenu">
	<?php 
	if (isset($_SESSION['user_id']))
	{
		echo '<a href="logout.php" title="Logout">Logout</a><br />';
	}
	else
	{

		
		echo '<a href="register.php" title="Register for the Site">Register</a>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<a href="login.php" title="Login">Login</a>';

		
		echo '<br />';

	}
	?>

	</div>
</div>

<div id="Content">
<!-- End of Header -->
