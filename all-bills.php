<?php
	session_start();
	include('include/config.php');
	include('include/checklogin.php');
	require_once('include/basicdb.php');
	check_login();
?>
<?php
	if(isset($_GET['sort'])) {
		$sdate = $conn->real_escape_string($_GET['date']);
		$sinv_id = $conn->real_escape_string($_GET['inv_id']);
		$ssql = ($sinv_id) ? "inv_id = '{$sinv_id}'" : "bill_date LIKE '{$sdate}%'";
	} else $ssql = 1;
	
	$result_all	= get_some_data("payments", $ssql);
	$tna = $result_all->num_rows;
	$page = isset($_GET['page']) ? $_GET['page'] :  1;
	$offset = (($page * 50) - 50); $looplimit = $tna/50; 
	$looplimit = (is_float($looplimit)) ? intval($looplimit)+1 : $looplimit;
	$result_app	= get_some_data("payments", "{$ssql} GROUP BY inv_id ORDER BY id DESC LIMIT 50 OFFSET {$offset}");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bills</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
	<div id="app">
		<?php include('include/sidebar.php');?>
		<div class="app-content">
			<?php include('include/header.php');?>
			<div class="main-content">
				<div class="wrap-content container" id="container">
					<section id="page-title">
						<div class="row">
							<div class="col-sm-8"><h1 class="mainTitle">Admin | Bills</h1></div>
							<ol class="breadcrumb">
								<li><span>Admin</span></li>
								<li class="active"><span>Bills</span></li>
							</ol>
						</div>
					</section>
					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<span class="text-info"><?= (isset($_SESSION['msg'])) ? $_SESSION['msg'] : null ?><?php $_SESSION['msg']=""; ?></span>
								<h5 class="over-title margin-bottom-15">Bills</h5>
								<p>
									<strong>Sort By:</strong>
									<form class="form-inline" action="" method="GET">
										<input type="hidden" name="sort" />
										<div class="form-group">
											<input type="date" name="date" class="form-control" />
										</div>
										<div class="form-group">
											<label>Or, </label>
											<input type="text" name="inv_id" class="form-control" placeholder="INV_" />
										</div>
										<button type="submit" class="btn btn-default">Submit</button>
									</form>
								</p>
								<table class="table table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>Student Info</th>
											<th>Date</th>
											<th>Bill Info</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$total_bill = 0; $total_discount = 0; $total_scholarship = 0;
										while($row = $result_app->fetch_array()) {
											$std_info = get_single_data("students", "id = '{$row['std_id']}'");
									?>
										<tr>
											<td>
												<strong><?= ($std_info['full_name']) ? $std_info['full_name'] : "Student Left" ?></strong>
												<p style="margin-bottom: 0">Class: <?= $std_info['class_name'] ?>, Section: <?= $std_info['section_name'] ?></p>
												<p style="margin-bottom: 0">Roll: <?= $std_info['roll_no'] ?></p>
											</td>
											<td><?= date("j F, Y", strtotime($row['bill_date'])) ?></td>
											<td>
												<table class="bill-his-dis-table">
													<tr><th>Title</th><th>Amount</th><th>Scholarsihp</th><th>Discount</th></tr>
												<?php
													$rb_dis = get_some_data("payments", "inv_id = '{$row['inv_id']}'");
													while($rbd = $rb_dis->fetch_array()) {
														$total_bill += $rbd['bill_amount'];
														$total_discount += $rbd['discount'];
														$total_scholarship += ($rbd['bill_amount'] * ($rbd['scholarship']/100));
												?>
													<tr>
														<td><?= $rbd['bill_title'] ?></td>
														<td><?= $rbd['bill_amount'] ?></td>
														<td><?= $rbd['scholarship'] ?>%</td>
														<td><?= $rbd['discount'] ?></td>
													</tr>
												<?php } mysqli_free_result($rb_dis); ?>
												</table>
											</td>
											<td>
												<a href="bill-invoice.php?bid=<?= $row['inv_id'] ?>"><button class="btn btn-info"><i class="fa fa-file"></i> View Invoice</button></a>
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
								<!-- Compulsory Paging Code -->
								<?php
									$prev_page = ($page == 1) ? 1 : $page-1;
									$next_page = ($page == $looplimit) ? $looplimit : $page+1;
								?>
								<nav aria-label="Page navigation example">
									<ul class="pagination">
									<li class="page-item"><a class="page-link" href="?page=<?= $prev_page; ?>">&laquo;</a></li>
								<?php 
									for($pi = 1; $pi <= $looplimit; $pi++){
										$active = ($page == $pi) ? 'active' : '';
								?>
									<li class="page-item <?= $active; ?>"><a class="page-link" href="?page=<?= $pi; ?>"><?= $pi ?></a></li>
								<?php } ?>
									<li class="page-item"><a class="page-link" href="?page=<?= $next_page; ?>">&raquo;</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('include/footer.php');?>
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
	<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script src="vendor/autosize/autosize.min.js"></script>
	<script src="vendor/selectFx/classie.js"></script>
	<script src="vendor/selectFx/selectFx.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/main.js"></script>
	<script src="assets/js/form-elements.js"></script>
	<script>jQuery(document).ready(function() {Main.init();FormElements.init();});</script>
</body>
</html>