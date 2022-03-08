<?php
	session_start();
	include('include/config.php');
	include('include/checklogin.php');
	require_once('include/basicdb.php');
	check_login();
?>
<?php
	$stdid = isset($_GET['stdid']) ? $conn->real_escape_string($_GET['stdid']) : null;
	if($stdid) {
		$std_info = get_single_data("students", "id = '{$stdid}'");
		$scholarship = $std_info['scholarship'];
	} else $scholarship = 0;
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
			$fields['discount'] = $conn->real_escape_string($_POST['discount'][$key]);
			if($conn->query(InsertInTable("payments", $fields))) $total_inserted += 1;
			else $error_array[] = "Error at position ". $key .". Description: " .$conn->error;
		}
		if($total_bill == $total_inserted) header("Location: bill-invoice.php?bid=". $fields['inv_id'] );
		else print_r($error_array);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin | Bill Pay</title>
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
								<h1 class="mainTitle">Admin | Bill Pay</h1>
							</div>
							<ol class="breadcrumb">
								<li><span>Admin</span></li>
								<li class="active"><span>Bill Pay</span></li>
							</ol>
						</div>
					</section>
					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<div class="row margin-top-30">
									<div class="col-lg-8 col-md-12 col-lg-offset-2">
										<div class="panel panel-white">
											<div class="panel-heading">
												<h5 class="panel-title">Bill Pay</h5>
											</div>
											<div class="panel-body">
												<form role="form" enctype="multipart/form-data" name="addpatient" method="post" onSubmit="return valid();">
													<input type="hidden" name="add_bill" value="1" />
													<div class="form-group">
														<label>Student Name</label>
														<select name="std_id" class="form-control js-example-basic-single" required>
														<?php
															$r_stds = get_some_data("students", "1");
															while($rst = $r_stds->fetch_array()) {
														?>	
															<option value="<?= $rst['id'] ?>"<?php if($rst['id'] == $stdid) echo ' selected'; ?>>
																<?= $rst['full_name'] ?> - <?= $rst['class_name'] ?>(<?= $rst['section_name'] ?>) - <?= $rst['roll_no'] ?>
															</option>
														<?php } mysqli_free_result($r_stds); ?>
														</select>
													</div>
													<table class="table table-bordered bill-table">
														<thead>
															<tr>
																<th width="40%">Bill's Title</th>
																<th>Amount</th>
																<th>Scholarship</th>
																<th>Discount</th>
															</tr>
														</thead>
														<tbody id="rep-row">
															<tr>
																<td>
																	<select name="bill_title[]" class="form-control" required>
																		<option value="Admission Fee">Admission Fee</option>
																		<option value="Registration Fee">Registration Fee</option>
																		<option value="Session Fee">Session Fee</option>
																		<option class="other_bill_opt" value="">Others</option>
																	</select>
																	<input type="hidden" name="bill_title[]" class="form-control other_bill_inp" placeholder="Enter your bill title here" disabled />
																</td>
																<td><input type="number" name="bill_amount[]" class="form-control" autocomplete="false" required /></td>
																<td>
																	<div class="input-group">
																		<input type="number" name="scholarship[]" class="form-control" value="<?= $scholarship ?>" autocomplete="false" required />
																		<span class="input-group-addon bg-muted">%</span>
																	</div>
																</td>
																<td>
																	<div class="input-group">
																		<input type="number" name="discount[]" class="form-control" autocomplete="false" required />
																		<span class="input-group-addon bg-muted">Tk</span>
																	</div>
																</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="4">
																	<a href="javascript:;" class="btn" id="rr-btn"><i class="fa fa-plus"></i> Add New Row</a>
																	<a href="javascript:;" class="text-danger btn" id="lrd-btn"><i class="fa fa-trash"></i> Remove last row</a>
																</td>
															</tr>
														</tfoot>
													</table>
													<button type="submit" class="btn btn-primary">Submit</button>
												</form>
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
			rr_row = $('#rep-row').html();
			$('#rr-btn').on('click', function(){
				$('#rep-row').append(rr_row);
				$('#lrd-btn').show();
			});
			$('#lrd-btn').on('click', function(){
				$('#rep-row tr').last().remove();
				if($('#rep-row tr').length == 1) $('#lrd-btn').hide();
			});
			$('.bill-table').on("click", '.other_bill_opt', function(){
				var $select_box = $(this).parent();
				$select_box.hide().prop("disabled", true);
				$select_box.next().attr("type", "text").prop("disabled", false);
			});
		});
	</script>
</body>
</html>
