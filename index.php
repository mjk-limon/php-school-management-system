<?php
	session_start();
	include("include/config.php");
	include("include/basicdb.php");
	if(isset($_COOKIE['alogin'])) header('Location: dashboard.php');
	if(isset($_POST['submit'])) {
		$username = $conn->real_escape_string($_POST['username']);
		$password = $conn->real_escape_string(md5($_POST['password']));
		
		$userinfo = get_single_data("admin_logins", "username = '".$username."' AND password = '".$password."'");
		if(!empty($userinfo['token'])){
			setcookie("alogin", $userinfo['token'], time() + (86400 * 30), "/");
			header("location: dashboard.php");
			exit();
		} else {
			$_SESSION['errmsg'] = "Invalid username or password";
			header("location: index.php");
			exit();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>School Management System</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700%7CPT+Serif:400,700,400italic' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/login.css" type="text/css">
</head>
<body>
   <div class="menu">
		<div class="leftmenu">
			<img src="img/2.jpg">
		</div>
		<!--div class="rightmenu">
			<ul>
			  <li id="fisrtlist"><a href="../home.php"> HOME </a></li>
			  <li><a href="../doctor/index.php"> Doctor </a></li>
			  <li><a href="../receptionist/rlogin.php"> Receptionist</a></li>
			  <li><a href="../patient/plogin.php"> patient</a></li>
			  <li><a href="../pharmacist/perlogin.php"> pharmacist </a></li>
			  <li><a href="../admin/index.php">Admin</a></li>
			</ul>
		</div-->
   </div>
    <div class="login-page">
      <div class="form">
			<span style="color:red;"><?= (isset($_SESSION['errmsg'])) ? htmlentities($_SESSION['errmsg']) : "" ?><?php $_SESSION['errmsg']="";?></span>
			<form class="login-form" method="post">
				<h2>Admin Login</h2>
				<input type="text" class="form-control" name="username" placeholder="user name"/>
				<input type="password" class="form-control password" name="password" placeholder="password"/>
				<button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
			</form>
      </div>
    </div>
  </body>
</html>
