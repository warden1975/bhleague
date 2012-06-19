<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'class/db.cls.php');

define('NL', '\n');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

function scheduler($teams, $date, $time, $time_interval=60, $rounds=1) { 
    if (count($teams)%2 != 0) { 
        array_push($teams,"bye"); 
    } 
	
	$sets = count($teams)/2;
	for ($x=0; $x<$sets; $x++) {
		$i_t = date('h:i:s', $time);
		$times[$x] = $i_t;
		$time = strtotime("+{$time_interval} minutes", $time);
	}
	
    $team2 = array_splice($teams,(count($teams)/2)); 
    $team1 = $teams; 
	$teams_cnt = count($team1)+count($team2) * $rounds;
	for ($i=0; $i < $teams_cnt-1; $i++) { 
		$i_d = date('Y-m-d', $date);
		for ($j=0; $j<count($team1); $j++) { 
			if (($i%2 !=0) && ($j%2 ==0)) {
				$schedule[$i_d][$times[$j]]["Team 1"]=$team2[$j]; 
				$schedule[$i_d][$times[$j]]["Team 2"]=$team1[$j]; 
			} else { 
				$schedule[$i_d][$times[$j]]["Team 1"]=$team1[$j]; 
				$schedule[$i_d][$times[$j]]["Team 2"]=$team2[$j]; 
			} 
		} 
		if ($teams_cnt-1 > 2) { 
			array_unshift($team2,array_shift(array_splice($team1,1,1))); 
			array_push($team1,array_pop($team2)); 
		} 
		$date = strtotime('+1 week', $date);
	} 
    return $schedule; 
}

if (isset($action) && strlen(trim($action)) > 0) {
	switch ($action) {
		case 'create':
			$sql = "SELECT id FROM bhleague.schedule";
			if ($rs = $db->query($sql)) {
				$rs_cnt = $rs->num_rows;
				if ($rs_cnt <= 0) {
					$game_date = strtotime("$game_date");
					$game_time = strtotime("$game_time");
					$time_interval = 60;
					$game_teams = substr($teams, 2, strlen($teams) - 2);
					$game_teams = explode(',', $game_teams);
					
					$schedule = scheduler($game_teams, $game_date, $game_time, $time_interval, 2);
					
					foreach ($schedule as $date => $game) {
						foreach ($game as $time => $teams) {
							#echo $date.' | '.$time.' | '.$teams['Team 1'].' vs '.$teams['Team 2'].NL;
							$team1 = $teams['Team 1'];
							$team2 = $teams['Team 2'];
							$sqls = "INSERT INTO bhleague.schedule SET game_date = '{$date}', game_time = '{$time}', team1 = '{$team1}', team2 = '{$team2}'";
							#echo $sqls.NL;
							$rss = $db->query($sqls);
						}
					}
					echo '{success:true}';
				} else echo '{success:false:record_exists}';
				$rs->close();
			} else echo '{success:false:table_error}';
			break;
		case 'post':
			/*$sql = "INSERT INTO bhleague.teams (team_name, team_desc, logo) VALUES ('".$team_name."','".$team_desc."','".$logo."')";
			if ($rs = $db->query($sql)) echo '{success:true}';
			else*/ echo '{success:false}';
			break;
		default: echo '{success:false}'; break;
	}

} else echo '{success:false}';

$db->close();
$db = NULL;
?>