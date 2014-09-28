<?php 

require_once ('includes/config.inc.php'); 
$page_title = 'Register';
include ('includes/header.php');

if (isset($_POST['submitted'])) { // Handle the form.

	require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$n = $p = $icode = FALSE;

	// Check for a valid name:
	if (name_reg($trimmed['name'])) {
		$n = mysqli_real_escape_string ($dbc, $trimmed['name']);
	}

	// Check for a password and match against the confirmed password:
	if (pass_match($trimmed['password1'], $trimmed['password2'])) {
		if (pass_reg($trimmed['password1'])) {	
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		}
	}	

	// Check for a valid invite:
	if (invite_reg($trimmed['icode'])) {
		$icode = mysqli_real_escape_string ($dbc, $trimmed['icode']);
	}
	
	if ($n && $p && $icode) { // If everything's OK...

		$q = "SELECT active FROM invites WHERE invite='$icode'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

		if (mysqli_num_rows($r) == 1) { // Match.

			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			$active_status = $row['active'];

			if ($active_status == 0) {

				// Make sure the name is available:
				$q = "SELECT user_id FROM users WHERE name='$n'";
				$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
				if (mysqli_num_rows($r) == 0) { // Available.
		

					$q = "UPDATE invites SET active=1 WHERE invite='$icode' LIMIT 1";
					$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
					// Print a customized message:
					if (mysqli_affected_rows($dbc) == 1) {
					//echo "<h3>Your account is now active. You may now log in.</h3>";

						// Add the user to the database:
						$q = "INSERT INTO users (pass, name) VALUES (SHA1('$p'), '$n')";
						$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
						if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
							// Finish the page:
							echo '<h3>Thank you for registering!</h3>';
							include ('includes/footer.php'); // Include the HTML footer.
							exit(); // Stop the page.
				
						} else { // If it did not run OK.
							echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience. 6</p>';
						}


					} else {
						echo '<p class="error">Your account could not be activated. Please re-check the link or contact the system administrator. 5</font></p>'; 
					}

			
				} else { // The name address is not available.
					echo '<p class="error">That name has already been registered. 4</p>';
				}
			


			} else { // If one of the data tests failed.
				echo '<p class="error">Invite Code Invalid. Error Code: 1</p>';
			}
	

		} else { // If one of the data tests failed.
			echo '<p class="error">Please re-enter your password and try again. No match in Database</p>';
		}
	

	} else { // If one of the data tests failed.
//		echo '<p class="error">Please re-enter your password and try again. 3</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.
?>
	
<center>
<h1>Register</h1>
<form action="register.php" method="post">
	<fieldset>
	
	<p><input type="text" name="name" size="20" maxlength="40" value="<?php if (isset($trimmed['name'])) echo $trimmed['name']; ?>" id="Input" placeholder="Username"/></p>
	
	<p><input type="password" name="password1" size="20" maxlength="40" id="Input" placeholder="Password"/></p>
	
	<p><input type="password" name="password2" size="20" maxlength="40" id="Input" placeholder="Confirm Password"/></p>

	<p><input type="icode" name="icode" size="32" maxlength="32" id="Input" placeholder="Invite Code"/></p>
	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Register" id="Button" placeholder="Login"/></div>
	<input type="hidden" name="submitted" value="TRUE" />
<!--<small>Password must be between 4 and 20 characters long and use only letters, numbers, and the underscore.</small>-->
</form>
</center>
<?php // Include the HTML footer.
include ('includes/footer.php');
?>
