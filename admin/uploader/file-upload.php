<?php
//require('../../config/db.cnf.php');
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
include('/home/bhleague/public_html/class/class.upload.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);





	
	
	$dir_dest = "/home/bhleague/public_html/images/prettyPhoto/uploads/";
	
	$title = addslashes($_REQUEST['title']);
	$name = basename( $_FILES['photo-path']['name']);
	
	//$username = $_COOKIE["username"];

	$iconcon = '';
	$returnValue = array();
	$returnValue['filecount'] = count($_FILES);	
	$newiconpath = $iconcon = $img_width = $img_height = "";
	$returnValue['error'] = 0;
	$returnValue['status'] = 'ok';	
	if($returnValue['filecount'] > 0){	
		$handle = new Upload($_FILES['photo-path']);
		if ($handle->uploaded) {
			$handle->Process($dir_dest);
			if ($handle->processed) {
				//$info = getimagesize($handle->file_dst_pathname);
				$returnValue['newname'] = $handle->file_dst_name;
				$newiconpath = $handle->file_dst_name;
				$returnValue['mime'] = $info['mime'];
				//$returnValue['dimension'] = "$info[0] x $info[1]";
				//$img_width = $info[0] ; $img_height = $info[1];				
				$returnValue['size'] = round(filesize($handle->file_dst_pathname)/256)/4 . 'KB';			
				$returnValue['error'] = 0;
				$returnValue['status'] = 'ok';
				$iconcon = " ,file_path = '$newiconpath' ";			
			} else {
				$returnValue['error'] = 1;
				$returnValue['status'] = $handle->error;						
			}
			$handle-> Clean();
		}	
	}	
	$insertid = 0;	
	$dir_dest = "images/prettyPhoto/uploads/";
	$url = $dir_dest.$newiconpath;
	$sqli = "insert into `bhleague`.photos 
		set `title` = '$title' , name = '$newiconpath', url ='$url' ";
	$result = $db->query($sqli) or die("\n".__LINE__.$db->error.$sqli); 
	$insertid = $db->insert_id;
	if($returnValue['status'] == 'ok')
	{
	 	echo "success";
	}
	else
	{
		echo "error";
	}
	//$returnValue['insertid'] = $insertid;
//	echo json_encode($returnValue);		



	


?>