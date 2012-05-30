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
		FROM  bhleague.players_stats a INNER JOIN bhleague.players b ON a.player_id = b.id where  b.id='{$player}' and WEEKDAY(a.game_date) = '{$gameday}'
		group by a.game_date";
		
		//echo $sqlx;exit;
		
		
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
				$sqlz ="SELECT team1,team2,team1 as team1x, team2 as team2x,team1_score,team2_score,CONCAT(team1_score,' - ',team2_score) as score from bhleague.`schedule` WHERE game_date ='{$game_date}' AND (team1 ='{$team_id}' OR team2 ='{$team_id}') LIMIT 1";
				$rsz = $db->query($sqlz);  
				if($rsz)
				 {
				$rsz_cnt = $rsz->num_rows;
				if($rsz_cnt>0)
				
				{
				
				    $obj = $rsz->fetch_object();
				
					if((@$obj->team1_score)>(@$obj->team2_score))
					{
						@$obj->team1 = "<font color=\'green\'>".$myarr[@$obj->team1]."</font>";
						
						@$obj->team2 = "<font color=\'red\'>".$myarr[@$obj->team2]."</font>";
					}
					else if((@$obj->team1_score)<(@$obj->team2_score))
					{
						@$obj->team1 = "<font color=\'red\'>".$myarr[@$obj->team1]."</font>";
						@$obj->team2 = "<font color=\'green\'>".$myarr[@$obj->team2]."</font>";
						
		
					}
					else
					{
						@$obj->team1 = $myarr[@$obj->team1];
						@$obj->team2 = $myarr[@$obj->team2];
						//echo @$obj->team1;exit;
					}
					@$team1 = @$obj->team1;
					@$team2 = @$obj->team2;
					@$team1x = @$obj->team1x;
					@$team2x = @$obj->team2x;
					@$score = @$obj->score;
				}
				
				$dx[] = "[ '{$game_date}', '{$team_id}', '{$position}', {$team1x},'{$team1}',{$team2x},'{$team2}', '{$score}',  {$points}, {$rebounds}, {$assists}]";
			}
			
		}
		$rsx->close();
	}
	}
	$datax = implode(', ', $dx);
	$player_storex = "[ {$datax} ]";	
	
		

	$sql1 = "select concat(year(a.game_date), '-', month(a.game_date)) as season, concat(b.player_fname, ' ', b.player_lname) as player_name, 
		sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
		sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
		sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played, 
		c.team_name, c.id as team_id 
	from bhleague.players_stats a, bhleague.players b , bhleague.teams c  
	where 
		(select case (weekday(a.game_date)) 
		when '1' then b.team_id 
		when '5' then b.team_id2 
		when '6' then b.team_id3
		end) = c.id 
		and weekday(a.game_date) = '{$gameday}' and b.id = '{$player}'  
	group by month(a.game_date), b.id 
	order by points desc, rebounds desc, assists desc;";
	//$sql1 = "select concat(year(a.game_date), '-', month(a.game_date)) as season, concat(b.player_fname, ' ', b.player_lname) as player_name, 
	//	sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
	//	sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
	//	sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
	//	sum(if(a.player_id=b.id, 1, 0)) as games_played, 
	//	c.team_name, c.id as team_id 
	//from bhleague.players_stats a, bhleague.players b , bhleague.teams c  
	//where 
	//	(select case (weekday(a.game_date)) 
	//	when '1' then b.team_id 
	//	when '5' then b.team_id2 
	//	when '6' then b.team_id3
	//	end) = c.id 
	//	and weekday(a.game_date) IN ('1','5','6') and b.id = '{$player}'  
	//group by month(a.game_date), b.id 
	//order by points desc, rebounds desc, assists desc;";

	if ($rs1 = $db->query($sql1)) {
		$rs1_cnt = $rs1->num_rows;
		if ($rs1_cnt > 0) {
			while ($row1 = $rs1->fetch_object()) {
				$season = $row1->season;
				$player_name = $row1->player_name;
				$points = $row1->points;
				$rebounds = $row1->rebounds;
				$assists = $row1->assists;
				$games = $row1->games_played;
				$team = $row1->team_name;
				$team_id = $row1->team_id;
				$ppg = number_format(@round($points / $games, 2), 1);
				$rpg = number_format(@round($rebounds / $games, 2), 1);
				$apg = number_format(@round($assists / $games, 2), 1);
				
				$d[] = "['{$season}', '{$team_id}', '{$team}', '{$games}', {$ppg}, {$rpg}, {$apg}]";
			}
		}
		$rs1->close();
	}

	$data = implode(', ', $d);
	$player_store = "[ {$data} ]";

	$sql = "select ave.player_name, ave.team_name, ave.logo, 
	(select position_name from bhleague.positions where id = ave.player_position) as `position`, 
	ave.points, ave.assists, ave.rebounds, ave.player_email, 
	round((ave.points / ave.games_played), 2) as ppg, 
	round((ave.assists / ave.games_played), 2) as apg, 
	round((ave.rebounds / ave.games_played), 2) as rpg, 
	ave.games_played, ave.player_height, ave.player_weight  
	from (
	select concat(b.player_fname, ' ', b.player_lname) as player_name, 
		c.team_name, (if(a.player_id=b.id, b.position_id, 0)) as player_position, 
		(if(a.player_id=b.id, b.email, 0)) as player_email, 
		(if(a.player_id=b.id, b.height, 0)) as player_height, 
		(if(a.player_id=b.id, b.weight, 0)) as player_weight, 
		sum(if(a.player_id=b.id, ((a.game_points_1*1) + (a.game_points_2*2) + (a.game_points_3*3)), 0)) as points, 
		sum(if(a.player_id=b.id, a.game_assists, 0)) as assists, 
		sum(if(a.player_id=b.id, a.game_rebounds, 0)) as rebounds, 
		sum(if(a.player_id=b.id, 1, 0)) as games_played, 
		c.logo 
	from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
	where (select case (weekday(a.game_date)) 
		when '1' then b.team_id 
		when '5' then b.team_id2 
		when '6' then b.team_id3
		end) = c.id 
		and weekday(a.game_date) = '{$gameday}' and a.player_id = '{$player}'  
	group by b.id 
	order by points desc
	) as ave 
	limit 1";
	//$sql = "select ave.player_name, ave.team_name, ave.logo, 
	//(select position_name from bhleague.positions where id = ave.player_position) as `position`, 
	//ave.points, ave.assists, ave.rebounds, ave.player_email, 
	//round((ave.points / ave.games_played), 2) as ppg, 
	//round((ave.assists / ave.games_played), 2) as apg, 
	//round((ave.rebounds / ave.games_played), 2) as rpg, 
	//ave.games_played, ave.player_height, ave.player_weight  
	//from (
	//select concat(b.player_fname, ' ', b.player_lname) as player_name, 
	//	c.team_name, (if(a.player_id=b.id, b.position_id, 0)) as player_position, 
	//	(if(a.player_id=b.id, b.email, 0)) as player_email, 
	//	(if(a.player_id=b.id, b.height, 0)) as player_height, 
	//	(if(a.player_id=b.id, b.weight, 0)) as player_weight, 
	//	sum(if(a.player_id=b.id, ((a.game_points_1*1) + (a.game_points_2*2) + (a.game_points_3*3)), 0)) as points, 
	//	sum(if(a.player_id=b.id, a.game_assists, 0)) as assists, 
	//	sum(if(a.player_id=b.id, a.game_rebounds, 0)) as rebounds, 
	//	sum(if(a.player_id=b.id, 1, 0)) as games_played, 
	//	c.logo 
	//from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
	//where (select case (weekday(a.game_date)) 
	//	when '1' then b.team_id 
	//	when '5' then b.team_id2 
	//	when '6' then b.team_id3
	//	end) = c.id 
	//	and weekday(a.game_date) IN ('1','5','6') and a.player_id = '{$player}'  
	//group by b.id 
	//order by points desc
	//) as ave 
	//limit 1";

	if ($rs = $db->query($sql)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			$row = $rs->fetch_object();
		}
		$rs->close();
	}

	$sql3 = "set @rank:=0;";
	$rs3 = $db->query($sql3);
	//$sql2 = "select rank, r.points, r.player_id from (
	//  select @rank:=@rank+1 as rank, pt.points, pt.player_id  
	//	from (
	//		select b.id as player_id, 
	//			sum(if(a.player_id=b.id, ((a.game_points_1*1) + (a.game_points_2*2) + (a.game_points_3*3)), 0)) as points 
	//		from bhleague.players_stats a, bhleague.players b  
	//		where weekday(a.game_date) = '{$gameday}'  
	//		group by b.id 
	//		order by points desc
	//	) as pt 
	//group by pt.player_id  
	//order by pt.points desc ) as r 
	//where r.player_id = '{$player}';";
	$sql2 = "select rank, r.points, r.player_id from (
	  select @rank:=@rank+1 as rank, pt.points, pt.player_id  
		from (
			select b.id as player_id, 
				sum(if(a.player_id=b.id, ((a.game_points_1*1) + (a.game_points_2*2) + (a.game_points_3*3)), 0)) as points 
			from bhleague.players_stats a, bhleague.players b  
			where weekday(a.game_date) IN ('1','5','6')  
			group by b.id 
			order by points desc
		) as pt 
	group by pt.player_id  
	order by pt.points desc ) as r 
	where r.player_id = '{$player}';";

	if ($rs2 = $db->query($sql2)) {
		$rs2_cnt = $rs2->num_rows;
		if ($rs2_cnt > 0) {
			$row2 = $rs2->fetch_object();
		}
		$rs2->close();
	}

	$db->close();
	$db = NULL;
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>BHLeague</title>
		
		<link href='http://fonts.googleapis.com/css?family=Arvo:700,400italic,700italic,400' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css_v2/bebas/stylesheet.css" type="text/css"   charset="utf-8" />
		<link rel="stylesheet" href="css_v2/roboto/stylesheet.css" type="text/css"  charset="utf-8" />	
		
		<link rel="stylesheet" href="css_v2/reset.css" type="text/css" media="screen" title="style" charset="utf-8" />
		<link rel="stylesheet" href="css_v2/style.css" type="text/css" media="screen" title="style" charset="utf-8" />
		
		<!--[if IE 6]>
		<link rel="stylesheet" href="css_v2/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
		<![endif]-->    
		<!--[if IE 7]>
		<link rel="stylesheet" href="css_v2/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
		<![endif]-->
		<!--[if IE 8]>
		<link rel="stylesheet" href="css_v2/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
		<![endif]-->

		<script type="text/javascript" charset="utf-8" src="js_v2/jq.js"></script>
		<script type="text/javascript" charset="utf-8" src="js_v2/placeholder.js"></script>	
		<script type="text/javascript" charset="utf-8" src="js_v2/main.js"></script>	
		
		<!-- ** CSS ** -->
		<!-- base library-->
		<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />     
		
		<!-- ExtJS library: base/adapter -->
		<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
		<!-- ExtJS library: all widgets -->
		<script type="text/javascript" src="extjs/ext-all.js"></script>    
	<!--	<script type="text/javascript" src="index_main_v2.js"></script>-->
		<script type="text/javascript" src="bhlcommon.js"></script>
	<script type="text/javascript">
	Ext.bhlcommondata.player_stats = <?php echo $player_store; ?>;
	Ext.bhlcommondata.player_games = <?php echo $player_storex; ?>;
	</script>
	<script type="text/javascript" src="player.js"></script>
    <style type="text/css">
		.title_playerx{background:#00A9D3;font-size:18px;font-family:"Arvo",'Arial';font-weight:bold;color:white;letter-spacing:-1px;padding:0px 10px;text-shadow:0px -1px 1px #000;}	
		.title_profile{background:#00A9D3;font-size:18px;font-family:"Arvo",'Arial';font-weight:bold;color:white;letter-spacing:-1px;padding:0px 10px;text-shadow:0px -1px 1px #000;}			
    </style>
	</head>
	<body>
	<div class="container">
		<div id="header">
			<h1 class="logo"><a href="#"><img src="images/logo_v2.png" /></a></h1>
			<div class="wrap">
				<div class="menu">
					<img src="images/banner-left.png" class="b_left" />
					<img src="images/banner-right.png" class="b_right" />	
					<div id="game_league_id" style="float: left;left:30px;top:15px;position:absolute">
                      <input type="text" id="local-gamedays" size="20"/>
                    </div>			
					<div class="menu-link">				
						<a href="index.php" target="_self">HOME</a>
						<a href="standings_v2.html" target="_self">STANDINGS</a>
						<a href="schedule_v2.html" target="_self">SCHEDULES</a>
						<a href="league_leaders_v2.html" target="_self">LEAGUE LEADERS</a>
						<a href="rosters_v2.php" target="_self">ROSTERS</a>
						<a href="payments_v2.html">PAYMENTS</a>
						
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="col1 col">
				<h2 class="title">Player Profile</h2>
				<div class="padding_clear"></div>
				<div class="people">
					<ul>
						<li class="first">
							<a href="#">
								<h5><?php echo @$row->team_name;?></h5>
								<div class="img">
									<div class="img-person">
										<!--<img src="images/1st.png" class="rank" />-->
										<img src="images/person.png" />
									</div>
									<div class="name">
										<?php echo @$row->player_name;?>
									</div>
								</div>
								<div class="point">
									<img src="images/tridown.png" />
									<div class="p_point">
										<div class="small">
											SCORING RANK
										</div>
										<div class="big" style="padding-top:0px">
											# <?php echo $row2->rank; ?>
										</div>
									</div>
								</div>
							</a>
						</li>
						<li class="step2">								
							<div class="img-logo">
								<div class="logo">
									<img src="<?php echo ($row->logo <> NULL) ? $row->logo : 'images/no-profile.gif'; ?>" />
								</div>
								<!--<div class="name">
									<?php echo @$row->team_name;?>
								</div>-->
							</div>
						</li>						
					</ul>
				</div>
			</div>

			<div class="col2 col">
				<h2 class="title">2012 Season Statistics</h2>				
				<div class="padding_clear"></div>
				<dl id="prastats">
				  <dt>PPG</dt>
	              <dd><?php echo @$row->ppg; ?> </dd>
	              <dt>RPG</dt>
	              <dd><?php echo @$row->rpg; ?> </dd>
	              <dt>APG</dt>
	              <dd><?php echo @$row->apg; ?></dd>
	              <dt>GAMES</dt>
	              <dd><?php echo @$row->games_played; ?></dd>
	              <dt class="pralast">POINTS</dt>
	              <dd class="pralast"><?php echo @$row->points; ?></dd>
				  <div class="clear"></div>
	            </dl>
				<div class="clear"></div>
				<div class="playerInfoStatsPlayerInfoBorders"> Email: <span class="playerInfoValuePlayerInfoBorders"><?php echo @$row->player_email; ?> </span> </div>
				<div class="playerInfoStatsPlayerInfoBorders"> Height: <span class="playerInfoValuePlayerInfoBorders"><?php echo (trim(@$row->player_height) != '')?@$row->player_height:' - '; ?> </span> </div>
				<div class="playerInfoStatsPlayerInfoBorders"> Weight: <span class="playerInfoValuePlayerInfoBorders"><?php echo (trim(@$row->player_weight) != '')?@$row->player_weight:' - '; ?> </span> </div>
				<div class="playerInfoStatsPlayerInfoBorders"> Position: <span class="playerInfoValuePlayerInfoBorders"><?php echo @$row->position; ?> </span> </div>				
			</div>
			<div class="padding_clear"></div>
		</div>
		<div id="content" class="dark-c">
			<div class="col col3-full">
				<div class="table1">
					<h2 class="title2">Player  Stats By Game</h2>
					<div id="grid-player-stats-game" class="sub-box"></div>
				</div>			
			</div>
			
			<div class="clear"></div>
		</div>
		<div id="content" class="dark-c">
			<div class="col col3-full">
				<div class="table1">
					<h2 class="title2"> Player Stats By Season</h2>
					<div id="grid-player-stats-season" class="sub-box"></div>
				</div>			
			</div>
			
			<div class="clear"></div>
		</div>
		<?php include('footer.php'); ?>
	</div>
	</body>
