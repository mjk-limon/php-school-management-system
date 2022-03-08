<?php
	session_start();
	//error_reporting(0);
	include('include/config.php');
	include('include/checklogin.php');
	include('include/basicdb.php');
	check_login();
?>
<?php
	$total_students = get_total_rows("students", "1");
	$total_bill = 0; $total_scholarship = 0; $total_discount = 0;
	foreach(get_all("payments", "ORDER BY id DESC") as $rpmnt) {
		$total_bill += $rpmnt['bill_amount'];
		$total_discount += $rpmnt['discount'];
		$total_scholarship += $rpmnt['scholarship'] * ($rpmnt['bill_amount']/100);
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Doctor  | Dashboard</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body>
		<div id="app">
			<?php include('include/sidebar.php');?>
			<div class="app-content">
				<?php include('include/header.php');?>
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">Admin | Dashboard</h1>
								</div>
								<ol class="breadcrumb">
									<li><span>User</span></li>
									<li class="active"><span>Dashboard</span></li>
								</ol>
							</div>
						</section>
						<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"><?= $total_students ?></span>
											<h2 class="StepTitle">Total Students</h2>
											<p class="cl-effect-1">
												<a href="student-list.php">
													View Students
												</a>
											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"><?= $total_bill-$total_scholarship ?></span>
											<h2 class="StepTitle">Total Payment Received</h2>
											<p class="cl-effect-1">
												<a>&nbsp;</a>
											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"><?= $total_scholarship ?></span>
											<h2 class="StepTitle">Total Scholarship</h2>
											<p class="cl-effect-1">
												<a>&nbsp;</a>
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"><?= $total_discount ?></span>
											<h2 class="StepTitle">Total Discount</h2>
											<p class="cl-effect-1">
												<a>&nbsp;</a>
											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-smile-o fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">My Profile</h2>

											<p class="links cl-effect-1">
												<a href="edit-profile.php">
													Update Profile
												</a>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include('include/footer.php');?>	
		</div>
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<script src="assets/js/main.js"></script>
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		<style>
			.StepTitle{position:relative}
			.StepTitle span.label{position:absolute;right:11px;top:0;font-size:9px !important;border-radius:50%;width:20px;height:20px;line-height:13px;}
		</style>
	</body>
</html>