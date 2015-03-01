<?php 
	require_once ('config.inc.php'); 
	require_once (MYSQL);
	
	$q = 'select ua_browser_name, count(ua_browser_name) as c from hits group by ua_browser_name';
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$a = '[';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$a.= "{browser:'".$row['ua_browser_name']."',hits:".$row['c']."},";
	}
	chop($a,",");
	$a.="]";
	echo $a;
?>
