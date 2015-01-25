<?php
	session_start();
	require_once ('includes/config.inc.php'); 
	
	if (isset($_SESSION['name'])) {
		$_SESSION = array(); // Destroy the variables.
		session_destroy(); // Destroy the session itself.
		setcookie (session_name(), '', time()-300); // Destroy the cookie.
	}
	header("Location: login.php");
?>
