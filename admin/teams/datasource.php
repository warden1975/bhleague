<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

$st = "SELECT `id`, `team_name`,`team_desc`,`logo`,`mini_logo`,`season` FROM `bhleague`.`teams`";
$where = "";
$order = " ORDER BY `id`";
if (isset($query) && strlen(trim($query)) > 0) {
	$where = " WHERE `team_name` LIKE '%" . $query ."%' OR `team_desc` LIKE '%" . $query ."%' OR `mini_logo` LIKE '%" . $query ."%' OR `season` LIKE '%" . $query ."%'";
}
$sql = $st . $where . $order;

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_array()) {
			$data[] = $row;
		}
	}
	$rs->close();
	
	echo '{results:' . $rs_cnt . ',rows:' . json_encode($data) . '}';
} else '{success:false}';

$db->close();
$db = NULL;
?>