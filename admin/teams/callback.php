<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

if (isset($action) && strlen(trim($action)) > 0) {
	switch ($action) {
		case 'update':
			$sql = "UPDATE bhleague.teams SET `".@$field."` = '".@$value."' WHERE id = ".@$id;
			break;
		case 'insert':
			$sql = "INSERT INTO bhleague.teams (team_name, team_desc, logo) VALUES ('".$team_name."','".$team_desc."','".$logo."')";
			break;
		case 'delete':
			$sql = "DELETE FROM bhleague.teams WHERE id = ".$id;
			break;
	}
	
	if ($rs = $db->query($sql)) echo '{success:true}';
	else echo '{success:false}';

} else echo '{success:false}';

$db->close();
$db = NULL;
?>