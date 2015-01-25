<?php
	require_once ('includes/config.inc.php'); 
	session_start();
	session_unset();
	session_destroy();
?>

<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>WhiteLightning -> Login</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
	
	<!--login modal-->
		<script>
			document.body.style.backgroundImage="url('img/whitelightning.jpg')";
		</script>
		<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h1 class="text-center">WhiteLightning v2.0</h1>
					</div>
					<div class="modal-body">
						<form class="form col-md-12 center-block" action="login.php" method="post">
						<div class="form-group">
							<input type="text" name="name" id="Input" class="form-control input-lg" placeholder="Username">
						</div>
						<div class="form-group">
							<input type="password" name="pass" id="Input" class="form-control input-lg" placeholder="Password">
						</div>
						<div class="form-group">
							<input type="submit" name="submit" value="Submit" id="Button" class="btn btn-primary btn-lg btn-block"/>
							<?php
								if (isset($_POST["submit"])) {
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

									if ($n && $p) { // If everything is OK.
										session_start();
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
											echo '<font color="red">Please enter a valid username and password!</font>';
										}	
									}
									mysqli_close($dbc); 
								}// End of SUBMIT conditional.
							?>
						</div>
						</form>
					</div>
					<div class="modal-footer">
						<div class="col-md-12">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
						</div>	
					</div>
				</div>
			</div>
		</div>
		<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>