<?php 

$page_purpose = "Throw";
$page_filename = "view_throws.php";
$db_table_name = "throws";
$db_table_id = "throw_id";

require_once ('includes/config.inc.php'); 
$page_title = 'WL -> View ' . $page_purpose . 's';
include ('includes/header.html');

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
	case 'hi':
		$order_by = 'hit_id ASC'; // ASC
		break;
	case 'ex':
		$order_by = 'msf_exploit_full_path ASC'; // ASC
		break;
	case 'ta':
		$order_by = 'msf_target ASC'; // ASC
		break;
	default:
		$order_by = 'php_date_time DESC'; // ASC
		$sort = 'dt';
		break;
}


// Make the query:
$q = "SELECT ".$db_table_id.", CONCAT_WS(' ', php_date, php_time) AS php_date_time, hit_id, msf_exploit_full_path, msf_target FROM ".$db_table_name." ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b><a href="'.$page_filename.'?sort=dt">Date Time</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=hi">Hit ID</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=ex">Exploit Name</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=ta">Target Number</a></b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left">' . $row['php_date_time'] . '</td>
		<td align="left">' . $row['hit_id'] . '</td>
		<td align="left">' . $row['msf_exploit_full_path'] . '</td>
		<td align="left">' . $row['msf_target'] . '</td>
	</tr>';	
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
include ('includes/footer.html');
mysqli_close($dbc);
?>
