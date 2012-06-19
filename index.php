<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

$m = 'ppg';
$mm = 'Points';
$pg = $r = $g = array();
$ppg = $apg = $rpg = array();

extract($_REQUEST);

$month_games = get_month_games();

### NEW VARS ###
	$rimage[0] = "1st.png";
	$rimage[1] = "2nd.png";
	$rimage[2] = "3rd.png";
	
	$xcount =1;
### NEW VARS ###						

$sql_ppg = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name,c.id as team_id,
	sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as score, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
where 
	(select case (weekday(a.game_date)) 
	when '1' then b.team_id 
	when '5' then b.team_id2 
	when '6' then b.team_id3
	end) = c.id 
	and weekday(a.game_date) = '{$gameday}'   
group by b.id 
order by score desc) as ave 
order by pg desc limit 3;";
		
if ($rs = $db->query($sql_ppg)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$ppg[] = $row;
		}
	}
	$rs->close();
}
$sql_rpg = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name,c.id as team_id, 
	sum(if(a.player_id=b.id, game_rebounds, 0)) as score, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
where 
	(select case (weekday(a.game_date)) 
	when '1' then b.team_id 
	when '5' then b.team_id2 
	when '6' then b.team_id3
	end) = c.id 
	and weekday(a.game_date) = '{$gameday}'   
group by b.id 
order by score desc) as ave 
order by pg desc limit 3;";
		
if ($rs = $db->query($sql_rpg)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$rpg[] = $row;
		}
	}
	$rs->close();
}

$sql_apg = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name,c.id as team_id, 
	sum(if(a.player_id=b.id, game_assists, 0)) as score, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
where 
	(select case (weekday(a.game_date)) 
	when '1' then b.team_id 
	when '5' then b.team_id2 
	when '6' then b.team_id3
	end) = c.id 
	and weekday(a.game_date) = '{$gameday}'   
group by b.id 
order by score desc) as ave 
order by pg desc limit 3;";
		
if ($rs = $db->query($sql_apg)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$apg[] = $row;
		}
	}
	$rs->close();
}

$sql = "select rank.team, round(rank.pts_for - rank.pts_against, 0) as pts_diff,rank.team_id from (
select b.team_name as team, b.id as team_id, 
	sum(if(a.game_winner=b.id,1,0)) as wins, 
	sum(if(a.game_loser=b.id,1,0)) as losses, 
	(sum(if(a.game_winner=b.id,a.winner_score,0)) + sum(if(a.game_loser=b.id,a.loser_score,0))) as pts_for,
	(sum(if(a.game_winner=b.id,a.loser_score,0)) + sum(if(a.game_loser=b.id,a.winner_score,0))) as pts_against 
from bhleague.games_stats a, bhleague.teams b 
where weekday(a.game_date) = '{$gameday}'  
group by b.id having pts_for <> 0 
order by wins desc, losses asc) as rank 
order by pts_diff desc limit 8;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$r[] = $row;
		}
	}
	$rs->close();
}

$sx = array();

$sql = "select a.id, a.game_time, 	
	DATE_FORMAT(a.game_date,'%M %d, %Y') as game_date,
	(select mini_logo from bhleague.teams b where a.team1 = b.id) mlogo1,
	(select mini_logo from bhleague.teams b where a.team2 = b.id) mlogo2 
	from bhleague.`schedule` a 
	where a.game_date = (select game_date from bhleague.`schedule` where game_date >= now() group by game_date limit 1) ORDER BY a.game_date DESC";

if ($rx = $db->query($sql)) {
	$rx_cnt = $rx->num_rows;
	if ($rx_cnt > 0) {
		while ($rx_row = $rx->fetch_assoc()) {
			$sx[] = $rx_row;
		}
	}
	$rx->close();
}

$sql = "select b.team_name as team, b.id as team_id,
	sum(if(a.game_winner=b.id,1,0)) as wins, 
	sum(if(a.game_loser=b.id,1,0)) as losses
from bhleague.games_stats a, bhleague.teams b 
where a.game_date in (
  select * from (
	  select game_date from bhleague.`games_stats` where game_date <= now() and weekday(game_date) = '{$gameday}' group by game_date desc limit 3
  ) as game_x
)
group by b.id having wins <> 0 
order by wins desc, losses asc;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_object()) {
			$team_id = $row->team_id;
			$team = $row->team;
			$wins = $row->wins;
			$losses = $row->losses;
			$win_pct = @round($wins / ($wins + $losses), 2) * 100;
			
			$g[] = array('team_id' => $team_id, 'team' => $team, 'win_pct' => $win_pct);
		}
	}
	$rs->close();
}

