<?php
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);



if (isset($action) && strlen(trim($action)) > 0) {
	switch ($action) {
		case 'update':
			if(@$field=='team2' || @$field=='team1')
			{
				$check = checkTeamValue(@$id,@$field,@$value);
				if($check=="valid")
				{
					$sql = "UPDATE bhleague.schedule SET `".@$field."` = '".@$value."' WHERE id = ".@$id;
					if ($rs = $db->query($sql)) echo '{success:true}';
					else echo '{success:false}';
				}
				else
				{
					echo "{invalid pairings}";
				}
			}
			else
			{
			$sql = "UPDATE bhleague.schedule SET `".@$field."` = '".@$value."' WHERE id = ".@$id;
			if ($rs = $db->query($sql)) echo '{success:true}';
			else echo '{success:false}';
			}
			break;
		case 'insert':
			$sql = "INSERT INTO bhleague.schedule (game_date, game_time, team1, team2,team1_score,team2_score) VALUES ('".$game_date."','".$game_time."','".$team1."','".$team2."','".$team1_score."','".$team2_score."')";
			if ($rs = $db->query($sql)) echo '{success:true}';
			else echo '{success:false}';
			break;
		case 'delete':
			$sql = "DELETE FROM bhleague.schedule WHERE id = ".$id;
			if ($rs = $db->query($sql)) echo '{success:true}';
			else echo '{success:false}';
			break;
	}
	
	

} else echo '{success:false}';

$db->close();
$db = NULL;
function checkTeamValue($idx,$fieldx,$valx)
{
	global $db;
	if($fieldx=='team1')
	{
		$sqlz = "SELECT team2 from bhleague.schedule WHERE id =$idx";
		$result = $db->query($sqlz);
		$row =$result->fetch_array();
		if($row['team2']==$valx)
		{
		    return "invalid"; 
		 }
		 else
		 {
		 	return "valid"; 
		 }
		 		 
	}
	else if($fieldx=='team2')
	{
		$sqlz = "SELECT team1 from bhleague.schedule WHERE id =$idx";
		$result = $db->query($sqlz);
		$row =$result->fetch_array();
		if($row['team1']==$valx)
		{
		    return "invalid"; 
		 }
		 else
		 {
		 	return "valid"; 
		 }
	}
}
?>