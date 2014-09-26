<?php

require_once ('includes/config.inc.php');
$page_title = 'WL -> Edit a Invite';
include ('includes/header.html');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 1) {
	$url = 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

echo '<h1>Edit a Invite</h1>';
require_once (MYSQL);

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html'); 
	exit();
}

#require_once ('../mysqli_connect.php'); 

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array();
	
	// Check for a name:
	if (empty($_POST['invite'])) {
		$errors[] = 'You forgot to enter your invite.';
	} else {
		$n = mysqli_real_escape_string($dbc, trim($_POST['invite']));
	}

	// Check for a user level:
	//if (empty($_POST['user_level'])) {
	//	$errors[] = 'You forgot to enter your user level.';
	//} else {
		$ul = mysqli_real_escape_string($dbc, trim($_POST['active']));
	//}

	if (empty($errors)) { // If everything's OK.
	
		//  Test for unique email address:
		$q = "SELECT invite_id FROM invites WHERE invite='$n' AND invite_id != $id";
		$r = @mysqli_query($dbc, $q);
		if (mysqli_num_rows($r) == 0) {

			// Make the query:
			$q = "UPDATE invites SET invite='$n', active='$ul' WHERE invite_id=$id LIMIT 1";
			$r = @mysqli_query ($dbc, $q);
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Print a message:
				echo '<p>The invite has been edited.</p>';	
							
			} else { // If it did not run OK.
				echo '<p class="error">The invite could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
			}
				
		} else { // Already registered.
			echo '<p class="error">The email address has already been registered. 3</p>';
		}
		
	} else { // Report the errors.
	
		echo '<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again. 2</p>';
		
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT invite, active FROM invites WHERE invite_id=$id";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:
	echo '<form action="edit_invite.php" method="post">
<p>Invite: <input type="text" name="invite" size="32" maxlength="32" value="' . $row[0] . '" /></p>
<p>Active: <input type="text" name="active" size="1" maxlength="1" value="' . $row[1] . '" /></p>
<p><input type="submit" name="submit" value="Submit" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
</form>';

} else { // Not a valid user ID.
	echo '<p class="error">This page has been accessed in error. 1</p>';
}

mysqli_close($dbc);
		
include ('includes/footer.html');
?>
