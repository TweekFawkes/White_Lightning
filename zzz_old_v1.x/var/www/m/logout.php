<?php
	include('includes/validate.php');
	
	if (isset($_SESSION['name'])) {
		$_SESSION = array(); // Destroy the variables.
		session_destroy(); // Destroy the session itself.
		setcookie (session_name(), '', time()-300); // Destroy the cookie.
	}
	header("Location: login.php");
?>
