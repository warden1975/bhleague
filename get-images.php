<?php

require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
extract($_REQUEST);


$sql = "SELECT name,size,lastmod,url FROM bhleague.photos  ORDER by lastmod DESC ";

$arr = array();

if (!$rs = $db->query($sql)) {

	echo '{success:false}';

}else{
	
	$record_count = $rs->num_rows;
	while($obj = $rs->fetch_assoc()){
		//$arr[] = $obj;
		
		array_push($arr,$obj);
	}

	echo '{"images":'.json_encode($arr).'}';

}

?>
