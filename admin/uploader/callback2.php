<?php
error_reporting(E_ERROR);

require_once($_SERVER['DOCUMENT_ROOT'].'/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);

if($action == 'getupload') {	

	$arr = array();
	$sql = "SELECT `id`, remarks, file_path, upload_date, upload_by FROM `intranet_pocketlead`.`insertion_orders` order by id desc";	
	$result = $db->query($sql);
	$tcount = $result->num_rows; 
	while ($row = $result->fetch_assoc()) {
		//$row['enabled'] = ($row['enabled']==1) ? 'yes' : 'no';
		//$row['datetime'] =htmlspecialchars($row['password']);
		
		$row['datetime'] = $row['datetime'];
		array_push($arr, $row);
	}
	$datatable = json_encode($arr);
	
	$data = '{"ResultSet":{"datatable":'.$datatable.'}}';
	echo $data;
}else if($action == 'downloadme'){
	
	$sql = "SELECT file_path FROM `intranet_pocketlead`.`insertion_orders` where id = '".$_REQUEST['f']."' ";	
	$result = $db->query($sql);	
	$row = $result->fetch_assoc();		
	$file_path = $row['file_path'];
#	header("Content-type: application/vnd.ms-excel");
#	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
#	header("Content-disposition: filename=".$filename.".csv");	
	
	$fName = basename("./uploads/".$file_path);
	echo $fName."<hr>";
	echo $file_path;
	
	/*header("Content-type:application/octet-stream");
	header("Content-disposition: filename=".$file_path.".csv");
	header("Content-disposition:attachment;");
	$fName = basename("./uploads/".$file_path);
	fpassthru($fName);	*/
			
}else if($action == 'saveupload'){
	
	include('/home/pocketle/public_html/intranet/class/class.upload.php');
	$dir_dest = "/home/pocketle/public_html/intranet/include/insertion_orders/uploads";
	
	$txt_remarks = addslashes($_REQUEST['txt_remarks']);
	$username = $_COOKIE["username"];

	$iconcon = '';
	$returnValue = array();
	$returnValue['filecount'] = count($_FILES);	
	$newiconpath = $iconcon = $img_width = $img_height = "";
	$returnValue['error'] = 0;
	$returnValue['status'] = 'ok';	
	if($returnValue['filecount'] > 0){	
		$handle = new Upload($_FILES['txt_file']);
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
	
	$sqli = "insert into intranet_pocketlead.insertion_orders 
		set `remarks` = '$txt_remarks' , upload_by = '$username' $iconcon ";
	$result = $db->query($sqli) or die("\n".__LINE__.$db->error.$sqli); 
	$insertid = $db->insert_id;
	$returnValue['insertid'] = $insertid;
	echo json_encode($returnValue);		
}
?>