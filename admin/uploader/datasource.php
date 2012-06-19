<?php

require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);
$myarr = array();
$myplayer = array();
//
$sql = "SELECT `id`,team_name from bhleague.teams ";
$result = $db->query($sql);
while($row = $result->fetch_array()){

$myarr[$row['id']] = $row['team_name'];

}

$sql = "SELECT `id`,CONCAT(player_fname,' ',player_lname) as player_name from bhleague.players ";
$resultz = $db->query($sql);
while($rowz = $resultz->fetch_array()){

$myplayer[$rowz['id']] = $rowz['player_name'];

}



//print_r($myarr); exit;


$sql = "SELECT `id`,`name`,`title`,`url` FROM bhleague.photos ORDER by id DESC"; 

//if(isset($query) && $query!='')
//{
//	$sql .=" WHERE   game_date  LIKE '%".$query."%'
// player_id  LIKE '%".$query."%' OR  team_id  LIKE '%".$query."%' OR  game_points_1  LIKE '%".$query."%' OR  game_attempts_1  LIKE '%".$query."%' OR  game_points_2  LIKE '%".$query."%'
//OR  game_attempts_2  LIKE '%".$query."%' OR  game_points_3  LIKE '%".$query."%' OR  game_attempts_3  LIKE '%".$query."%' OR  game_assists  LIKE '%".$query."%'OR  game_rebounds  LIKE '%".$query."%'";
//}
//else
//{
//	$sql .=" ORDER by id DESC ";
//}

//echo $sql;exit;	
$arr = array();

if (!$rs = $db->query($sql)) {

	echo '{success:false}';

}else{
	
	$record_count = $rs->num_rows;
	while($obj = $rs->fetch_array()){
		$filepath = "http://www.bhleague.com/".@$obj['url'];
		@$obj['name'] = "<a href=\"$filepath\"  target=\"_blank\">".$obj['name']."</a>";
		
		array_push($arr,$obj);
	}

	echo '{results:'.$record_count.',rows:'.json_encode($arr).'}';

}

?>
