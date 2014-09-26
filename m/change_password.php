<?php 

require_once ('includes/config.inc.php'); 
$page_title = 'WL -> Change Your Password';
include ('includes/header.html');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

if (isset($_POST['submitted'])) {
	require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	$p = FALSE;
	
	// Check for a password and match against the confirmed password:
	if (pass_match($trimmed['password1'], $trimmed['password2'])) {
		if (pass_reg($trimmed['password1'])) {	
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		}
	}	
	
	if ($p) { // If everything's OK.

		// Make the query.
		$q = "UPDATE users SET pass=SHA1('$p') WHERE user_id={$_SESSION['user_id']} LIMIT 1";	
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
			// Send an email, if desired.
			echo '<h3>Your password has been changed.</h3>';
			mysqli_close($dbc); // Close the database connection.
			include ('includes/footer.html'); // Include the HTML footer.
			exit();
			
		} else { // If it did not run OK.
		
			echo '<p class="error">Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>'; 

		}

	} else { // Failed the validation test.
//		echo '<p class="error">Please try again.</p>';		
	}
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.

?>
<center>
<h1>Change Your Password</h1>
<form action="change_password.php" method="post">
	<fieldset>
	<p><input type="password" name="password1" size="20" maxlength="20" id="Input" placeholder="New Password"/></p>
	<p><input type="password" name="password2" size="20" maxlength="20" id="Input" placeholder="Confirm New Password"/></p>
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="Change My Password" id="Button" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
	<!-- <small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small> -->
</form>
</center>
<?php
include ('includes/footer.html');
?>
