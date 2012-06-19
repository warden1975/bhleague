<?php

@$bhlOGL = stripslashes(@$_COOKIE['bhlOGL']);
$bhlOGL = json_decode($bhlOGL);
if (isset($bhlOGL) && strlen($bhlOGL->id) > 0) $gameday = $bhlOGL->id;
else $gameday = 5;

$db = NULL;

function get_month_games() {
	global $db, $gameday;
	
	$d = array();
	
	$sql = "select date_format(game_date, '%M %D') as game_date, (select team_name from bhleague.teams where id = a.game_winner) as team_winner, a.winner_score, 
		(select team_name from bhleague.teams where id = a.game_loser) as team_loser, a.loser_score 
	from bhleague.games_stats a 
	where weekday(a.game_date) = '{$gameday}' and month(a.game_date) = month(now()) 
	order by a.game_date desc;";
	
	if ($rs = $db->query($sql)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_object()) {
				$game_date = $row->game_date;
				$team_winner = $row->team_winner;
				$winner_score = $row->winner_score;
				$team_loser = $row->team_loser;
				$loser_score = $row->loser_score;
				
				$game_score = "$game_date :   $team_winner vs $team_loser   [$winner_score - $loser_score]";
				
				$d[] = "'{$game_score}'";
			}
		}
		$rs->close();
	}
	
	$data = implode(', ', $d);
	return "[ {$data} ]";
}