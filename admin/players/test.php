<?php
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
//extract($_REQUEST);
$array = array();

$sql = "SELECT CONCAT(player_fname, ' ', player_lname) as player, id FROM bhleague.players ORDER BY team_id";

//echo $sql;exit;

$rx = $db->query($sql);


while ($rowx = $rx->fetch_assoc())
{
	$ix ="'".$rowx['id']."'";
	$iy = "'".$rowx['player']."'";
    $arrx[] = "[ {$ix}, {$iy} ]";

}
$iz = ' [' . implode(', ', $arrx) . ']';

echo $iz;
?>