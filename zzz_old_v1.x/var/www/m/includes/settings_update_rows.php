<?php 
	if (isset($_POST['action'])){
		$trimmed = array_map('trim', $_POST);
		require_once ('config.inc.php'); 
		require_once (MYSQL);
		$q = 'SELECT user_level FROM users WHERE name = "'.$trimmed['action'].'"';
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		if ($row['user_level'] == 1){
			$change_level = 0;
		}else{
			$change_level = 1;
		}
		$q = 'UPDATE users SET user_level='.$change_level.' WHERE name="'.$trimmed['action'].'"';
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	}
?>