if(isset($temp) && $temp == 1){
	echo "<pre>";
	print_r($ppg);
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
	<script type="text/javascript" src="index_main_v3.js"></script>
    <script type="text/javascript" src="bhlcommon_v3.js"></script>
	<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '300544373362639', // App ID
      channelUrl : '//www.bhleague.com/fbchannel.php', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
   
  (function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=300544373362639";
	fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
	<script language="javascript">
		//Ext.bhlcommondata.month_games = <?php echo $month_games; ?>;	
		Ext.Element.prototype.setClass = function(cls,add_class){
		  add_class ? this.addClass(cls) : this.removeClass(cls)
		}	
		function togglebox_leaders(box) {
			var bp_el = Ext.get('box_point');
			var br_el = Ext.get('box_rebound');
			var ba_el = Ext.get('box_assist');
			
			switch (box) {
				default:
				case 1:
					Ext.get('a_box_point').addClass('on');
					Ext.get('a_box_rebound').removeClass('on');
					Ext.get('a_box_assist').removeClass('on');										
					bp_el.show();
					br_el.setVisibilityMode(Ext.Element.DISPLAY);
					br_el.hide();
					ba_el.setVisibilityMode(Ext.Element.DISPLAY);
					ba_el.hide();
					break;
				case 2:
					Ext.get('a_box_point').removeClass('on');
					Ext.get('a_box_rebound').addClass('on');
					Ext.get('a_box_assist').removeClass('on');					
					bp_el.setVisibilityMode(Ext.Element.DISPLAY);
					bp_el.hide();
					br_el.show();
					ba_el.setVisibilityMode(Ext.Element.DISPLAY);
					ba_el.hide();
					break;
				case 3:
					Ext.get('a_box_point').removeClass('on');
					Ext.get('a_box_rebound').removeClass('on');
					Ext.get('a_box_assist').addClass('on');					
					bp_el.setVisibilityMode(Ext.Element.DISPLAY);
					bp_el.hide();
					br_el.setVisibilityMode(Ext.Element.DISPLAY);
					br_el.hide();
					ba_el.show();
					break;
			}
		}	
	</script>
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
					<div id="div_fb" style="right:-15px;top:50px;position:absolute;z-index:999">
						<fb:like width="450" show_faces="false" /></fb:like>
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="container">
				<div class="col1 col">
					<h2 class="title">BHL Basketball Leaders</h2>
					<div class="sub"><a href="#" class="on" id="a_box_point" onclick="togglebox_leaders(1);">Points</a> <a id="a_box_rebound" href="#" onclick="togglebox_leaders(2);" >Rebound</a> <a id="a_box_assist" href="#" onclick="togglebox_leaders(3);">Assist</a></div>
					<div class="padding_clear"></div>
					
					<div class="people">
						<ul id="box_point">
							 <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($ppg as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
							<li <?php echo $li_class;?>>
								<a href="#">
									<div class="space"></div>
									<h5><a href="teams_v3.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#D3D2D2"><?php echo strtoupper($team_name); ?></a></h5>
									<div class="img">
										<div class="img-person">
											<img src="images_v3/<?php echo $image; ?>" class="rank" />
											<img src="images_v3/no-pic.png" />
										</div>
									</div>
									<div class="name">
										<a href="player_v3.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
									</div>
									<div class="point">
										<div class="p_point">
											<div class="big">
												<?php echo $pg; ?>
											</div>
											<div class="small">
												PPG									
											</div>
											<div class="clear"></div>											
										</div>
									</div>
								</a>
							</li>
							 <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
						</ul>
						<ul id="box_rebound" style="display:none;">
							<!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($rpg as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
							<li <?php echo $li_class;?>>
								<a href="#">
									<div class="space"></div>
									<h5><a href="teams_v3.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#D3D2D2"><?php echo strtoupper($team_name); ?></a></h5>
									<div class="img">
										<div class="img-person">
											<img src="images_v3/<?php echo $image; ?>" class="rank" />
											<img src="images_v3/no-pic.png" />
										</div>
									</div>
									<div class="name">
										<a href="player_v3.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?>
									</div>
									<div class="point">
										<div class="p_point">
											<div class="big">
												<?php echo $pg; ?>
											</div>
											<div class="small">
												RPG									
											</div>
											<div class="clear"></div>											
										</div>
									</div>
								</a>
							</li>
							 <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
							
						</ul>
						<ul id="box_assist" style="display:none;">
							 <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($apg as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
							<li <?php echo $li_class;?>>
								<a href="#">
									<div class="space"></div>
									<h5><a href="teams_v3.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#D3D2D2"><?php echo strtoupper($team_name); ?></a></h5>
									<div class="img">
										<div class="img-person">
											<img src="images_v3/<?php echo $image; ?>" class="rank" />
											<img src="images_v3/no-pic.png" />
										</div>
									</div>
									<div class="name">
										<a href="player_v3.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?>
									</div>
									<div class="point">
										<div class="p_point">
											<div class="big">
												<?php echo $pg; ?>
											</div>
											<div class="small">
												APG									
											</div>
											<div class="clear"></div>											
										</div>
									</div>
								</a>
							</li>
							 <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
							
						</ul>
					</div>
				</div>

				<div class="col2 col">
					
					<h2 class="title">Matches</h2>	
					<?php
					if (is_array($sx)) {
						$game_date = $sx[0][game_date];
					?>
					<div class="sub"><span><?php echo $game_date; ?></span></div>
					<div class="padding_clear"></div>
					<table cellpaddding="0" cellspacing="0" class="playon">
                    <?php
						foreach ($sx as $sk) {
							$id = $sk[id];
							$mlogo1 = $sk[mlogo1];
							$mlogo2 = $sk[mlogo2];
							$game_time = $sk[game_time];
					?>
						<tr>
							<td width="15%" class="first"><img src="<?php echo $mlogo1; ?>" /></td>
							<td width="10%"><b>VS</b></td>
							<td width="15%"><img src="<?php echo $mlogo2; ?>" /></td>
							<td width="55%" class="last"><div class="time"><?php echo $game_time; ?> (PST)</div></td>
						</tr>
						<tr>
							<td class="sep"></td>
						</tr>
                    <?php 
						} 
					?>   	
					</table>
					<?php 
					} else { 
					?>
					<div class="sub"><span>No Upcoming Matches</span></div>
					<div class="padding_clear"></div>
					<!--<table cellpaddding="0" cellspacing="0" class="playon">
						<tr>
							<td width="15%" class="first"></td>
							<td width="10%"><b></b></td>
							<td width="15%"></td>
							<td width="55%" class="last"><div class="time"></div></td>
						</tr>	
					</table>-->
				  	<?php 
				 	} 
				  	?>	
				</div>
				<div class="padding_clear"></div>
			</div>
		</div>
		<div id="content" class="dark-c">
			<div class="container">
				<div class="col col3">
					<div class="table1">
						<h2 class="title2">Team Standings</h2>
						<div id="grid-standings" ></div>
						
					</div>
					<div class="table1">
						<h2 class="title2">Top Players
							<div class="links">
								<a href="#" class="first active">Current</a>
								<a href="#">Past Two Weeks</a>
							</div>
						</h2>
						<div id="grid-top20" class="sub-box"></div>
					</div>
					<div class="table1">
						<h2 class="title2">Hottest Players <span class="l_pos">^Previous Two Games</span>							
						</h2>
						 <div id="grid-top10" class="sub-box"></div>
					</div>
				</div>
				<div class="col col4">
					<div class="table2">
						<h2 class="title2">Power Rankings <a href="standings_v3.html" class="r_pos">Complete Rankings</a></h2>
						<table cellspacing="0" cellpadding="0">
							<tr>
								<th width="70%">Team Name</th>
								<th width="15%" class="center">Rank</th>
								<th width="15%" class="center"></th>
							</tr>
							<tbody>
						  <tbody>
						  <?php
						foreach ($r as $kk) {
						$kk_sign = gmp_sign($kk['pts_diff']);
						if($xcount % 2==0)
						{
						?>
						  <tr class="nth">
							<td><a href="teams_v3.php?team_id=<?php echo $kk['team_id']; ?>" style="text-decoration:none; color:#FFFFFF;"><?php echo $kk['team']; ?></a></td>
							<td class="center"><?php echo $kk['pts_diff']; ?></td>
							<td class="center"><?php echo ($kk_sign == -1 or $kk_sign == 0) ? '<img src="images_v3/arrowdown2.png" />' : '<img src="images_v3/arrowup1.png" />'; ?></td>
						  </tr>
						  <?php
						 } 
						 else
						 {
						 ?>
						 <tr >
							<td><a href="teams_v3.php?team_id=<?php echo $kk['team_id']; ?>" style="text-decoration:none; color:#FFFFFF;"><?php echo $kk['team']; ?></a></td>
							<td class="center"><?php echo $kk['pts_diff']; ?></td>
							<td class="center"><?php echo ($kk_sign == -1 or $kk_sign == 0) ? '<img src="images_v3/arrowdown2.png" />' : '<img src="images_v3/arrowup1.png" />'; ?></td>
						  </tr>
						 <?php
						 }
						 $xcount++;
						}
						?>
						</tbody>				
						</table>
					</div>
					<div class="table2">
						<h2 class="title2">Hottest Teams <span class="r_pos">^Past Three Games</span></h2>
						<table cellspacing="0" cellpadding="0">
							<tr>
								<th width="80%">TEAM NAME</th>
								<th width="20%" class="center">WIN %</th>
							</tr>
							<tbody>
							  <?php
							  $xcount =1;
							foreach ($g as $gk) {
							if($xcount % 2==0)
						    {
							?>
							  <tr class="nth">
								<td><a href="teams_v3.php?team_id=<?php echo $gk['team_id']; ?>" style="text-decoration:none; color:#FFFFFF;"><?php echo $gk['team']; ?></a></td>
								<td class="center"><?php echo $gk['win_pct']; ?></td>
							  </tr>
							   <?php
							 } 
							 else
							 {
							 ?>
							 <tr >
								<td><a href="teams_v3.php?team_id=<?php echo $gk['team_id']; ?>" style="text-decoration:none; color:#FFFFFF;"><?php echo $gk['team']; ?></a></td>
								<td class="center"><?php echo $gk['win_pct']; ?></td>
							  </tr>
							 <?php
							 }
							 $xcount++;
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="clear"></div>
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

