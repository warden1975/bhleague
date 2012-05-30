<?php
//require('../../config/db.cnf.php');
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);


switch ($action){
	case 'update':
		//$sched_end = date('Y-m-d H:i:s', strtotime($_REQUEST['date_schedule'].' '.$_REQUEST['schedule'] . " +9 hours"));
		//$_schedule = $_REQUEST['date_schedule'].' '.$_REQUEST['schedule']." - ".$sched_end;
		$sql = "UPDATE bhleague.players SET ".@$_REQUEST['field']." = '".@$_REQUEST['value']."' WHERE id = ".@$_REQUEST['id'];
		//echo $sql;exit;
		if (!$rs = $db->query($sql)) 
		{
		
			echo '{success:false}';
		
		}
		else
		{
			echo '{success:true}';
		}
	break;
	case 'insert':
		//$emp_id = explode(",",$_REQUEST['employee']);
//		$error =0;
//		$sqx =" SELECT date_schedule from intranet.employee_schedule WHERE date_schedule='". $_REQUEST['date_schedule']."' and employee ='".$_REQUEST['employee']."'";
//		//echo $sqx; exit;
//		$rx = $db->query($sqx);
//		if($rx->num_rows==0)
//		{
//			$sched_end = date('Y-m-d H:i:s', strtotime($_REQUEST['date_schedule'].' '.$_REQUEST['schedule'] . " +9 hours"));
//			$_schedule = $_REQUEST['date_schedule'].' '.$_REQUEST['schedule']." - ".$sched_end;
//			for($i=0;$i<sizeof($emp_id);$i++)
//			{
			$sql = "INSERT INTO bhleague.players (player_fname,player_lname,email,height,weight,position_id,league,jersey_size,jersey_number,phone,referred_by,occupation) 
			VALUES ('".addslashes($fname)."','".addslashes($lname)."','".addslashes($email)."','".addslashes($height)."','".addslashes($weight)."','".addslashes($position)."','".addslashes($league)."','".
			addslashes($jersey_size)."','".addslashes($jersey_no)."','".addslashes($phone)."','".addslashes($referrer)."','".addslashes($occupation)."')";
			//echo $sql; exit;
			
			$rs = $db->query($sql) or die('{"status":"failed","error":"'.$db->error.'"}');
			
			if($rs)	
			{
				echo '{"status":"success","id":"'.$db->insert_id.'"}';
			}
			else
			{
				echo '{"status":"failed"}';
			}
		//}
//		else
//		{
//			echo "DUPLICATE DATE";
//		}
		//echo $sql;exit;
	break;
	case 'delete':
		$sql = "DELETE FROM bhleague.players WHERE id = ".$_REQUEST['id'];
		if (!$rs = $db->query($sql)) 
		{
		
			echo '{success:false}';
		
		}
		else
		{
			echo '{success:true}';
		}
	break;
	case 'test':
		$REQUEST = $_REQUEST;
		$REQUEST['occupation'] = urldecode($REQUEST['occupation']);
		$d = http_build_query($REQUEST);
		echo '{status:"lee :: '.$d.'"}';
		//print_r($_REQUEST);
	break;
}


	


?>