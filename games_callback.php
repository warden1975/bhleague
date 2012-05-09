	<?php
	define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
	define('DB_CLS', DIR . 'admin/class/db.cls.php');

	require DB_CLS;

	$db = new DB_Connect(MYSQL_INTRANET, 1);
	if (!$db) die("Can't connect to database.");

	extract($_REQUEST);

	$current_year = date("Y");
	$previous_year = $current_year -1;

	$myarr = array();
	
	//$team1 =13;
//	$team2 =8;
//	$gamedate ='2012-04-24 00:00:00';
	$sql = "SELECT `id`,team_name from bhleague.teams ";
	$result = $db->query($sql);
	while($row = $result->fetch_array()){

	$myarr[$row['id']] = $row['team_name'];

	}

	if(@$action=='get_game_stats')
	{
		
		$sql = "SELECT `game_date`,`game_time`,team1,team2,team1 as team1x,team2 as team2x,CONCAT(team1_score,' - ',team2_score) as score,team1_score,team2_score FROM bhleague.`schedule` WHERE game_date ='$gamedate' and team1 =$team1 and team2=$team2 and YEAR(game_date) = $current_year  ORDER by `game_date`,`game_time` ";
		
		//echo $sql; exit;
		
		$arr = array();
		
		if (!$rs = $db->query($sql)) 
		{
		
			echo '{success:false}';
		
		}
		else
		{
			
			$record_count = $rs->num_rows;
			while($obj = $rs->fetch_array())
			{
				@$obj['game_date'] = date('D M d, Y', strtotime(@$obj['game_date']));
				if(@$obj['team1_score']>@$obj['team2_score'])
				{
					@$obj['team1'] = "<font color='green'>".$myarr[$obj['team1']]."</font>";
					@$obj['team2'] = "<font color='red'>".$myarr[$obj['team2']]."</font>";
				}
				else if(@$obj['team1_score']<@$obj['team2_score'])
				{
					@$obj['team1'] = "<font color='red'>".$myarr[$obj['team1']]."</font>";
					@$obj['team2'] = "<font color='green'>".$myarr[$obj['team2']]."</font>";

				}
				else
				{
					@$obj['team1'] = $myarr[$obj['team1']];
					@$obj['team2'] = $myarr[$obj['team2']];
				}
				array_push($arr,$obj);
			}
		
			echo '{"results":'.$record_count.',"rows":'.json_encode($arr).'}';
		
		}
	}

	else if(@$action=='get_game_player_roster')
	{
		if(!isset($team_id) || $team_id=='')
		{
			$team_id= getLastGameDate();
		}
		$d = array();

	$sql = "select distinct b.team_id,concat(b.player_fname, ' ', b.player_lname) as player_name,b.id as player_id, 
		b.height, b.weight, (select position_abbv from bhleague.positions where id = b.position_id) as `position`, 
		sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
		sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
		sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played
	from bhleague.players_stats a, bhleague.players b where b.team_id IN($team1,$team2) and a.game_date ='$gamedate'
	group by b.team_id,b.id 
	order by b.team_id, points desc, rebounds desc, assists desc;";
	
	//echo $sql;exit;

	if ($rs = $db->query($sql)) 
	{
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) 
		{
			while ($row = $rs->fetch_object()) 
			{
				$player_id = $row->player_id;
				$player = $row->player_name;
				$height = htmlentities($row->height, ENT_QUOTES);
				$weight = @$row->weight;
				$position = @$row->position;
				$points = $row->points;
				$rebounds = $row->rebounds;
				$assists = $row->assists;
				$games = $row->games_played;
				$ppg = number_format(@round($points / $games, 2), 1);
				$rpg = number_format(@round($rebounds / $games, 2), 1);
				$apg = number_format(@round($assists / $games, 2), 1);
				
				/*$d[] = "['{$player}', {$points}, {$rebounds}, {$assists}, {$games}, {$ppg}, {$rpg}, {$apg}]";*/
				$d[] = "['{$player_id}','{$player}', '{$height}', '{$weight}', '{$position}', {$ppg}, {$rpg}, {$apg}, {$games}]";
			}
		}
		$rs->close();
	}

	$data = implode(', ', $d);
	echo "[ {$data} ]";
	}
	else if(@$action=='get_previous_matchup')
	{
		$sql = "SELECT `game_date`,`game_time`,team1,team2,CONCAT(team1_score,' - ',team2_score) as score,team1_score,team2_score FROM bhleague.`schedule` WHERE  ((team1 =$team1 and team2=$team2) OR (team1 =$team2 and team2=$team1)) AND game_date < '$gamedate'   ORDER by `game_date`,`game_time`";
		
		$arr = array();
		
		if (!$rs = $db->query($sql)) 
		{
		
			echo '{success:false}';
		

		}
		else
		{
			
			$record_count = $rs->num_rows;
			while($obj = $rs->fetch_array())
			{
				@$obj['game_date'] = date('D M d, Y', strtotime(@$obj['game_date']));
				if(@$obj['team1_score']>@$obj['team2_score'])
				{
					@$obj['team1'] = "<font color='green'>".$myarr[$obj['team1']]."</font>";
					@$obj['team2'] = "<font color='red'>".$myarr[$obj['team2']]."</font>";
				}
				else if(@$obj['team1_score']<@$obj['team2_score'])
				{
					@$obj['team1'] = "<font color='red'>".$myarr[$obj['team1']]."</font>";
					@$obj['team2'] = "<font color='green'>".$myarr[$obj['team2']]."</font>";

				}
				else
				{
					@$obj['team1'] = $myarr[$obj['team1']];
					@$obj['team2'] = $myarr[$obj['team2']];
				}
				array_push($arr,$obj);
			}
		
			echo '{"results":'.$record_count.',"rows":'.json_encode($arr).'}';
		
		}
	}
	else if(@$action=='getAllGameDate')
	{
		$sql ="SELECT DISTINCT game_date,DATE_FORMAT(game_date,'%b %d %Y')as formatted_date FROM `games_stats` ORDER BY game_date;";
		
		//$conn = OpenDbConnection();   
		
		$result = $db->query($sql);
		
		$num = $result->num_rows;
		
		$i = 0;   
		
		$gameData = array("count" => $num, "games" => array());
		
		while ($row = $result->fetch_assoc()) {    
			$gameData["games"][$i] = $row;    
			$i++;  
		}   
		
		//CloseDbConnection($conn); 
		
		echo json_encode($gameData); 
	}
	else if(@$action=='getteamname')
	{
		echo @$myarr[@$team_id];
	}
	else if(@$action=='get_Date')
	{
		$sql ="SELECT DISTINCT game_date FROM `bhleague`.`games_stats` ORDER BY game_date desc LIMIT 1;";
		$result = $db->query($sql);
		if($result)
		{
			$row = $result->fetch_assoc();
			echo $row['game_date'];
		}
		else
		{
			echo $db->error;
		}
		
	}
	function getLastGameDate()
	{
		global $db;
		$sql ="SELECT DISTINCT game_date FROM `bhleague`.`games_stats` ORDER BY game_date desc LIMIt 1;";
		$result = $db->query($sql);
		$row = $result->fetch_assoc();
		return $row['game_date'];
	}
	$db->close();
	$db = NULL;
	
	?>