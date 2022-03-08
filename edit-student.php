<?php
	session_start();
	include('include/config.php');
	include('include/checklogin.php');
	require_once('include/basicdb.php');
	check_login();
?>
<?php
	if(isset($_POST['update_student'])) {
		$stdid = $conn->real_escape_string($_POST['stdid']);
		if(!empty($_FILES['photograph']['name'])) {
			$file = upload_image_noArray('photograph', './');
			if($file !== false) {
				if(!is_dir("img/student-images")) mkdir("img/student-images", 0777, true);
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				$newimgname = 'img/student-images/uimg-'. date("dmY_His.") . $ext;
				rename($file, $newimgname);
				$fields['image'] = $newimgname;
			}
		}
		$fields['full_name'] = $conn->real_escape_string($_POST['full_name']);
		$fields['fathers_name'] = $conn->real_escape_string($_POST['fathers_name']);
		$fields['mothers_name'] = $conn->real_escape_string($_POST['mothers_name']);
		$fields['gender'] = $conn->real_escape_string($_POST['gender']);
		$fields['birth_date'] = $conn->real_escape_string($_POST['birth_date']);
		$fields['class_name'] = $conn->real_escape_string($_POST['class_name']);
		$fields['section_name'] = $conn->real_escape_string($_POST['section_name']);
		$fields['roll_no'] = $conn->real_escape_string($_POST['roll_no']);
		$fields['address'] = $conn->real_escape_string($_POST['address']);
		$fields['mobile_number'] = $conn->real_escape_string($_POST['mobile_number']);
		$fields['blood_group'] = $conn->real_escape_string($_POST['blood_group']);
		$fields['scholarship'] = $conn->real_escape_string($_POST['scholarship']);
		
		if($conn->query(UpdateTable("students", $fields, "id = '{$stdid}'"))) header("Location: student-info.php?stid={$stdid}");
		else header("Location: student-info.php?stid={$stdid}&emsg=".urlencode($conn->error));
	}
?>
<?php
	$stdid = isset($_GET['stdid']) ? $conn->real_escape_string($_GET['stdid']) : exit("Invaild student id !");
	$std_info = get_single_data('students', "id= '{$stdid}'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin | Edit Student</title>
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
								<h1 class="mainTitle">Admin | Edit Student</h1>
							</div>
							<ol class="breadcrumb">
								<li><span>Admin</span></li>
								<li class="active"><span>Edit Student</span></li>
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
												<h5 class="panel-title">Edit Student</h5>
											</div>
											<div class="panel-body">
												<form role="form" enctype="multipart/form-data" method="post" onSubmit="return valid();">
													<input type="hidden" name="update_student" value="1" />
													<input type="hidden" name="stdid" value="<?= $std_info['id'] ?>" />
													<div class="form-group">
														<label>Full Name</label>
														<input type="text" name="full_name" class="form-control" value="<?= $std_info['full_name'] ?>" required />
													</div>
													<div class="form-group">
														<label>Father's Name</label>
														<input type="text" name="fathers_name" class="form-control" value="<?= $std_info['fathers_name'] ?>" required />
													</div>
													<div class="form-group">
														<label>Mother's Name</label>
														<input type="text" name="mothers_name" class="form-control" value="<?= $std_info['mothers_name'] ?>" required />
													</div>
													<div class="form-group">
														<label>Gender</label>
														<select name="gender" class="form-control" autocomplete="off">
															<option value="Male"<?= ($std_info['gender']=='Male') ? ' selected': null; ?>>Male</option>
															<option value="Female"<?= ($std_info['gender']=='Female') ? ' selected': null; ?>>Female</option>
														</select>
													</div>
													<div class="form-group">
														<label>Birthdate</label>
														<input type="date" name="birth_date" class="form-control" value="<?= $std_info['birth_date'] ?>" required />
													</div>
													<div class="row">
														<div class="col-md-4 col-lg-4">
															<div class="form-group">
																<label>Class</label>
																<select name="class_name" class="form-control" autocomplete="off">
																	<option value="1"<?= ($std_info['class_name']==1) ? ' selected': null; ?>>1</option>
																	<option value="2"<?= ($std_info['class_name']==2) ? ' selected': null; ?>>2</option>
																	<option value="3"<?= ($std_info['class_name']==3) ? ' selected': null; ?>>3</option>
																	<option value="4"<?= ($std_info['class_name']==4) ? ' selected': null; ?>>4</option>
																	<option value="5"<?= ($std_info['class_name']==5) ? ' selected': null; ?>>5</option>
																	<option value="6"<?= ($std_info['class_name']==6) ? ' selected': null; ?>>6</option>
																</select>
															</div>
														</div>
														<div class="col-md-4 col-lg-4">
															<div class="form-group">
																<label>Section</label>
																<select name="section_name" class="form-control" autocomplete="off">
																	<option value="A"<?= ($std_info['section_name']=='A') ? ' selected': null; ?>>A</option>
																	<option value="B"<?= ($std_info['section_name']=='B') ? ' selected': null; ?>>B</option>
																	<option value="C"<?= ($std_info['section_name']=='C') ? ' selected': null; ?>>C</option>
																	<option value="D"<?= ($std_info['section_name']=='D') ? ' selected': null; ?>>D</option>
																</select>
															</div>
														</div>
														<div class="col-md-4 col-lg-4">
															<div class="form-group">
																<label>Roll No</label>
																<input type="text" name="roll_no" class="form-control" value="<?= $std_info['roll_no'] ?>" required />
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="address">Address</label>
														<textarea name="address" class="form-control" required><?= $std_info['address'] ?></textarea>
													</div>
													<div class="form-group">
														<label for="phone">Contact Number</label>
														<input type="text" name="mobile_number" class="form-control" value="<?= $std_info['mobile_number'] ?>" required />
													</div>
													<div class="form-group">
														<label>Blood Group</label>
														<select name="blood_group" class="form-control" autocomplete="off">
															<option value="Unknown"<?= ($std_info['blood_group']=='Unknown') ? ' selected': null; ?>>Unknown</option>
															<option value="A+"<?= ($std_info['blood_group']=='A+') ? ' selected': null; ?>>A+</option>
															<option value="A-"<?= ($std_info['blood_group']=='A-') ? ' selected': null; ?>>A-</option>
															<option value="B+"<?= ($std_info['blood_group']=='B+') ? ' selected': null; ?>>B+</option>
															<option value="B-"<?= ($std_info['blood_group']=='B-') ? ' selected': null; ?>>B-</option>
															<option value="O+"<?= ($std_info['blood_group']=='O+') ? ' selected': null; ?>>O+</option>
															<option value="O-"<?= ($std_info['blood_group']=='O-') ? ' selected': null; ?>>O-</option>
															<option value="AB+"<?= ($std_info['blood_group']=='AB+') ? ' selected': null; ?>>AB+</option>
															<option value="AB-"<?= ($std_info['blood_group']=='AB-') ? ' selected': null; ?>>AB-</option>
														</select>
													</div>
													<div class="form-group">
														<label>Scholarship</label>
														<div class="input-group">
															<input type="number" name="scholarship" class="form-control" value="<?= $std_info['scholarship'] ?>" required />
															<span class="input-group-addon bg-muted">%</span>
														</div>
													</div>
													<div class="form-group">
														<label>Student Photograph</label>
														<input type="file" name="photograph"/>
													</div>
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
	<script>
		jQuery(document).ready(function() {
			Main.init();
			FormElements.init();
		});
	</script>
</body>
</html>
