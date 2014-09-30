<?php

$page_purpose = "Hit";
$page_filename = "view_hits.php";
$db_table_name = "hits";
$db_table_id = "hit_id";

require_once ('includes/config.inc.php'); 
$page_title = 'View ' . $page_purpose . 's';
include ('includes/header.php');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {
	$url = 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

// --- --- ---




// --- --- ---

echo '<h1>View ' . $page_purpose . 's</h1>';
require_once (MYSQL);

// Number of records to show per page:
$display = 100;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(" . $db_table_id . ") FROM " . $db_table_name;
	$r = @mysqli_query ($dbc, $q);
	$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'n';

// Determine the sorting order:
switch ($sort) {
	case 'dt':
		$order_by = 'php_date_time DESC'; // ASC
		break;
	case 'ra':
		$order_by = 'php_remote_addr ASC'; // ASC
		break;
	case 'of':
		$order_by = 'ua_os_family ASC'; // ASC
		break;
	case 'bn':
		$order_by = 'ua_browser_name ASC'; // ASC
		break;
	case 'bv':
		$order_by = 'ua_browser_version ASC'; // ASC
		break;
	default:
		$order_by = 'php_date_time DESC'; // ASC
		$sort = 'dt';
		break;
}


	
// Make the query:
$q = "SELECT ".$db_table_id.", CONCAT_WS(' ', php_date, php_time) AS php_date_time, php_remote_addr, php_http_referer, php_http_user_agent, ua_os_family, ua_os_version, ua_os_platform, ua_browser_wow64, ua_browser_name, ua_browser_version, pd_os, pd_br, pd_br_ver, pd_br_ver_full, me_mshtml_build, be_office, pd_reader, pd_flash, pd_java, pd_qt, pd_rp, pd_shock, pd_silver, pd_wmp, pd_vlc FROM ".$db_table_name." ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b><a href="'.$page_filename.'?sort=dt">Date Time</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=ra">Remote Addr</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=of">OS Family</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=bn">Browser Name</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=bv">Browser Version</a></b></td>

</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left">' . $row['php_date_time'] . '</td>
		<td align="left">' . $row['php_remote_addr'] . '</td>
		<td align="left">' . $row['ua_os_family'] . '</td>
		<td align="left">' . $row['ua_browser_name'] . '</td>
		<td align="left">' . $row['ua_browser_version'] . '</td>
	</tr>
	<tr bgcolor="' . $bg . '">
		<td align="left"  colspan="5">';
		// |  |  | pd_flash |   |    |    |  |  |   |   |
		
		if( !(strcmp( $row['me_mshtml_build'], "unknown") == 0) )
		{
			echo "mshtml: " . $row['me_mshtml_build'] . " | ";
		}
		
		if( !(strcmp( $row['be_office'], "unknown") == 0) )
		{
			echo "office: " . $row['be_office'] . " | ";
		}
		
		if( !(strcmp( $row['pd_reader'], "unknown") == 0) )
		{
			echo "reader: " . $row['pd_reader'] . " | ";
		}
		
		if( !(strcmp( $row['pd_flash'], "unknown") == 0) )
		{
			echo "flash: " . $row['pd_flash'] . " | ";
		}
		
		if( !(strcmp( $row['pd_java'], "unknown") == 0) )
		{
			echo "java: " . $row['pd_java'] . " | ";
		}
		
		if( !(strcmp( $row['pd_qt'], "unknown") == 0) )
		{
			echo "qt: " . $row['pd_qt'] . " | ";
		}
		
		if( !(strcmp( $row['pd_rp'], "unknown") == 0) )
		{
			echo "rp: " . $row['pd_rp'] . " | ";
		}
		
		if( !(strcmp( $row['pd_shock'], "unknown") == 0) )
		{
			echo "shock: " . $row['pd_shock'] . " | ";
		}
		
		if( !(strcmp( $row['pd_silver'], "unknown") == 0) )
		{
			echo "silver: " . $row['pd_silver'] . " | ";
		}
		
		if( !(strcmp( $row['pd_wmp'], "unknown") == 0) )
		{
			echo "wmp: " . $row['pd_wmp'] . " | ";
		}
		
		if( !(strcmp( $row['pd_vlc'], "unknown") == 0) )
		{
			echo "vlc: " . $row['pd_vlc'] . " | ";
		}

	echo '</td></tr>';
	
	echo '<tr bgcolor="' . $bg . '">';
	echo '<td align="left"  colspan="5">';
	echo $row['php_http_user_agent'];
	echo '</td></tr>';
	//php_http_user_agent
	
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
//mysqli_close($dbc);

// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	echo '<br /><p>';
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="'.$page_filename.'?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="'.$page_filename.'?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="'.$page_filename.'?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</p>'; // Close the paragraph.
	
} // End of links section.

// Include the HTML footer.	
include ('includes/footer.php');
mysqli_close($dbc);
?>
