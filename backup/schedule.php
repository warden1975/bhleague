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

$myarr = array();
//
$sql = "SELECT `id`,team_name from bhleague.teams ";
$result = $db->query($sql);
while($row = $result->fetch_array()){

$myarr[$row['id']] = $row['team_name'];

}


function numberToPlace($number) {
 
if(!is_numeric($number)) return false;
$number = intval($number);
$lastNumber = substr($number, -1);
$lastTwo = substr($number, -2);
 
$append = ($lastNumber == '1' && $lastTwo != 11) ? 'st' : (($lastNumber == '2' && $lastTwo != 12) ? 'nd' : (($lastNumber == '3' && $lastTwo != 13) ? 'rd' : 'th'));
 
return $number . $append;
}

$sql = "SELECT id,game_date,MONTHNAME(game_date) as `monthname`,MONTH(game_date) as `month`, day(game_date) as date,game_time,CONCAT(team1,' vs. ', team2) as teams,CONCAT(team1_score,' - ',team2_score) as score,team1_score,team2_score,team1,team2 FROM `bhleague`.`schedule`  order by month(game_date),day(game_date);";
//$sql = "SELECT id,game_date,MONTH(game_date) as `month`, day(game_date) as date,game_time,CONCAT(team1,' vs. ', team2) as teams,CONCAT(team1_score,' - ',team2_score) as score,team1,team2 FROM `bhleague`.`schedule` order by month(game_date),day(game_date);";

if ($rs = $db->query($sql)) {
	$record_count = $rs->num_rows;
	if ($record_count > 0) {
		
	while($obj = $rs->fetch_assoc()){
		if(@$obj['team1_score']>@$obj['team2_score'])
		{
		@$obj['teams'] = "<a href='http://www.bhleague.com/games.php?gamedate=".$obj['game_date']."&team1=".$obj['team1']."&team2=".$obj['team2']."' target='_self'>".$myarr[$obj['team1']]." vs. ".$myarr[$obj['team2']]."</a>";
		@$obj['winner'] = @$myarr[@$obj['team1']];
		}
		else if(@$obj['team1_score']<@$obj['team2_score'])
		{
		@$obj['teams'] = "<a href='http://www.bhleague.com/games.php?gamedate=".$obj['game_date']."&team1=".$obj['team1']."&team2=".$obj['team2']."' target='_self'>".$myarr[$obj['team1']]." vs. ".$myarr[$obj['team2']]."</a>";
		@$obj['winner'] = $myarr[$obj['team2']];
		}
		else
		{
		@$obj['teams'] = $myarr[$obj['team1']]." vs. ".$myarr[$obj['team2']];
		}
		//@$obj['month'] = date( 'F', mktime(0, 0, 0, $obj['month']) );
		//@$obj['date'] = numberToPlace($obj['date']);
		array_push($arr,$obj);
	}

	echo '{results:'.$record_count.',rows:'.json_encode($arr).'}';
	}
}

//$data = implode(', ', $d);
//echo "[ {$data} ]";

$db->close();
$db = NULL;


?>