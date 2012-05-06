<?php

require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);
$myarr = array();
//
$sql = "SELECT `id`,team_name from bhleague.teams ";
$result = $db->query($sql);
while($row = $result->fetch_array()){

$myarr[$row['id']] = $row['team_name'];

}
//print_r($_REQUEST); exit;

$sql = "SELECT `id`,player_fname,player_lname,team_id,team_id2,team_id3,email,height,weight,position_id FROM bhleague.players "; 

if(isset($query) && $query!='')
{
	$sql .=" WHERE player_fname LIKE '%".$query."%' OR `player_lname` LIKE '%".$query."%' OR `team_id` LIKE '%".$query."%' OR `email` LIKE '%".$query."%' OR `height` LIKE '%".$query."%' OR `weight` LIKE '%".$query."%' ORDER by team_id,player_fname,player_lname  ";
}
else
{
	$sql .=" ORDER by team_id,player_fname,player_lname ";
}

//echo $sql;exit;	
$arr = array();

if (!$rs = $db->query($sql)) {

	echo '{success:false}';

}else{
	
	$record_count = $rs->num_rows;
	while($obj = $rs->fetch_array()){
		//$arr[] = $obj;
		$obj['team_id'] = @$myarr[$obj['team_id']];
		array_push($arr,$obj);
	}

	echo '{results:'.$record_count.',rows:'.json_encode($arr).'}';

}

?>
