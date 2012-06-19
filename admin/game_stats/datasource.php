<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

$season_arr = array();

$sql = "SELECT `id`,name from bhleague.seasons ";
$resultz = $db->query($sql);
while($rowz = $resultz->fetch_array()){

$season_arr[$rowz['id']] = $rowz['name'];

}

$data = array();


$sql = "SELECT * FROM `bhleague`.`games_stats`";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_array()) {
			$row['season'] = @$season_arr[$row['season']];
			$data[] = $row;
		}
	}
	$rs->close();
	
	echo '{results:' . $rs_cnt . ',rows:' . json_encode($data) . '}';
} else '{success:false}';

$db->close();
$db = NULL;
?>