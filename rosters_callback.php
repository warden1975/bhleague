<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

$action = NULL;
extract($_REQUEST);
$d = array();

switch ($action) {
	case 'top20':
		$sql = "select top.player_id, top.player_name, top.height, top.weight, top.position, top.points, top.rebounds, top.assists, top.games_played, 
		round((top.points / top.games_played), 1) as ppg  
		from (
		  select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player_name, 
			  b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
			  sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
			  sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
			  sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
			  sum(if(a.player_id=b.id, 1, 0)) as games_played
		  from bhleague.players_stats a, bhleague.players b 
		  where weekday(a.game_date) = '{$gameday}' 
		  group by b.id 
		  order by points desc, rebounds desc, assists desc
		) as top 
		order by ppg desc limit 20;";
		break;
	case 'top10':
		$sql = "select top.player_id, top.player_name, top.height, top.weight, top.`position`, top.points, top.rebounds, top.assists, top.games_played, 
		round((top.points / top.games_played), 1) as ppg  
		from (
		  select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player_name, 
			  b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
			  sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
			  sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
			  sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
			  sum(if(a.player_id=b.id, 1, 0)) as games_played
		  from bhleague.players_stats a, bhleague.players b 
		  where a.game_date in (
		  	select * from (
				select game_date from bhleague.`players_stats` where game_date <= now() and weekday(game_date) = '{$gameday}' group by game_date desc limit 2
			) as game_x
		  )
		  group by b.id 
		  order by points desc, rebounds desc, assists desc
		) as top 
		order by ppg desc limit 10;";
		break;
	default:
		$sql = "select b.id as player_id, concat(b.player_fname, ' ', b.player_lname) as player_name, 
			b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
			sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
			sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
			sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
			sum(if(a.player_id=b.id, 1, 0)) as games_played
		from bhleague.players_stats a, bhleague.players b 
		where weekday(a.game_date) = '{$gameday}' and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team_id' 
		group by b.id 
		order by points desc, rebounds desc, assists desc;";
		break;	
}

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_object()) {
			$player_id = $row->player_id;
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
			
			$d[] = "['{$player_id}', '{$player}', '{$height}', '{$weight}', '{$position}', {$ppg}, {$rpg}, {$apg}, {$games}]";
		}
	}
	$rs->close();
}

$data = implode(', ', $d);
echo "[ {$data} ]";

$db->close();
$db = NULL;