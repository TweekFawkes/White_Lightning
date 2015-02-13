<?php 
	define('LIVE', TRUE);
	define('EMAIL', 'bhelms85@gmail.com');
	define('MYSQL', '/var/mysqli_connect.php');
	date_default_timezone_set ('US/Eastern');
	
	// ****************************************** //
	// ************ ERROR MANAGEMENT ************ //

	// Create the error handler:
	function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {

		// Build the error message.
		$message = "<p>An error occurred in script '$e_file' on line $e_line: $e_message\n<br />";
		
		// Add the date and time:
		$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n<br />";
		
		// Append $e_vars to the $message:
		$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n</font>";
		
		if (!LIVE) { // Development (print the error).
		
			echo '<div class="error">' . $message . '</div><br />';
			
		} else { // Don't show the error:
		
			// Send an email to the admin:
			mail(EMAIL, 'Site Error!', $message, 'From: email@example.com');
			
			// Only print an error message if the error isn't a notice:
			if ($e_number != E_NOTICE) {
				echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div><br />';
			}
		} // End of !LIVE IF.

	} // End of my_error_handler() definition.

	// Use my error handler.
	set_error_handler ('my_error_handler');

	// ****************************************** //
	// ************ SECURITY MANAGEMENT ************ //

	function name_reg ($name) {

		$reg_ex = "/[A-Za-z0-9-].{4,20}/";
		
		if (preg_match ($reg_ex, $name)) {
			return true;
		} else {
			echo '<font color="red">Please enter a valid name! Names must be a 5 to 20 characters.</font>';
		} 
		return false;
	}

	function pass_match ($password1, $password2) {

		if ($password1 == $password2) {
			return true;
		} else {
			echo '<font color="red">Your password did not match the confirmed password!</font>';
		}
		return false;
	}

	function pass_reg ($password) {

		// 8 to 15 character string with at least one upper case letter , one lower case letter , and one digit		
		$reg_ex = "/(?=.*[a-z])(?=.*[A-Z]).{8,15}/";
		
		if (preg_match ($reg_ex, $password)) {
			return true;
		} else {
			echo '<font color="red">Please enter a valid password!</font>';
		} 
		return false;
	}
?>
