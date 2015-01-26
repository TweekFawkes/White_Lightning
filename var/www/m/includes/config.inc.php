<?php 

// ********************************** //
// ************ SETTINGS ************ //

// Flag variable for site status:
define('LIVE', FALSE);

// Admin contact address:
define('EMAIL', 'bhelms85@gmail.com');

// Site URL (base for all redirections):
define ('BASE_URL', 'http://192.168.30.206/n/');

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

// ************ ERROR MANAGEMENT ************ //
// ****************************************** //


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

function invite_reg ($name) {

	$reg_ex = "/^[a-z0-9]{32}$/";
	
	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<font color="red">Please enter a invite code!\n</font><br>';
		echo '<font color="red">Invite Codes must be a 32 character string using only lower case letters and numbers.</font>';
	} 
	return false;
}

function link_name_reg ($name) {

	$reg_ex = "/[\w\s-].{2,32}/";

	if (preg_match ($reg_ex, $name)) {
		return true;
	} else {
		echo '<font color="red">Please enter a valid link name!</font>';
		echo '<font color="red">Link names must be a 2 to 32 character string using only lower and upper case letters and numbers and underscores.</font>';
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
		echo '<font color="red">Please enter a valid link url!</font>';
		echo '<font color="red">Link url must be a 2 to 1001 character string starting with ' . $reg_ex . ' </font>';
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
		echo '<font color="red">Please enter a valid IP address!</font>';
		echo '<font color="red">IP address must be a 2 to 1001 character string and work within this regex: ' . $reg_ex . ' </font>';
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

// ************ SECURITY MANAGEMENT ************ //
// ****************************************** //

?>
