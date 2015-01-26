<?php 
	require_once ('config.inc.php'); 
	require_once (MYSQL);
	
	$q = "SELECT CONCAT_WS(' ', ua_os_family, ua_os_version) AS os, COUNT(CONCAT_WS(' ', ua_os_family, ua_os_version)) AS c FROM hits GROUP BY os";
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$a = '[';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$a.= "{label:'".$row['os']."',value:".$row['c']."},";
	}
	chop($a,",");
	$a.="]";
	echo $a;
?>
