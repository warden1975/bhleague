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

$season_arr = array();

$sql = "SELECT `id`,name from bhleague.seasons ";
$resultz = $db->query($sql);
while($rowz = $resultz->fetch_array()){

$season_arr[$rowz['id']] = $rowz['name'];

}

//print_r($_REQUEST); exit;


$sql = "SELECT `id`,`game_date`,`game_time`,team1,team2,team1_score,team2_score,season,bye_week FROM bhleague.schedule "; 

if(isset($query) && $query!='')
{
	$sql .=" WHERE `game_date` LIKE '%".$query."%' OR `game_time` LIKE '%".$query."%' OR `team1` LIKE '%".$query."%' OR `team2` LIKE '%".$query."%' OR `team1_score` LIKE '%".$query."%' OR `team2_score` LIKE '%".$query."%' OR `season` LIKE '%".$query."%' OR `bye_week` LIKE '%".$query."%'  ORDER by `game_date`,`game_time` ";
}
else
{
	$sql .=" ORDER by `game_date`,`game_time`";
}

//echo $sql;exit;	
$arr = array();

if (!$rs = $db->query($sql)) {

	echo '{success:false}';

}else{
	
	$record_count = $rs->num_rows;
	while($obj = $rs->fetch_array()){
		//$arr[] = $obj;
		@$obj['team1'] = $myarr[$obj['team1']];
		@$obj['team2'] = $myarr[$obj['team2']];
		$obj['season'] = @$season_arr[$obj['season']];
		//if(@$obj['bye_week']==1)
//		{
//			@$obj['bye_week'] =checked;
//		}
//		else
//		{
//			@$obj['bye_week'] ='<div class="x-grid3-check-col"> </div>';
//		}
		array_push($arr,$obj);
	}

	echo '{results:'.$record_count.',rows:'.json_encode($arr).'}';

}

?>
