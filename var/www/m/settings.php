<?php
	session_start();
	require_once ('includes/config.inc.php'); 
	
	if (isset($_SESSION['name'])) {
		require_once (MYSQL);	
	}else{
		header("Location: login.php");
	}
?>

<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WhiteLightning -> Settings</title>

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
                            Settings
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Settings
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php 
				$t1 = '<li class="active">';
				$t2 = '<li>';
				$tab_1 = 'tab active';
				$tab_2 = 'tab';
				
				if (isset($_POST['submit']) and $_POST['submit'] == "Add User"){
					$t2 = '<li class="active">';
					$t1 = '<li>';
					$tab_2 = 'tab active';
					$tab_1 = 'tab';
				}
				if ($_SESSION['user_level'] == 1){
					echo '<div class="tabs">
					<ul class="tab-links">
						'.$t1.'<a href="#tab1">User Settings</a></li>
							'.$t2.'<a href="#tab2">Administrator Settings</a></li>
					</ul>
				 
					<div class="tab-content">
						<div id="tab1" class="'.$tab_1.'">'; }?>
							<div class="row">
								<div class="col-lg-4">
									<h2>Change Password</h2>
										<form action="settings.php" method="post">
											<div class="form-group">
												<input type="password" name="current_pass" id="Input" class="form-control input-lg" placeholder="Current Password">
											</div>
											<div class="form-group">
												<input type="password" name="change_pass" id="Input" class="form-control input-lg" placeholder="New Password">
											</div>
											<div class="form-group">
												<input type="password" name="vfy_change_pass" id="Input" class="form-control input-lg" placeholder="Verify New Password">
											</div>
											<div>
												<?php
													if (isset($_POST['submit']) and $_POST['submit'] == "Change Password") { // Handle the form.	
														if ($_POST['current_pass'] == ''){
															echo '<font color="red">Must fill out all fields.</font>';
														}elseif ($_POST['vfy_change_pass'] != $_POST['change_pass']){
															echo '<font color="red">New passwords don\'t match.</font>';	
														}elseif ($_POST['current_pass'] == $_POST['change_pass']){
															echo '<font color="red">New password cannot match current password.</font>';											
														}
														$current_user = $_SESSION['name'];
														$current_pass = sha1($_POST['current_pass']);
														$new_pass = sha1($_POST['change_pass']);
														
														$q = 'SELECT pass FROM users WHERE name = "'.$current_user.'"';
														$r = @mysqli_query ($dbc, $q); // Run the query.
														$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
														if ($row['pass'] != $current_pass){
															echo '<font color="red">Current password does not match that on record.</font>';
														}else{
															$q = 'UPDATE users SET pass="'.$new_pass.'" WHERE name="'.$current_user.'"';
															$r = @mysqli_query ($dbc, $q);
															echo '<font color="green">Password successfully changed.</font>';															
														}
													
													}
												?>
												<br>
												<input type="submit" name="submit" value="Change Password" id="Button" class="btn btn-default"/>
											</div>
									</div>
								<?php 
								if ($_SESSION['user_level'] == 1){
								echo '</div>
							</div>
							<div id="tab2" class="'.$tab_2.'">
								<div class="row">
									<div class="col-lg-4">
										<h2>Add User</h2>
										<form action="settings.php" method="post">
											<div class="form-group">
												<input class="form-control input-lg" name="new_user" placeholder="User Name">
											</div>
											<div class="form-group">
												<input type="password" name="new_pass" id="Input" class="form-control input-lg" placeholder="New Password">
											</div>
											<div class="form-group">
												<input type="password" name="vfy_new_pass" id="Input" class="form-control input-lg" placeholder="Verify New Password">
											</div>
											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="add_admin" value="True">Admin
													</label>
												</div>
											</div>';
											if (isset($_POST['submit']) and $_POST['submit'] == "Add User") { // Handle the form.	
												if ($_POST['new_user'] == ''){
													echo '<font color="red">Must fill out all fields.</font>';
												}elseif ($_POST['vfy_new_pass'] != $_POST['new_pass']){
													echo '<font color="red">New passwords don\'t match.</font>';											
												}
												if (name_reg($_POST['new_user']) and pass_reg($_POST['new_pass'])){
													$hash = sha1($_POST['new_pass']);
													$new_user = $_POST['new_user'];
													if (isset($_POST['add_admin'])){
														$group = 1;
													}else{
														$group = 0;
													}
													
													$q = 'SELECT COUNT(user_id) as n FROM users';
													$r = @mysqli_query ($dbc, $q); // Run the query.
													while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
														$uid = $row['n'] + 1;
													}
												
													$q = 'SELECT COUNT(name) as n FROM users WHERE name = "'.$new_user.'"';
													$r = @mysqli_query ($dbc, $q); // Run the query.
													$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
													if ($row['n'] == 0) {
														$i = 'INSERT INTO users VALUES("'.$uid.'","'.$new_user.'","'.$hash.'","'.$group.'")';
														$r = @mysqli_query ($dbc, $i); // Run the query.
														echo '<font color="green">New user created.</font>';
													}else{
														echo '<font color="red">User already exists in db.</font>';
													}
												}
											}
											echo '<br>
											<div>
												<input type="submit" name="submit" value="Add User" id="Button" class="btn btn-default"/>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>';} ?>
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
