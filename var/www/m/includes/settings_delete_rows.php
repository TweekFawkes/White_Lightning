<?php 
	if (isset($_POST['action'])){
		$trimmed = array_map('trim', $_POST);
		require_once ('config.inc.php'); 
		require_once (MYSQL);
		$q = 'DELETE FROM users WHERE name = "' . $trimmed['action'] .'"';
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	}
?>
