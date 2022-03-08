<?php
	session_start();
	include('include/config.php');
	include('include/checklogin.php');
	require_once('include/basicdb.php');
	check_login();
?>
<?php
	if(isset($_POST['add_bill'])) {
		$bt_array = array_filter($_POST['bill_title']);
		$total_bill = count($bt_array); $total_inserted = 0; $error_array = array();
		
		$fields['std_id'] = $conn->real_escape_string($_POST['std_id']);
		$fields['inv_id'] = "INV_".date("d").random_token("3").date("m").random_token("3").date("y");
		$fields['bill_date'] = date("Y-m-d H:i:s");
		foreach($bt_array as $key => $bill_title) {
			$fields['bill_title'] = $conn->real_escape_string($bill_title);
			$fields['bill_amount'] = $conn->real_escape_string($_POST['bill_amount'][$key]);
			$fields['scholarship'] = $conn->real_escape_string($_POST['scholarship'][$key]);
			if($conn->query(InsertInTable("payments", $fields))) $total_inserted += 1;
			else $error_array[] = "Error at position ". $key .". Description: " .$conn->error;
		}
		if($total_bill == $total_inserted) header("Location: bill-invoice.php?bid=". $bid );
		else print_r($error_array);
	}
?>
<?php
	$std_id = (isset($_GET['stid'])) ? $conn->real_escape_string($_GET['stid']) : exit("Invaild Student Id");
	$std_info = get_single_data("students", "id = '{$std_id}'");
	$stdimg = file_exists($std_info['image']) ? $std_info['image'] : "img/student.png";
	$r_bills = get_some_data("payments", "std_id = '{$std_info['id']}' GROUP BY inv_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin | Student Info</title>
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
	<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	<script type="text/javascript">
		function valid(){
			if(document.adddoc.npass.value!= document.adddoc.cfpass.value) {
				alert("Password and Confirm Password Field do not match  !!");
				document.adddoc.cfpass.focus();
				return false;
			}
			return true;
		}
	</script>
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
								<h1 class="mainTitle">Admin | Student Info</h1>
							</div>
							<ol class="breadcrumb">
								<li><span>Admin</span></li>
								<li class="active"><span>Student Info</span></li>
							</ol>
						</div>
					</section>
					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<div class="row margin-top-30">
									<div class="col-lg-6 col-md-6">
										<div class="std-info">
											<p><small>Student Id: <?= $std_info['id'] ?></small></p>
											<h3><?= $std_info['full_name'] ?></h3>
											<p><strong>Father's name:</strong> <?= $std_info['fathers_name'] ?></p>
											<p><strong>Mother's name:</strong> <?= $std_info['mothers_name'] ?></p>
											<p>
												<strong>Class:</strong> <?= $std_info['class_name'] ?>, 
												<strong>Section:</strong> <?= $std_info['section_name'] ?>, 
												<strong>Roll:</strong> <?= $std_info['roll_no'] ?>
											</p>
											<p><strong>Address:</strong> <?= $std_info['address'] ?></p>
											<p><strong>Gender:</strong> <?= $std_info['gender'] ?></p>
											<p><strong>Birth Date:</strong> <?= date("d/m/Y", strtotime($std_info['birth_date'])) ?></p>
											<p><strong>Mobile Number:</strong> <?= $std_info['mobile_number'] ?></p>
											<p><strong>Blood Group:</strong> <?= $std_info['blood_group'] ?></p>
										</div>
									</div>
									<div class="col-lg-2 col-md-2">
										<a href="bill-pay.php?stdid=<?= $std_info['id'] ?>" class="btn btn-success margin-bottom-5"><i class="fa fa-money"></i> Add Bill</a>
										<a href="edit-student.php?stdid=<?= $std_info['id'] ?>" class="btn btn-info"><i class="fa fa-pencil"></i> Edit Info</a>
									</div>
									<div class="col-lg-4 col-md-4 text-right">
										<div class="stdinfo-img-container">
											<img src="<?= $stdimg ?>" class="img-responsive" alt="" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8 col-md-8">
										<div class="bill-history">
										<?php if($r_bills->num_rows > 0){ ?>
											<h3>Bill History</h3>
											<table class="table table-striped">
												<thead>
													<tr>
														<th>Date</th>
														<th>Description</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												<?php
													$total_bill = 0; $total_discount = 0; $total_scholarship = 0;
													while($rb = $r_bills->fetch_array()) {
												?>
													<tr>
														<td><?= date("d/m/Y", strtotime($rb['bill_date'])) ?></td>
														<td>
															<table class="bill-his-dis-table">
																<tr><th>Title</th><th>Amount</th><th>Scholarsihp</th><th>Discount</th></tr>
															<?php
																$rb_dis = get_some_data("payments", "inv_id = '{$rb['inv_id']}'");
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
														<td><a href="bill-invoice.php?bid=<?= $rb['inv_id'] ?>" class="btn btn-info"><i class="fa fa-file"></i> Invoice</a></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
											<h5>Total Bill: <?= $currency.$total_bill ?></h5>
											<h5>Total Scholarsihp: <?= $currency.$total_scholarship ?></h5>
											<h5>Total Discount: <?= $currency.$total_discount ?></h5>
											<h5>Total Amount Paid: <?= $currency.($total_bill-$total_scholarship-$total_discount) ?></h5>
										<?php } else { ?>
											<div class="alert alert-info">
												This student has no bill history yet
											</div>
										<?php } ?>
										</div>
									</div>
								</div>
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
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/main.js"></script>
	<script src="assets/js/form-elements.js"></script>
	<script>
		jQuery(document).ready(function() {
			Main.init();
			FormElements.init();
		});
	</script>
</body>
</html>