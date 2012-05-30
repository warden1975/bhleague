<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
	define('DB_CLS', DIR . 'admin/class/db.cls.php');
	define('COMMON', DIR . 'bhlcommon.php');

	require DB_CLS;
	require COMMON;

	$db = new DB_Connect(MYSQL_INTRANET, 1);
	if (!$db) die("Can't connect to database.");

	$player = 3;
	$d = array();
	$dx = array();

	extract($_REQUEST);
	
	$myarr = array();
	$obj;

	$sql = "SELECT `id`,team_name from bhleague.teams ";
	$result = $db->query($sql);
	while($row = $result->fetch_object()){
	
	$myarr[$row->id] = $row->team_name;
	
	}
	
	$sqlx ="SELECT concat(b.player_fname, ' ', b.player_lname) as player_name,b.id as player_id ,a.game_date,a.team_id,
 (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
		sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
		sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
		sum(if(a.player_id=b.id, game_assists, 0)) as assists
		FROM  bhleague.players_stats a INNER JOIN bhleague.players b ON a.player_id = b.id where  b.id='{$player}'
		group by a.game_date";
		
		
		
		if ($rsx = $db->query($sqlx)) {
		$rsx_cnt = $rsx->num_rows;
		if ($rsx_cnt > 0) {
			while ($rowx = $rsx->fetch_object()) 
			{
				$player_name = $rowx->player_name;
				$game_date = $rowx->game_date;
				$team_id = $rowx->team_id;
				$position = $rowx->position;
				$points = $rowx->points;
				$rebounds = $rowx->rebounds;
				$assists = $rowx->assists;
				//$games = $rowx->games_played;
				//$team = $rowx->team_name;
				$team_id = $rowx->team_id;
				$sqlz ="SELECT team1,team2,team1_score,team2_score,CONCAT(team1_score,' - ',team2_score) as score from bhleague.`schedule` WHERE game_date ='{$game_date}' AND (team1 ='{$team_id}' OR team2 ='{$team_id}') LIMIT 1";
				$rsz = $db->query($sqlz);  
				if($rsz)
				 {
				$rsz_cnt = $rsz->num_rows;
				if($rsz_cnt>0)
				
				{
				
				    $obj = $rsz->fetch_object();
				
					if((@$obj->team1_score)>(@$obj->team2_score))
					{
						@$obj->team1 = "<font color='green'>".$myarr[@$obj->team1]."</font>";
						
						@$obj->team2 = "<font color='red'>".$myarr[@$obj->team2]."</font>";
					}
					else if((@$obj->team1_score)<(@$obj->team2_score))
					{
						@$obj->team1 = "<font color='red'>".$myarr[@$obj->team1]."</font>";
						@$obj->team2 = "<font color='green'>".$myarr[@$obj->team2]."</font>";
						
		
					}
					else
					{
						@$obj->team1 = $myarr[@$obj->team1];
						@$obj->team2 = $myarr[@$obj->team2];
						//echo @$obj->team1;exit;
					}
					$team1 = @$obj->team1;
					$team2 = @$obj->team2;
					$score = @$obj->score;
				}
				
				$dx[] = "['{$player_name}', '{$game_date}', '{$team_id}', '{$position}', '{$team1}','{$team2}', '{$score}',  {$points}, {$rebounds}, {$assists}]";
			}
			
		}
		$rsx->close();
	}
	}
	$datax = implode(', ', $dx);
	$player_storex = "[ {$datax} ]";
	echo $player_storex;
?>