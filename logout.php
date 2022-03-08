<?php
	session_start();
	include('include/config.php');
	setcookie("alogin", null, time() - 100, "/");
	session_unset();
	header("Location: index.php");
?>