<?php
//require('../../config/db.cnf.php');
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);

$myplayer = array();

$sql = "SELECT `id`,team_id  from bhleague.players ";
$resultz = $db->query($sql);
while($rowz = $resultz->fetch_array()){

$myplayer[$rowz['id']] = $rowz['team_id'];

}


switch ($action){
	case 'update':
		//$sched_end = date('Y-m-d H:i:s', strtotime($_REQUEST['date_schedule'].' '.$_REQUEST['schedule'] . " +9 hours"));
		//$_schedule = $_REQUEST['date_schedule'].' '.$_REQUEST['schedule']." - ".$sched_end;
		if(trim(@$_REQUEST['field'])=='player_id')
		{
			$sql = "UPDATE bhleague.players_stats SET ".@$_REQUEST['field']." = '".@$_REQUEST['value']."',team_id = '".@$myplayer[@$_REQUEST['value']]."' WHERE id = ".@$_REQUEST['id'];
		}
		else
		{
			$sql = "UPDATE bhleague.players_stats SET ".@$_REQUEST['field']." = '".@$_REQUEST['value']."' WHERE id = ".@$_REQUEST['id'];
		}
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
			$sql ="SELECT * from `bhleague`.`players_stats` WHERE game_date='".@$game_date."' AND player_id='".@$player_id."' AND team_id ='".@$team_id."'";
			$rec = $db->query($sql);
			if($rec->num_rows>0)
			{
				echo '{duplicate:true}';
			}
			else if($rec->num_rows==0)
			{
				$sql = "INSERT INTO bhleague.players_stats (game_date,player_id,team_id,game_points_1,game_attempts_1,game_points_2,game_attempts_2,game_points_3,game_attempts_3,game_assists,game_rebounds,season) VALUES 
				('".@$game_date."','".@$player_id."','".@$team_id."','".@$game_points_1."','".@$game_attempts_1."','".@$game_points_2."','".@$game_attempts_2."','".@$game_points_3."','".@$game_attempts_3."','".@$game_assists."','".@$game_rebounds."','".@$season."')";
				//echo $sql; exit;
				
				$rs = $db->query($sql);
				
				if($rs)	
				{
					echo '{success:true}';
				}
				else
				{
					echo '{success:false}';
				}
			}
		//}
//		else
//		{
//			echo "DUPLICATE DATE";
//		}
		//echo $sql;exit;
	break;
	case 'delete':
		$sql = "DELETE FROM bhleague.players_stats WHERE id = ".$_REQUEST['id'];
		if (!$rs = $db->query($sql)) 
		{
		
			echo '{success:false}';
		
		}
		else
		{
			echo '{success:true}';
		}
	break;
	
	case 'getteamid':
	
	//echo $player_id;exit;
	echo $myplayer[$player_id];
	
	break;
	
	
	
}


	


?>