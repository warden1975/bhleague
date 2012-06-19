<?php
	define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
	define('DB_CLS', DIR . 'admin/class/db.cls.php');
	define('COMMON', DIR . 'bhlcommon.php');
	error_reporting (E_ALL ^ E_NOTICE);
	error_reporting(0);
	require DB_CLS;
	require COMMON;

	$db = new DB_Connect(MYSQL_INTRANET, 1);
	if (!$db) die("Can't connect to database.");

	//$player = 3;
	$d = array();
	$dx = array();

	extract($_REQUEST);
	
	$myarr = array();
	$obj;

	$sql = "SELECT `id`,team_name from bhleague.teams ";
	$result = $db->query($sql);
	while($rowzx = $result->fetch_object()){
	
	$myarr[$rowzx->id] = $rowzx->team_name;
	
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
					//if (isset($obj->team1x) && trim($obj->team1x)!='')
//					{
//					//If not isset -> set with dumy value
//					@$obj->team1x = "undefine";
//					@$team1x = @$obj->team1x;
//					} 
//					else
//					{
					@$team1x = @$obj->team1x;
					//}
					@$team2x = @$obj->team2x;
					@$score = @$obj->score;
				}
				
				$dx[] = "[ '{$game_date}', '{$team_id}', '{$position}', '{$team1x}','{$team1}',{$team2x},'{$team2}', '{$score}',  '{$points}', {$rebounds}, {$assists}]";
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

	//$sql = "select ave.player_name, ave.team_name, ave.logo, 
