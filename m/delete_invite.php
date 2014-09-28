<?php 

require_once ('includes/config.inc.php'); 
$page_title = 'Delete a Invite';
include ('includes/header.php');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 1) {
	$url = 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

echo '<h1>Delete a Invite</h1>';
require_once (MYSQL);

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.php'); 
	exit();
}

#require_once ('../mysqli_connect.php');

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM invites WHERE invite_id=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
			// Print a message:
			echo '<p>The invite has been deleted.</p>';	
		
		} else { // If the query did not run OK.
			echo '<p class="error">The invite could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>The invite has NOT been deleted.</p>';	
	}

} else { // Show the form.

	// Retrieve the user's information:
	$q = "SELECT invite FROM invites WHERE invite_id=$id";
	$r = @mysqli_query ($dbc, $q);
	
	if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

		// Get the user's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Create the form:
		echo '<form action="delete_invite.php" method="post">
	<h3>Invite: ' . $row[0] . '</h3>
	<p>Are you sure you want to delete this invite?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	
	} else { // Not a valid user ID.
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);
		
include ('includes/footer.php');
?>
