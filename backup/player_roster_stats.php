<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

$d = array();

$sql = "select concat(b.player_fname, ' ', b.player_lname) as player_name, 
	b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
	sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
	sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
	sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b 
group by b.id 
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

$db->close();
$db = NULL;