//	(select position_name from bhleague.positions where id = ave.player_position) as `position`, 
//	ave.points, ave.assists, ave.rebounds, ave.player_email, 
//	round((ave.points / ave.games_played), 2) as ppg, 
//	round((ave.assists / ave.games_played), 2) as apg, 
//	round((ave.rebounds / ave.games_played), 2) as rpg, 
//	ave.games_played, ave.player_height, ave.player_weight  
//	from (
//	select concat(b.player_fname, ' ', b.player_lname) as player_name, 
//		c.team_name, (if(a.player_id=b.id, b.position_id, 0)) as player_position, 
//		(if(a.player_id=b.id, b.email, '-')) as player_email, 
//		(if(a.player_id=b.id, b.height, '-')) as player_height, 
//		(if(a.player_id=b.id, b.weight, '-')) as player_weight, 
//		sum(if(a.player_id=b.id, ((a.game_points_1*1) + (a.game_points_2*2) + (a.game_points_3*3)), 0)) as points, 
//		sum(if(a.player_id=b.id, a.game_assists, 0)) as assists, 
//		sum(if(a.player_id=b.id, a.game_rebounds, 0)) as rebounds, 
//		sum(if(a.player_id=b.id, 1, 0)) as games_played, 
//		c.logo 
//	from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
//	where (select case (weekday(a.game_date)) 
//		when '1' then b.team_id 
//		when '5' then b.team_id2 
//		when '6' then b.team_id3
//		end) = c.id 
//		and weekday(a.game_date) = '{$gameday}' and b.id = '{$player}'  
//	group by b.id 
//	order by points desc
//	) as ave 
//	limit 1";
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
    
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400italic,400,700,700italic|Montserrat' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" href="css_v3/reset.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <link rel="stylesheet" href="css_v3/style.css" type="text/css" media="screen" title="style" charset="utf-8" />
	
    <!--[if IE 6]>
    <link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->    
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
	<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
	
	<script type="text/javascript" charset="utf-8" src="js_v3/jq.js"></script>
	<script type="text/javascript" charset="utf-8" src="js_v3/placeholder.js"></script>	
	<script type="text/javascript" charset="utf-8" src="js_v3/main.js"></script>	
	<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="extjs/ext-all.js"></script>    
    <script type="text/javascript" src="bhlcommon_v3.js"></script>
	<script type="text/javascript">
	Ext.bhlcommondata.player_stats = <?php echo $player_store; ?>;
	Ext.bhlcommondata.player_games = <?php echo $player_storex; ?>;
	</script>
	<script type="text/javascript" src="player_v3.js"></script>
  </head>
  <body>
  <style>
		.container {
    margin-bottom: 0;
    margin-left: auto;
    margin-right: auto;
    margin-top: 0;
    position: relative;
    width: 1120px;
}
	</style>
  </head>
  <body>
 <style>
	.x-grid3-row td {
     font-family: 'Montserrat',sans-serif;
    font-size: 12px;
	}
	.x-grid3-hd-inner {
 	font-family: 'Montserrat',sans-serif;
    font-size: 12px;

	}

	</style>
	<div class="fixed">
		<div id="header">
			<div class="container">
				<h1 class="logo"><a href="#"><img src="images_v3/logo.png" /></a></h1>
				<div class="wrap">
					<div class="menu">
						<div class="menu-link">											
							<a href="index.php">HOME</a>
							<a href="standings_v3.html">STANDINGS</a>
							<a href="schedule_v3.html">SCHEDULES</a>
							<a href="league_leaders_v3.html">LEAGUE LEADERS</a>
							<a href="rosters_v3.php">ROSTERS</a>
							<a href="photos_v3.php">PHOTOS</a>
							<a href="about_v3.php">ABOUT</a>												
							<a href="signup_v3.php" class="signup"> <span> SIGNUP </span></a>
						</div>
					</div>
				</div>
			</div>
			<div class="subheader">
				<div class="container">
					<div class="input-search">
						<div class="input-text">
							<div id="league_sel" data-value="">								
								<a href="#" class="btn-sel"><span><input type="text" id="local-gamedays" size="20"/></span></a>
							</div>							
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="container">
				<div class="col1 col">
					<h2 class="title">Player Profile</h2>
					<div class="people">
						<ul>
							<li class="first">
								<a href="#">
									<div class="space"></div>
									<h5><?php echo @$row->team_name;?></h5>
									<div class="img">
										<div class="img-person">
											<img src="images_v3/no-pic.png" />
										</div>
									</div>
									<div class="name t_center">
										<?php echo @$row->player_name;?>
									</div>
									<div class="point">
										<div class="p_point">
											SCORING RANK
											<div class="big-center">
												<?php echo $row2->rank; ?>
											</div>
											<div class="clear"></div>											
										</div>
									</div>
								</a>
							</li>
							<li class="step2">
								<div class="img-logo">
									<div class="logo">
										<img src="<?php echo ($row->logo <> NULL) ? $row->logo : 'images/no-profile.gif'; ?>" />
									</div>
								</div>
							</li>							
						</ul>
					</div>
				</div>

				<div class="col2 col">
					<h2 class="title">2012 Season Statistics</h2>				
					<dl id="prastats">
					<dt>PPG</dt>
		              <dd><?php echo @$row->ppg; ?> </dd>
		              <dt>RPG</dt>
		              <dd><?php echo @$row->rpg; ?></dd>
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
					<div class="playerInfoStatsPlayerInfoBorders"> Position: <span class="playerInfoValuePlayerInfoBorders"> </span> <?php echo @$row->position; ?> </div>
				</div>
				<div class="padding_clear"></div>
			</div>
		</div>
		<div id="content" class="dark-c">
			<div class="container">
				<div class="col">
					<div class="table1">
						<h2 class="title2">Player Stats By Game</h2>
						<div id="grid-player-stats-game" class="sub-box"></div>
					</div>
					<div class="table1">
						<h2 class="title2">Player Stats By Season</h2>
						<div id="grid-player-stats-season" class="sub-box"></div>
					</div>		
				</div>			
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<div class="f_left">
					&copy; COPYRIGHT 2012 BH LEAGUE.com
				</div>
				<div class="f_right">
					<a href="index.php">HOME</a>
					<a href="standings_v3.html">STANDINGS</a>
					<a href="schedule_v3.html">SCHEDULES</a>
					<a href="league_leaders_v3.html">LEAGUE LEADERS</a>
					<a href="rosters_v3.php">ROSTERS</a>
					<a href="photos_v3.php">PHOTOS</a>
					<a href="about_v3.php">ABOUT</a>												
					<a href="signup_v3.php" class="signup"> <span> SIGNUP </span></a>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
  </body>
</html>