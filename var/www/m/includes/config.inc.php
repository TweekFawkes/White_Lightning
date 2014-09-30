<?php 

// ********************************** //
// ************ SETTINGS ************ //

// Flag variable for site status:
define('LIVE', FALSE);

// Admin contact address:
define('EMAIL', 'InsertRealAddressHere');

// Site URL (base for all redirections):
define ('BASE_URL', 'http://qu.gs/m/');


// Location of the MySQL connection script:
define ('MYSQL', '/var/mysqli_connect.php');

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set ('US/Eastern');

// --- --- ---


// ************ SETTINGS ************ //
// ********************************** //


// ****************************************** //
// ************ ERROR MANAGEMENT ************ //

// Create the error handler:
function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {

	// Build the error message.
	$message = "<p>An error occurred in script '$e_file' on line $e_line: $e_message\n<br />";
	
	// Add the date and time:
	$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n<br />";
	
	// Append $e_vars to the $message:
	$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n</p>";
	
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

// ************ ERROR MANAGEMENT ************ //
// ****************************************** //


// ****************************************** //
// ************ SECURITY MANAGEMENT ************ //

function name_reg ($name) {

	$reg_ex = "/[A-Za-z0-9-].{2,40}/";
	
	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<p class="error">Please enter a valid name!</p>';
		echo '<p class="error">Names must be a 2 to 40 character string using only lower and upper case letters and numbers.</p>';
	} 
	return false;
}

function invite_reg ($name) {

	$reg_ex = "/^[a-z0-9]{32}$/";
	
	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<p class="error">Please enter a invite code!</p>';
		echo '<p class="error">Invite Codes must be a 32 character string using only lower case letters and numbers.</p>';
	} 
	return false;
}

function link_name_reg ($name) {

	$reg_ex = "/[\w\s-].{2,32}/";

	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<p class="error">Please enter a valid link name!</p>';
		echo '<p class="error">Link names must be a 2 to 32 character string using only lower and upper case letters and numbers and underscores.</p>';
	} 
	return false;
}

function url_reg ($name) {

	// wget limit is set to 1001 while links is set to 3000
	// http://www.ietf.org/rfc/rfc1738.txt
//	$reg_ex = "/^https?:\/\/[\.\w\/-\?]{2,996}$/";
//	$reg_ex = "/^https?:\/\/[(\?)=\.\w\/-:+&@;]{2,996}$/";
//	$reg_ex = "/^https?:\/\/[(\?)=\.\w\/\-:+&@;]{2,996}$/";
	$reg_ex = "/^https?:\/\/[(\?)=\.\w\/\-:+&@;~]{2,996}$/";
	
	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<p class="error">Please enter a valid link url!</p>';
		echo '<p class="error">Link url must be a 2 to 1001 character string starting with ' . $reg_ex . ' </p>';
	} 
	return false;
}

function ip_reg ($name) {

	// wget limit is set to 1001 while links is set to 3000
	// http://www.ietf.org/rfc/rfc1738.txt
//	$reg_ex = "/^https?:\/\/[\.\w\/-\?]{2,996}$/";
//	$reg_ex = "/^https?:\/\/[(\?)=\.\w\/-:+&@;]{2,996}$/";
//	$reg_ex = "/^https?:\/\/[(\?)=\.\w\/\-:+&@;]{2,996}$/";
//	$reg_ex = "/^https?:\/\/[(\?)=\.\w\/\-:+&@;~]{2,996}$/";
	
	$reg_ex = "/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
	
	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<p class="error">Please enter a valid IP address!</p>';
		echo '<p class="error">IP address must be a 2 to 1001 character string and work within this regex: ' . $reg_ex . ' </p>';
	} 
	return false;
}

function pass_match ($password1, $password2) {

	if ($password1 == $password2) {
		return true;
	} else {
		echo '<p class="error">Your password did not match the confirmed password!</p>';
	}
	return false;
}

function pass_reg ($password) {

	// 8 to 15 character string with at least one upper case letter , one lower case letter , and one digit		
	$reg_ex = "/(?=.*[a-z])(?=.*[A-Z]).{8,15}/";
	
	if (preg_match ($reg_ex, $password)) {
		return true;
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
		echo '<p class="error">Passwords must be a 8 to 15 character string with at least one upper case letter , one lower case letter , and one digit.</p>';
	} 
	return false;
}

// ************ SECURITY MANAGEMENT ************ //
// ****************************************** //

?>
