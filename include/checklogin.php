<?php
function check_login(){
	if(strlen($_COOKIE['alogin'])==0){
		$host = $_SERVER['HTTP_HOST'];
		header("Location: index.php");
	}
}
?>