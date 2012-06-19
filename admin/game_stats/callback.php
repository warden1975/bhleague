<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);

if (isset($action) && strlen(trim($action)) > 0) 
{
	switch ($action) 
	{
		case 'update':
			$sql = "UPDATE bhleague.games_stats SET `".@$field."` = '".@$value."' WHERE id = ".@$id;
			if ($rs = $db->query($sql)) echo '{success:true}';
			else echo '{success:false}';
			break;
		case 'insert':
			$sql ="SELECT * from `bhleague`.`games_stats` WHERE game_date='".$game_date."' AND game_winner='".$game_winner."' AND game_loser ='".$game_loser."'";
			$rec = $db->query($sql);
			if($rec->num_rows>0)
			{
				echo '{duplicate:true}';
			}
			else if($rec->num_rows==0)
			{
				$sql = "INSERT INTO bhleague.games_stats (game_date, game_winner, winner_score, game_loser, loser_score,season) VALUES ('".$game_date."','".$game_winner."','".$winner_score."','".$game_loser."','".$loser_score."','".$season."')";
				if ($rs = $db->query($sql))
				{ 
				echo '{success:true}';
				}
				else 
				{
				echo '{success:false}';				
				}				
			}
			break;
		case 'delete':
			$sql = "DELETE FROM bhleague.games_stats WHERE id = ".$id;
			if ($rs = $db->query($sql)) echo '{success:true}';
			else echo '{success:false}';
				break;
			}
	
	

} 
else 
{
	echo '{success:false}';
}
$db->close();
$db = NULL;
?>