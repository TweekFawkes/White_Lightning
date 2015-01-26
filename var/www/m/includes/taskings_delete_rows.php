<?php 
	if (isset($_POST['action'])){
		$trimmed = array_map('trim', $_POST);
		require_once ('config.inc.php'); 
		require_once (MYSQL);
		$q = 'DELETE FROM taskings WHERE random_string = "' . $trimmed['action'] .'"';
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		unlink("../../". $trimmed['action']);
		array_map('unlink', glob("../../". $trimmed['action']."*"));
	}
?>
