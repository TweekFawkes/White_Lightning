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
	<meta name="generator" content="Bootply" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>WhiteLightning -> Login</title>
	
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	
    <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
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
								$trimmed = array_map('trim', $_POST);
								$n = $p = FALSE;
								
								if (name_reg($trimmed['name'])) {
									$n = mysqli_real_escape_string ($dbc, $trimmed['name']);
								}
								
								if (pass_reg($trimmed['pass'])) {	
									$p = mysqli_real_escape_string ($dbc, $trimmed['pass']);
								}

								if ($n && $p) {
									session_start();
									$q = "SELECT user_id, name, user_level FROM users WHERE (name='$n' AND pass=SHA1('$p'))";
									$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

									if (@mysqli_num_rows($r) == 1) { 
										$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
										mysqli_free_result($r);
										mysqli_close($dbc);
										ob_end_clean(); 
										header("Location: index.php");
										exit(); 	
									} else { 
										echo '<font color="red">Please enter a valid username and password!</font>';
									}	
								}
								mysqli_close($dbc); 
							}
						?>
					</div>
					</form>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
</body>
</html>