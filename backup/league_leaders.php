<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

extract($_REQUEST);
$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

$arr = array();
$arr1 = array();
$arr2 = array();

$myarr = array();
//
$sql = "SELECT `id`,CONCAT(player_fname,' ',player_lname) as player from bhleague.players ";
$result = $db->query($sql);
while($row = $result->fetch_array()){

$myarr[$row['id']] = $row['player'];

}

if(@$action=='get_player_points')
{
  //$sql = "SELECT CONCAT(player_fname,' ',player_lname) as player, AVG(game_points_1 + game_points_2 + game_points_3) as points FROM `bhleague`.`players_stats` a INNER JOIN `bhleague`.`players` b  WHERE a.player_id <> 0 GROUP by player_id order by points DESC, a.player_id";
 // $sql ="SELECT a.player_id, CONCAT(player_fname,' ',player_lname) as player, AVG(game_points_1 + game_points_2 + game_points_3) as points FROM `bhleague`.`players_stats` a INNER JOIN `bhleague`.`players` b ON a.player_id = b.id  WHERE a.player_id <> 0 AND WEEKDAY(a.game_date) = '{$gameday}' GROUP by player_id order by points DESC, player";
  switch ($gameday)
  {
  	case 1:
	  $sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as ppg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '1' and b.team_id <> 0  
	group by b.id 
	order by score desc) as ave 
	order by ppg desc";
	break;
	case 5:
	$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as ppg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '5' and b.team_id2 <> 0  
	group by b.id 
	order by score desc) as ave 
	order by ppg desc";
	break;
	case 6:
	$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as ppg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '6' and b.team_id3 <> 0  
	group by b.id 
	order by score desc) as ave 
	order by ppg desc";
	break;
	
}

	if ($rs = $db->query($sql)) 
	{
		$record_count = $rs->num_rows;
		if ($record_count > 0) {
			
		while($obj = $rs->fetch_assoc())
		{
			//$arr[] = $obj;
			//@$obj['player_id'] = $myarr[$obj['player_id']];
			//@$obj['points'] = number_format(@$obj['points'],2);
			//@$obj['month'] = date( 'F', mktime(0, 0, 0, $obj['month']) );
			//@$obj['date'] = numberToPlace($obj['date']);
			@$obj['ppg'] = (is_null($obj['ppg']) ? '0.00' : $obj['ppg']);
			array_push($arr,$obj);
		}
	
		echo '{"results":'.$record_count.',"rows":'.json_encode($arr).'}';
		}
	}
}
if(@$action=='get_player_rebounds')
{
  //$sql = "SELECT player_id, AVG(game_rebounds) as rebounds FROM `bhleague`.`players_stats` WHERE player_id <> 0 GROUP by player_id order by rebounds DESC, player_id";
  //$sql ="SELECT a.player_id, CONCAT(player_fname,' ',player_lname) as player, AVG(game_rebounds) as rebounds FROM `bhleague`.`players_stats` a INNER JOIN `bhleague`.`players` b ON a.player_id = b.`id` WHERE a.player_id <> 0 AND WEEKDAY(a.game_date) = '{$gameday}' GROUP by player_id order by rebounds DESC, player";
   switch ($gameday)
   {
   	case 1:
		$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as rpg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, game_rebounds, 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '1' and b.team_id <> 0   
	group by b.id 
	order by score desc) as ave 
	order by rpg desc";
	break;
	case 5:
		$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as rpg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, game_rebounds, 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '5' and b.team_id2 <> 0   
	group by b.id 
	order by score desc) as ave 
	order by rpg desc";
	break;
	case 6:
		$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as rpg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, game_rebounds, 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '6' and b.team_id3 <> 0   
	group by b.id 
	order by score desc) as ave 
	order by rpg desc";
	break;
   }

	if ($rs1 = $db->query($sql)) 
	{
		$record_count1 = $rs1->num_rows;
		if ($record_count1 > 0) {
			
		while($obj1 = $rs1->fetch_assoc())
		{
			//$arr[] = $obj;
			//@$obj1['player_id'] = $myarr[$obj1['player_id']];
			//@$obj1['rebounds'] = number_format(@$obj1['rebounds'],2);
			//@$obj['month'] = date( 'F', mktime(0, 0, 0, $obj['month']) );
			//@$obj['date'] = numberToPlace($obj['date']);
			@$obj1['rpg'] = (is_null($obj1['rpg']) ? '0.00' : $obj1['rpg']);
			array_push($arr1,$obj1);
		}
	
		echo '{"results1":'.$record_count1.',"rows1":'.json_encode($arr1).'}';
		}
	}
}
if(@$action=='get_player_assists')
{
  //$sql = "SELECT player_id, AVG(game_assists) as assists FROM `bhleague`.`players_stats` WHERE player_id <> 0 GROUP by player_id order by assists DESC, player_id";
 // $sql = "SELECT a.player_id, CONCAT(player_fname,' ',player_lname) as player, AVG(game_assists) as assists FROM `bhleague`.`players_stats` a INNER JOIN `bhleague`.`players` b ON a.player_id = b.`id` WHERE player_id <> 0 AND WEEKDAY(a.game_date) = '{$gameday}' GROUP by player_id order by assists DESC, player";
 	switch ($gameday)
   {
   	case 1:
		$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as apg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, game_assists, 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '1' and b.team_id <> 0  
	group by b.id 
	order by score desc) as ave 
	order by apg desc";
	break;
	case 5:
		$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as apg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, game_assists, 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '5' and b.team_id2 <> 0  
	group by b.id 
	order by score desc) as ave 
	order by apg desc";
	break;
	
	case 6:
		$sql = "select ave.player_id, ave.player, round((ave.score / ave.games_played), 2) as apg from (
	select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player, 
		sum(if(a.player_id=b.id, game_assists, 0)) as score, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b 
	where weekday(a.game_date) = '6' and b.team_id3 <> 0  
	group by b.id 
	order by score desc) as ave 
	order by apg desc";
	break;
	
	}

	if ($rs2 = $db->query($sql)) 
	{
		$record_count2 = $rs2->num_rows;
		if ($record_count2 > 0) {
			
		while($obj2 = $rs2->fetch_assoc())
		{
			//$arr[] = $obj;
			//@$obj2['player_id'] = $myarr[$obj2['player_id']];
			//@$obj2['assists'] = number_format(@$obj2['assists'],2);
			//@$obj['month'] = date( 'F', mktime(0, 0, 0, $obj['month']) );
			//@$obj['date'] = numberToPlace($obj['date']);
			@$obj2['apg'] = (is_null($obj2['apg']) ? '0.00' : $obj2['apg']);
			array_push($arr2,$obj2);
		}
	
		echo '{"results2":'.$record_count2.',"rows2":'.json_encode($arr2).'}';
		}
	}
}


//$data = implode(', ', $d);
//echo "[ {$data} ]";

$db->close();
$db = NULL;


?>