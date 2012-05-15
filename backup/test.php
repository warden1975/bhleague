<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

$current_year = date("Y");
$previous_year = $current_year -1;

$myarr = array();

$sql = "SELECT `id`,team_name from bhleague.teams ";
$result = $db->query($sql);
while($row = $result->fetch_array()){

$myarr[$row['id']] = $row['team_name'];

}

if($action=='get_team_stats_current')
{
	if(!isset($team_id) || $team_id=='')
	{
		$team_id=13;
	}
	$sql = "SELECT `game_date`,`game_time`,team1,team2,CONCAT(team1_score,' - ',team2_score) as score FROM bhleague.`schedule` WHERE (team1 = $team_id or team2 = $team_id) and YEAR(game_date) = $current_year  ORDER by `game_date`,`game_time` ";
	
	$arr = array();
	
	if (!$rs = $db->query($sql)) {
	
		echo '{success:false}';
	
	}else{
		
		$record_count = $rs->num_rows;
		while($obj = $rs->fetch_array()){
			@$obj['game_date'] = date('D M d, Y', strtotime(@$obj['game_date']));
			@$obj['team1'] = $myarr[$obj['team1']];
			@$obj['team2'] = $myarr[$obj['team2']];
			array_push($arr,$obj);
		}
	
		echo '{results:'.$record_count.',rows:'.json_encode($arr).'}';
	
	}
}
if($action=='get_team_stats_previous')
{
	if(!isset($team_id) || $team_id=='')
	{
		$team_id=13;
	}
	$sql = "SELECT `game_date`,`game_time`,team1,team2,CONCAT(team1_score,' - ',team2_score) as score FROM bhleague.`schedule` WHERE (team1 = $team_id or team2 = $team_id) and YEAR(game_date) = $previous_year  ORDER by `game_date`,`game_time` ";
	
	$arr = array();
	
	if (!$rs = $db->query($sql)) {
	
		echo '{success:false}';
	
	}else{
		
		$record_count = $rs->num_rows;
		while($obj = $rs->fetch_array()){
			@$obj['game_date'] = date('D M d, Y', strtotime(@$obj['game_date']));
			@$obj['team1'] = $myarr[$obj['team1']];
			@$obj['team2'] = $myarr[$obj['team2']];
			array_push($arr,$obj);
		}
	
		echo '{results:'.$record_count.',rows:'.json_encode($arr).'}';
	
	}
}
else if($action=='get_team_roster_current')
{
	if(!isset($team_id) || $team_id=='')
	{
		$team_id=13;
	}
	$d = array();

$sql = "select distinct b.team_id,concat(b.player_fname, ' ', b.player_lname) as player_name, 
	b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
	sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
	sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
	sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b where b.team_id =$team_id and YEAR(a.game_date) =$current_year
group by b.team_id,b.id 
order by points desc, rebounds desc, assists desc;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_object()) {
			$player = $row->player_name;
			$height = htmlentities($row->height, ENT_QUOTES);
			$weight = @$row->weight;
			$position = @$row->position;
			$points = $row->points;
			$rebounds = $row->rebounds;
			$assists = $row->assists;
			$games = $row->games_played;
			$ppg = number_format(@round($points / $games, 2), 1);
			$rpg = number_format(@round($rebounds / $games, 2), 1);
			$apg = number_format(@round($assists / $games, 2), 1);
			
			/*$d[] = "['{$player}', {$points}, {$rebounds}, {$assists}, {$games}, {$ppg}, {$rpg}, {$apg}]";*/
			$d[] = "['{$player}', '{$height}', '{$weight}', '{$position}', {$ppg}, {$rpg}, {$apg}, {$games}]";
		}
	}
	$rs->close();
}

$data = implode(', ', $d);
echo "[ {$data} ]";
}
else if($action=='get_team_roster_previous')
{
	if(!isset($team_id) || $team_id=='')
	{
		$team_id=13;
	}
	$d = array();

$sql = "select distinct b.team_id,concat(b.player_fname, ' ', b.player_lname) as player_name, 
	b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
	sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
	sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
	sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b where b.team_id =$team_id and YEAR(a.game_date) =$previous_year
group by b.team_id,b.id 
order by points desc, rebounds desc, assists desc;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_object()) {
			$player = $row->player_name;
			$height = htmlentities($row->height, ENT_QUOTES);
			$weight = @$row->weight;
			$position = @$row->position;
			$points = $row->points;
			$rebounds = $row->rebounds;
			$assists = $row->assists;
			$games = $row->games_played;
			$ppg = number_format(@round($points / $games, 2), 1);
			$rpg = number_format(@round($rebounds / $games, 2), 1);
			$apg = number_format(@round($assists / $games, 2), 1);
			
			/*$d[] = "['{$player}', {$points}, {$rebounds}, {$assists}, {$games}, {$ppg}, {$rpg}, {$apg}]";*/
			$d[] = "['{$player}', '{$height}', '{$weight}', '{$position}', {$ppg}, {$rpg}, {$apg}, {$games}]";
		}
	}
	$rs->close();
}

$data = implode(', ', $d);
echo "[ {$data} ]";
}
else if($action=='getAllTeam')
{
	$sql ="SELECT `id`,team_name from bhleague.teams ";
  	
	//$conn = OpenDbConnection();   
	
	$result = $db->query($sql);
	
	$num = $result->num_rows;
	
	$i = 0;   
	
	$teamData = array("count" => $num, "teams" => array());
	
	while ($row = $result->fetch_assoc()) {    
		$teamData["teams"][$i] = $row;    
		$i++;  
	}   
	
	//CloseDbConnection($conn); 
	
	echo json_encode($teamData); 
}
else if($action=='getteamname')
{
	echo @$myarr[@$team_id];
}
else if($action=='getteam_leader')
{
	$sql ="SELECT  count(winner_score) as wins,game_winner FROM bhleague.games_stats GROUP BY game_winner ORDER BY count(winner_score) desc LIMIT 1";
	
	
	$result = $db->query($sql);
	if($result)
	{
	$row = $result->fetch_assoc();
	
	echo $row['game_winner'];
	}
	else
	{
	 echo $db->error;
	}
}

$db->close();
$db = NULL;