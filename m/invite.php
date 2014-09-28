<?php 

require_once ('includes/config.inc.php'); 
$page_title = 'Create Invites';
include ('includes/header.php');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {
	$url = 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}


if (isset($_POST['submitted'])) { // Handle the form.

	require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$ni = FALSE;
	

	
//	if ($ni) { // If everything's OK...

		if (isset($_SESSION['user_id'])) {
			//echo "{$_SESSION['user_id']}";
			$uid = $_SESSION['user_id'];
			
			$q = "INSERT INTO invites (invite, active) VALUES ('$uid', '0')";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				$q = "SELECT invite_id FROM invites WHERE invite='$uid'";
				$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
				if (mysqli_num_rows($r) == 1) { // Match.

					$row = mysqli_fetch_array($r, MYSQLI_ASSOC);

					$iid = $row['invite_id'];

					$q = "INSERT INTO users_invites (user_id, invite_id) VALUES ('$uid', '$iid')";
					$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

					if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
					
						// Create the invite code:
						$icode = md5(uniqid(rand(), true));

						$q = "UPDATE invites SET invite='$icode' WHERE invite_id ='$iid' LIMIT 1";
						$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
					
						if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

							
							// Finish the page:
							echo '<h3>Invite Code is: </h3>';
							echo $icode;
							include ('includes/footer.php'); // Include the HTML footer.
							exit(); // Stop the page.
						} else { // If it did not run OK.
							echo '<p class="error">You could generate an invite code due to a system error. We apologize for any inconvenience. 1</p>';
						}
					} else { // If it did not run OK.
						echo '<p class="error">You could generate an invite code due to a system error. We apologize for any inconvenience. 2</p>';
					}
				} else { // If it did not run OK.
					echo '<p class="error">You could generate an invite code due to a system error. We apologize for any inconvenience. 3</p>';
				}

			} else { // If it did not run OK.
				echo '<p class="error">You could generate an invite code due to a system error. We apologize for any inconvenience. 4</p>';
			}

		} else { // If it did not run OK.
			echo '<p class="error">You could generate an invite code due to a system error. We apologize for any inconvenience. 5</p>';
		}

	mysqli_close($dbc);
//	}

} // End of the main Submit conditional.
?>
<center>
<h1>Create Invite</h1>
<form action="invite.php" method="post">
	<fieldset>
	
	<!-- <p><input type="text" name="numinvites" size="20" maxlength="20" value="<?php if (isset($trimmed['numinvites'])) echo $trimmed['numinvites']; ?>"  id="Input" placeholder="Number of Invites"/></p> -->
	</fieldset>

	<div align="center"><input type="submit" name="submit" value="Create an Invite Code Now!" id="Button" /></div>
	<input type="hidden" name="submitted" value="TRUE" />

</form>
</center>
<?php // Include the HTML footer.
include ('includes/footer.php');
?>
