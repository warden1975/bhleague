<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");
$action = '';
extract($_REQUEST);

$d = array();

$sql = "select a.id, a.game_time, 
concat((select team_name from bhleague.teams b where a.team1 = b.id), ' vs ', (select team_name from bhleague.teams b where a.team2 = b.id)) as playing, 
a.game_date 
from bhleague.`schedule` a 
where game_date = (select game_date from bhleague.`schedule` where game_date >= now() group by game_date limit 1);";

	$sql = "select a.id, a.game_time, 
	concat((select team_name from bhleague.teams b where a.team1 = b.id), ' vs ', (select team_name from bhleague.teams b where a.team2 = b.id)) as playing, 
	a.game_date,
	(select mini_logo from bhleague.teams b where a.team1 = b.id) mlogo1,
	(select mini_logo from bhleague.teams b where a.team2 = b.id) mlogo2 
	from bhleague.`schedule` a 
	where game_date = (select game_date from bhleague.`schedule` where game_date >= now() group by game_date limit 1);";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_object()) {
			$playing = $row->playing;
			$game_time = $row->game_time;
			if($action == 'new'){
				$d[] = $row;
			}else{			
				$d[] = "['{$playing}', '{$game_time}']";
			}
		}
	}
}

if($action == 'new'){

	$tbl = '';
	foreach($d as $idx => $val){
		$tbl .= '<tr>
					<td width="15%"><img src="'.$val->mlogo1.'" /></td>
					<td width="10%"><b>VS</b></td>
					<td width="15%"><img src="'.$val->mlogo2.'" /></td>
					<td width="25%">'.$val->game_time.' (PST)</td>
				<tr>';	
	}
	echo '<table cellpaddding="0" cellspacing="0" class="playon">'.$tbl.'</table>';
}else{

	
	$data = implode(', ', $d);
	echo "[ {$data} ]";
}

$db->close();
$db = NULL;