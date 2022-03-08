<?php
	function check_username_by_table($tablename, $email){
		global $conn;
		$sql="SELECT * FROM $tablename WHERE email='$email' LIMIT 1";
		$result = $con->query($sql);
		return ($result->num_rows > 0) ? true : false;
	}
	function get_field($table){
		global $conn;
		$field = array();
		$sql = "SHOW COLUMNS FROM $table";
		$result = $conn->query($sql);
		while ($row = $result->fetch_array()) {
			$field[] = $row['Field'];
		}
		return $field;
	}
	function get_min($table, $index, $extra_sql=true) {
		global $conn;
		$get = "SELECT MIN({$index}) as {$index} FROM {$table} ";
		$get.= "WHERE ".$extra_sql;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		if(empty($row[$index])) { return 0;}
		else { return $row[$index]; }
	}
	function get_max($table, $index, $min, $extra_sql=true) {
		global $conn;
		$get = "SELECT MAX({$index}) as {$index} FROM {$table} ";
		$get.= "WHERE ".$extra_sql;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		if(empty($row[$index])) return $min;
		else return $row[$index];
	}
	function get_sum_of_index($table, $index, $extra_sql=true){
		global $conn;
		$get = "SELECT SUM({$index}) AS {$index} FROM {$table} ";
		$get.= "WHERE ".$extra_sql;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		if(empty($row[$index])) { return 0;}
		else { return $row[$index]; }
	}
	function get_total_rows($table, $extra_sql=true, $needed_index='*'){
		global $conn;
		$get = "SELECT ".$needed_index." FROM {$table} ";
		$get.= "WHERE ".$extra_sql;
		$result = $conn->query($get); $num = $result->num_rows;
		return $num;
	}
	function get_all($tablename, $extra_sql=true, $needed_index='*') {
		global $conn;
		$get = "SELECT ".$needed_index." FROM {$tablename} ";
		$get .= $extra_sql;
		$result = $conn->query($get) or trigger_error($get);
		return $result;
	}
	function get_some_data($tablename, $condition, $needed_index='*') {
		global $conn;
		$get = "SELECT ".$needed_index." FROM ".$tablename." ";
		$get.= "WHERE ".$condition;
		$result = $conn->query($get) or trigger_error($get);
		return $result;
	}
	function get_single_data($tablename, $condition, $needed_index='*') {
		global $conn;
		$get = "SELECT ".$needed_index." FROM ".$tablename." ";
		$get.= "WHERE ".$condition;
		$result = $conn->query($get) or trigger_error($get);
		$row = $result->fetch_array();
		return $row;
	}
	function get_single_index_data($tablename, $condition, $index) {
		global $conn;
		$get = "SELECT ".$index." FROM ".$tablename." ";
		$get.= "WHERE ".$condition;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		return $row[$index];
	}
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime; $ago = new DateTime($datetime); $diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7); $diff->d -= $diff->w * 7;
		$string = array(
			'y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 
			'h' => 'hour', 'i' => 'minute', 's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if($diff->$k){$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');}
			else{unset($string[$k]);}
		}
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	function random_token($length = 16) {
		$alpha = "abcdefghijklmnopqrstuvwxyz"; $alpha_upper = strtoupper($alpha);
		$numeric = "0123456789"; $special = ".-+=_,!@$#*%<>[]{}"; $chars = "";
		$chars = $alpha . $alpha_upper . $numeric;
		$len = strlen($chars); $pw = '';
		for($i=0; $i<$length; $i++) $pw .= substr($chars, rand(0, $len-1), 1);
		return str_shuffle($pw);
	}
	function upload_image($imageName, $imageArray, $outputFolder){
		$target_path = "../".basename($_FILES[$imageName]['name'][$imageArray]);
		$imageFileType = strtolower(pathinfo($target_path,PATHINFO_EXTENSION));
		$image_temp_name = (!empty($_FILES[$imageName]['tmp_name'][$imageArray])) ? $_FILES[$imageName]['tmp_name'][$imageArray] : "../index.php";
		if(!getimagesize($image_temp_name)) $error_message = "Uploaded file is not a image or Too large file !";
		if($_FILES[$imageName]["size"][$imageArray] > 2000000) $error_message = $_FILES[$imageName]["size"][$imageArray]. " Uploaded file must be less than 2M";
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") $error_message = $imageFileType." Incorrect Image Format";
		
		if(isset($error_message) && strlen($error_message) > 0) {
			adminMessage('red', $error_message); return false;
		} else {
			if(move_uploaded_file($_FILES[$imageName]['tmp_name'][$imageArray], $target_path)) {		
				if(!file_exists($outputFolder)) mkdir($outputFolder, 0777, true);
				$file = basename($_FILES[$imageName]['name'][$imageArray]);
				rename($target_path, $outputFolder.$file); return $outputFolder.$file;
			} else {adminMessage("red", "Error uploading file !"); return false;}
		}
	}
	function upload_image_noArray($imageName, $outputFolder){
		$target_path = "../".basename($_FILES[$imageName]['name']);
		$image_temp_name = (!empty($_FILES[$imageName]['tmp_name'])) ? $_FILES[$imageName]['tmp_name'] : "../index.php";
		
		if(!getimagesize($image_temp_name)) $error_message = "Uploaded file is not a image or Too large file !";
		if(getimagesize($_FILES[$imageName]["tmp_name"]) == false) $error_message = "Uploaded file is not a image";
		if($_FILES[$imageName]["size"] > 2000000) $error_message = "Uploaded file must be less than 2M";
		
		if(isset($error_message) && strlen($error_message) > 0) {
			adminMessage('red', $error_message); return false;
		} else {
			if(move_uploaded_file($_FILES[$imageName]['tmp_name'], $target_path)) {
				if(!file_exists($outputFolder)) mkdir($outputFolder, 0777, true);
				$file = basename($_FILES[$imageName]['name']);
				rename($target_path , $outputFolder.$file);
				return $file;
			} else {adminMessage('red', 'Error Uploading File ');return false;}
		}
	}
	function resize_image($newWidth, $newHeight, $targetFile, $originalFile, $delOrg=true, $forcType=false) {
		$info = getimagesize($originalFile); $mime = $info['mime'];
		switch ($mime) {
			case 'image/jpeg':
				$image_create_func='imagecreatefromjpeg';
				$image_save_func = 'imagejpeg';
				$new_image_ext = 'jpg';
				break;
			case 'image/png':
				$image_create_func='imagecreatefrompng';
				$image_save_func = 'imagepng';
				$new_image_ext = 'png';
				break;
			case 'image/gif':
				$image_create_func='imagecreatefromgif';
				$image_save_func = 'imagegif';
				$new_image_ext = 'gif';
				break;
			default: throw new Exception('Unknown image type.');
		}
		if($forcType) {
			$new_image_ext = $forcType;
			switch($forcType) {
				case 'jpg': $image_save_func = 'imagejpeg'; break;
				case 'png': $image_save_func = 'imagepng'; break;
				case 'gif': $image_save_func = 'imagegif'; break;
				default: throw new Exception('Unknown image type.');
			}
		}
		$img = $image_create_func($originalFile);
		list($width, $height) = getimagesize($originalFile);

		$newHeight = (empty($newHeight)) ? (($height/$width)*$newWidth) : $newHeight;
		$tmp = imagecreatetruecolor($newWidth, $newHeight);
		imagesavealpha($tmp, true);
		$color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
		imagefill($tmp, 0, 0, $color);
		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		if($delOrg && file_exists($originalFile)) unlink($originalFile);
	
		$parts = explode("/", $targetFile); $last = array_pop($parts);
		$save_path_name = array(implode('/', $parts), $last);
		if($save_path_name[0] && !file_exists($save_path_name[0])) mkdir($save_path_name[0], 0777, true);
		
		$image_save_func($tmp, "{$targetFile}.{$new_image_ext}");
	}
	function watermark_image($target, $wtrmrk_file) {
		$watermark = imagecreatefrompng($wtrmrk_file);
		imagealphablending($watermark, false);
		imagesavealpha($watermark, true);
		$img = imagecreatefromjpeg($target);
		$img_w = imagesx($img); $img_h = imagesy($img);
		$wtrmrk_w = imagesx($watermark); $wtrmrk_h = imagesy($watermark);
		$dst_x = ($img_w / 2) - ($wtrmrk_w / 2);
		$dst_y = ($img_h / 2) - ($wtrmrk_h / 2);
		imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
		
		imagejpeg($img, $target, 100);
		imagedestroy($img);
		imagedestroy($watermark);
	}
	function get_image_information($originalFile) {
		if(file_exists($originalFile) && $originalFile != "../"){
			if($info = getimagesize($originalFile)) {
				$mime = $info['mime'];
				switch ($mime) {
					case 'image/jpeg': $image_extension = 'jpg'; break;
					case 'image/png': $image_extension = 'png'; break;
					case 'image/gif': $image_extension = 'gif'; break;
					default: throw new Exception('Unknown image type.');
				}
				list($width, $height) = getimagesize($originalFile);
				return array($width, $height, $image_extension);
			} else return array(0, 0, 'Unknown');
		} else return array(0, 0, 'Unknown');
	}
	function send_mail($email_from, $email_to, $email_subject, $messageBody) {
		if($_SERVER['HTTP_HOST'] == 'localhost') return false;
		if(!isset($messageBody) || strlen($messageBody) <= 5) {
			echo 'Message content must be greater than 5 letter...'; return false;		
		} else {
			$bad 	= array("content-type","bcc:","to:","cc:");
			$Xman = array("\r\n","\n");
			$email_message	= str_replace($bad, "", $messageBody);
			$email_message	= str_replace($Xman, "<br>", $email_message);
		}
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.= 'From: '.$email_from."\r\n";
		return mail($email_to, $email_subject, $email_message, $headers);
	}
	function InsertInTable($table,$fields){
		$sql = "INSERT INTO {$table} (".implode(" , ",array_keys($fields)).") ";
		$sql.= "VALUES('";      
		foreach($fields as $key => $value) $fields[$key] = $value;
		$sql.= implode("' , '",array_values($fields))."');";       
		return $sql;
	}
	function UpdateTable($table,$fields,$condition) {
		$sql = "UPDATE {$table} SET ";
		foreach($fields as $key => $value) $fields[$key] = " {$key} = '{$value}' ";
		$sql.= implode(" , ",array_values($fields))." WHERE ".$condition.";";  
		return $sql;
	}
	function DeleteTable($tablename, $condition) {
		$sql= "DELETE FROM {$tablename} ";
		$sql.= "WHERE {$condition}" ;
		return $sql;
	}
	function deleteDir($dir) { 
		if(!file_exists($dir)) return false;
		else {
			$files = array_diff(scandir($dir), array('.','..')); 
			foreach ($files as $file) { 
				(is_dir("{$dir}/{$file}")) ? deleteDir("{$dir}/{$file}") : unlink("{$dir}/{$file}"); 
			}
			return rmdir($dir); 
		}
	}
?>