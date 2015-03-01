<?php 
	session_start();
	require_once ('includes/config.inc.php'); 
	
	if (isset($_SESSION['name'])) {
		require_once (MYSQL);	
	}else{
		header("Location: login.php");
	}
?>
