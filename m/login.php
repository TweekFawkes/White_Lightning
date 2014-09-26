<?php

require_once ('includes/config.inc.php'); 
$page_title = 'WL -> Login';
include ('includes/header.html');

if (isset($_POST['submitted'])) {
	require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$n = $p = FALSE;

	// Check for a valid name:
	if (name_reg($trimmed['name'])) {
		$n = mysqli_real_escape_string ($dbc, $trimmed['name']);
	}

	// Check for a valid password:
	if (pass_reg($trimmed['pass'])) {	
		$p = mysqli_real_escape_string ($dbc, $trimmed['pass']);
	}

	if ($n && $p) { // If everything's OK.
	
		// Query the database:
		$q = "SELECT user_id, name, user_level FROM users WHERE (name='$n' AND pass=SHA1('$p'))";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows($r) == 1) { // A match was made.

			// Register the values & redirect:
			$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
			mysqli_free_result($r);
			mysqli_close($dbc);
							
			$url = BASE_URL . 'index.php'; // Define the URL:
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
				
		} else { // No match was made.
			echo '<p class="error">The name and password entered do not match those on file.</p>';
		}
		
	} else { // If everything wasn't OK.
//		echo '<p class="error">Please try again.</p>';
	}
	
	mysqli_close($dbc);

} // End of SUBMIT conditional.
?>
<center>
<h1>Login</h1>
<form action="login.php" method="post">
	<fieldset>
	<p><input type="text" name="name" id="Input" size="20" maxlength="40" placeholder="Username" /></p>
	<p><input type="password" name="pass" id="Input" size="20" maxlength="40" placeholder="Password" /></p>
	<div align="center"><input type="submit" name="submit" value="Login" id="Button" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
	</fieldset>
</form>
<!-- <p><b>Your browser must allow cookies in order to log in.</b></p> -->
</center>

<?php // Include the HTML footer.
include ('includes/footer.html');
?>
