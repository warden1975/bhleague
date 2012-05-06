<?php
//session_unset();
  session_start();
  //$_SESSION['username'] = "fortune";
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);

if($action =='checkAuth')
{
	global $db;
	$sql= "select `username`,id
				from `bhleague`.`accounts`
				where decode(password, 'admin.golivemobile.com') = '$pwd' and username = '$username'";
		//($db->query($sql))? $response2 = $o->api_response_data:$response2 = $o->api_response_text;		
//		$reccount2 = $o->api_response_data_cnt;
		$rs =$db->query($sql);
		$isuser = $rs->num_rows;
		$row = $rs->fetch_array();		
		if($isuser > 0)
		{
			$_SESSION['username'] = $username;
			$_SESSION['authenticated'] = 'yes';
			setcookie("usernamez",$username, time()+3600*24);
			//$_COOKIE['usernamez'] = $username;
			$return = array("status" => "success",							
							"id" =>  $row['id'],
							"username" => $row['username']);				
		}
		else
		{
				$return = array("status" => "fail");	
		}
		echo json_encode($return);
}

?>