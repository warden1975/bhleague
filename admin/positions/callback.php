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
			$sql = "UPDATE bhleague.positions SET `".@$field."` = '".@$value."' WHERE id = ".@$id;
			break;
		case 'insert':
			$sql = "INSERT INTO bhleague.positions (position_name, position_abbv) VALUES ('".$position_name."','".$position_abbv."')";
			break;
		case 'delete':
			$sql = "DELETE FROM bhleague.positions WHERE id = ".$id;
			break;
	}
	
	if ($rs = $db->query($sql)) echo '{success:true}';
	else echo '{success:false}';

} else echo '{success:false}';

$db->close();
$db = NULL;
?>