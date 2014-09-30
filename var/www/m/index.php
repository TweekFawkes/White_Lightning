<?php 

// Include the configuration file:
require_once ('includes/config.inc.php'); 

// Set the page title and include the HTML header:
$page_title = 'WL';
include ('includes/header.html');

echo '<center>';

if (isset($_SESSION['name']))
{

	

	require_once (MYSQL);
	
	// Count the number of hits:
	$hits_count = "?";
	$q = "SELECT COUNT(hit_id) FROM hits";
	try {
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$hits_count = $row[0];
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		$hits_count = "?";
	}

	// Count the number of hits:
	$throws_count = "?";
	$q = "SELECT COUNT(throw_id) FROM throws";
	try {
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$throws_count = $row[0];
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		$throws_count = "?";
	}
	
	// Count the number of hits:
	$loads_count = "?";
	$q = "SELECT COUNT(load_id) FROM loads";
	try {
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$loads_count = $row[0];
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		$loads_count = "?";
	}
	
	
	echo '
	</center>
	
	<div id="nifty50">
	<center>
	
	<a href="view_hits.php" title="Hits"><h1>Hits: ' . $hits_count . '</h1></a>
	<a href="view_throws.php" title="Hits"><h1>Throws: ' . $throws_count . '</h1></a>
	<a href="view_loads.php" title="Hits"><h1>Loads: ' . $loads_count . '</h1></a>
	</center>
	
	</div>
	
	<div id="nifty50r">
	<center>';
	
	// Make the query:
	$q = 'select COUNT(hit_id) AS count, CONCAT(ua_os_family, " ", ua_os_version) AS os from hits group by os order by count(*) DESC';
	try {
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// Table header:
		echo '
		<table align="center" cellspacing="0" cellpadding="5" width="75%">
		<tr>
			<td align="left"><b>OS</b></td>
			<td align="left"><b>Count</b></td>
		</tr>';
		
		// Fetch and print all the records....
		$bg = '#eeeeee'; 
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
			echo '
			<tr bgcolor="' . $bg . '">
				<td align="left">' . $row['os'] . '</td>
				<td align="left">' . $row['count'] . '</td>
			</tr>';
		} // End of WHILE loop.
		echo '</table>';
		mysqli_free_result ($r);
		//mysqli_close($dbc);		
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		//$hits_count = "?";
		mysqli_free_result ($r);
	}
	
	echo'<h1> - </h1>';

	// Make the query:
	$q = 'select COUNT(hit_id) AS count, ua_browser_name from hits group by ua_browser_name order by count(*) DESC';
	try {
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// Table header:
		echo '
		<table align="center" cellspacing="0" cellpadding="5" width="75%">
		<tr>
			<td align="left"><b>Browser</b></td>
			<td align="left"><b>Count</b></td>	
		</tr>';
		
		// Fetch and print all the records....
		$bg = '#eeeeee'; 
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
			echo '
			<tr bgcolor="' . $bg . '">
				<td align="left">' . $row['ua_browser_name'] . '</td>
				<td align="left">' . $row['count'] . '</td>
			</tr>';
		} // End of WHILE loop.
		echo '</table>';
		mysqli_free_result ($r);
		//mysqli_close($dbc);		
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		//$hits_count = "?";
		mysqli_free_result ($r);
	}
	
	echo'<h1> - </h1>';
	
	
	
	
	
	echo'
	</center>
	</div>
	';
	
}
else
{
	echo '
	<p><img src="includes/logo_blue.png" alt="WHITELIGHTNING!" height="500" width="500"></p> 

	<p><b>Running Since 1791</b></p>
	<p></p>
	<p></p>
	';
}
?>

</center>

<?php // Include the HTML footer file:
include ('includes/footer.html');
?>
