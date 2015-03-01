<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="WhiteLightning Framework">
    <meta name="author" content="FE: Cr0n1c, BE: TweekFawkes, Support: h4bu">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

    <div id="wrapper">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">WhiteLightning</a>
			</div>
			<ul class="nav navbar-right top-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['name'] ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="settings.php"><i class="fa fa-fw fa-gear"></i> Settings</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
						</li>
					</ul>
				</li>
			</ul>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav side-nav">
					<li class="active">
						<a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
					</li>
					<li>
						<a href="tasking.php"><i class="fa fa-fw fa-edit"></i> Exploit Taskings</a>
					</li>
					<li>
						<a href="logs.php"><i class="fa fa-fw fa-table"></i> Exploit Logs</a>
					</li>
				</ul>
			</div>
		</nav>
    </div>
</body>
</html>
