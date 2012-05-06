<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

$d = array();

$sql = "select b.team_name as team, 
	sum(if(a.game_winner=b.id,1,0)) as wins, 
	sum(if(a.game_loser=b.id,1,0)) as losses, 
	(sum(if(a.game_winner=b.id,a.winner_score,0)) + sum(if(a.game_loser=b.id,a.loser_score,0))) as pts_for,
	(sum(if(a.game_winner=b.id,a.loser_score,0)) + sum(if(a.game_loser=b.id,a.winner_score,0))) as pts_against 
from bhleague.games_stats a, bhleague.teams b 
where weekday(game_date) = '{$gameday}' 
group by b.id 
order by wins desc, losses asc;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		$gbw = -1;
		$gbl = -1;
		while ($row = $rs->fetch_object()) {
			if ($gbw == -1) $gbw = $row->wins;
			if ($gbl == -1) $gbl = $row->losses;
			$team = $row->team;
			$wins = $row->wins;
			$losses = $row->losses;
			$win_pct = @round($wins / ($wins + $losses), 2) * 100;
			$score = ($wins - $losses);
			$pt_for = $row->pts_for;
			$pt_against = $row->pts_against;
			$pt_diff = ($pt_for - $pt_against);
			$gb = (abs($gbw - $wins) / 2) + (abs($gbl - $losses) / 2);
			
			$d[] = "['{$team}', {$wins}, {$losses}, {$win_pct}, {$pt_diff}, {$gb}]";
		}
	}
}

$data = implode(', ', $d);
echo "[ {$data} ]";

$db->close();
$db = NULL;