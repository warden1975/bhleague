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
			$sql = "UPDATE bhleague.games_stats SET `".@$field."` = '".@$value."' WHERE id = ".@$id;
			break;
		case 'insert':
			$sql = "INSERT INTO bhleague.games_stats (game_date, game_winner, winner_score, game_loser, loser_score) VALUES ('".$game_date."','".$game_winner."','".$winner_score."','".$game_loser."','".$loser_score."')";
			break;
		case 'delete':
			$sql = "DELETE FROM bhleague.games_stats WHERE id = ".$id;
			break;
	}
	
	if ($rs = $db->query($sql)) echo '{success:true}';
	else echo '{success:false}';

} else echo '{success:false}';

$db->close();
$db = NULL;
?>