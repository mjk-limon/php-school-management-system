<?php
	$currency = 'Tk.';
	define('DB_SERVER','localhost');
	define('DB_USER','root');
	define('DB_PASS' ,'adminlimon');
	define('DB_NAME', 'school_management');
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	if($conn->connect_error) die("Server Error !");
?>