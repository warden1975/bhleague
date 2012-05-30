<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

#define('DB_CLS', 'admin/class/db.cls.php');		# LOCAL CONFIG
#define('COMMON', 'bhlcommon.php');				# LOCAL CONFIG


require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

$m = 'ppg';
$mm = 'Points';
$pg = $r = $g = array();
$ppg = $apg = $rpg = array();

$month_games = get_month_games();

### NEW VARS ###
	$rimage[0] = "1st.png";
	$rimage[1] = "2nd.png";
	$rimage[2] = "3rd.png";
### NEW VARS ###						

$sql_ppg = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name, 
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
$sql_rpg = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name, 
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

$sql_apg = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name, 
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

$sql = "select rank.team, round(rank.pts_for - rank.pts_against, 0) as pts_diff, rank.team_id from (
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

$sql = "select date_format(game_date, '%M %e, %Y') as game_date, dayname(game_date) as game_dayname from bhleague.`schedule` where game_date >= now() group by game_date limit 1;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		$s = $rs->fetch_assoc();
	}
	$rs->close();
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
extract($_GET);
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
	<script type="text/javascript" src="index_main_v2.js"></script>
    <script type="text/javascript" src="bhlcommon_v2.js"></script>
    <script language="javascript">
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

	<!--<div id="grid-top10"></div>
	<div id="grid-next_game"></div>
	<div id="grid-top20"></div>
	<div id="grid-standings"></div>-->            
    
	<div class="container">
		<div id="header">
			<h1 class="logo"><a href="#"><img src="images/logo_v2.png" /></a></h1>
			<div class="wrap">
				<div class="menu">
					<img src="images/banner-left.png" class="b_left" />
					<img src="images/banner-right.png" class="b_right" />				
					<div class="menu-link">
						<select>
							<option>Select</option>
						</select>
					
						<a href="index_v2.php" target="_self">HOME</a>
						<a href="standings_v2.html" target="_self">STANDINGS</a>
						<a href="schedule_v2.html" target="_self">SCHEDULES</a>
						<a href="#">LEAGUE LEADERS</a>
						<a href="#">ROSTERS</a>
						<a href="#">PAYMENTS</a>
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			<h2 class="title-full">BHL Basketball Leaders</h2>
			<div class="col1 col">				
				<div class="sub"><a id="a_box_point" href="#" class="on" onclick="togglebox_leaders(1);">POINTS</a> <a id="a_box_rebound" href="#" onclick="togglebox_leaders(2);">REBOUND</a> <a id="a_box_assist" href="#" onclick="togglebox_leaders(3);">ASSIST</a></div>
				<div class="padding_clear"></div>
                <div id="box_point">
                    <div class="people">
                        <ul>
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
                                    <h5><?php echo strtoupper($team_name); ?></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <?php echo $player_name; ?>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
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
                <div id="box_rebound" style="display:none;">
                    <div class="people">
                        <ul>
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
                                    <h5><?php echo strtoupper($team_name); ?></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <?php echo $player_name; ?>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
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
            	</div>   <!--<div id="box_rebound" >-->  
                <div id="box_assist" style="display:none;">
                    <div class="people">
                        <ul>
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
                                    <h5><?php echo strtoupper($team_name); ?></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <?php echo $player_name; ?>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
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
            	</div>   <!--<div id="box_assist" >-->                            
			</div>
			<div class="col1-r col">
				<div class="sub"><a id="a_box_point" href="#" class="on" onclick="togglebox_leaders(1);">POINTS</a> <a id="a_box_rebound" href="#" onclick="togglebox_leaders(2);">REBOUND</a> <a id="a_box_assist" href="#" onclick="togglebox_leaders(3);">ASSIST</a></div>
				<div class="padding_clear"></div>
                <div id="box_point">
                    <div class="people">
                        <ul>
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
                                    <h5><?php echo strtoupper($team_name); ?></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <?php echo $player_name; ?>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
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
                <div id="box_rebound" style="display:none;">
                    <div class="people">
                        <ul>
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
                                    <h5><?php echo strtoupper($team_name); ?></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <?php echo $player_name; ?>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
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
            	</div>   <!--<div id="box_rebound" >-->  
                <div id="box_assist" style="display:none;">
                    <div class="people">
                        <ul>
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
                                    <h5><?php echo strtoupper($team_name); ?></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <?php echo $player_name; ?>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
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
            	</div>   <!--<div id="box_assist" >-->                            
			</div>


			<div class="padding_clear"></div>
		</div>
		<div id="content" class="dark-c">
			<div class="col col3">
				<div class="table1">
					<h2 class="title2">Team Standings</h2>
                    <div id="grid-standings" class="sub-box"></div>
				</div>
				<div class="table1">
					<h2 class="title2">Top 20 Players
						<div class="links">
							<a href="#" class="active">CURRENT <img src="images/arrowup3.png"></a>
							<a href="#">PAST TWO WEEKS <img src="images/arrowup3.png"></a>
						</div>
					</h2>
					 <div id="grid-top20" class="sub-box"></div>
				</div>
                <div class="table1">
					 <h2 class="title2">HOTTEST PLAYERS * Previous two games</h2>
					 <div id="grid-top10" class="sub-box"></div>
                </div>
			</div>
			<div class="col col4">
				<div class="table2">
					<h2 class="title2">Power Rankings</h2>
					<table cellspacing="0" cellpadding="0">
						<thead>
                  <tr>
                    <th>TEAM</th>
                    <th>RANK</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				foreach ($r as $kk) {
				$kk_sign = gmp_sign($kk['pts_diff']);
				?>
                  <tr>
                    <td><a href="teams.php?team_id=<?php echo $kk['team_id']; ?>" style="text-decoration:none; color:#FF0;"><?php echo $kk['team']; ?></a></td>
                    <td><?php echo $kk['pts_diff']; ?></td>
                    <td><?php echo ($kk_sign == -1 or $kk_sign == 0) ? '<img src="images/arrowdown2.png" />' : '<img src="images/arrowup1.png" />'; ?></td>
                  </tr>
                  <?php
				}
				?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3"><a href="standings.html" class="blue_link"><small>View complete rankings</small></a></td>
                  </tr>
                </tfoot>
					</table>
				</div>
				<div class="table2">
					<h2 class="title2">Hottest Teams</h2>
					<table cellspacing="0" cellpadding="0">
						<thead>
                  <tr>
                    <th>TEAM</th>
                    <th>WIN %</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				foreach ($g as $gk) {
				?>
                  <tr>
                    <td><a href="teams.php?team_id=<?php echo $gk['team_id']; ?>" style="text-decoration:none; color:#FF0;"><?php echo $gk['team']; ?></a></td>
                    <td><?php echo $gk['win_pct']; ?></td>
                  </tr>
                  <?php
				}
				?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2"><span style="color:#FFF;">* Past three games</span></td>
                  </tr>
                </tfoot>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="footer">
			<div class="f_left">
				&copy; COPYRIGHT 2012 BH LEAGUE.com
			</div>
			<div class="f_right">
				<a href="#">HOME</a>
				<a href="#">STANDINGS</a>
				<a href="#">SCHEDULES</a>
				<a href="#">LEAGUE LEADERS</a>
				<a href="#">ROSTERS</a>
				<a href="#">PAYMENTS</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
  </body>
</html>

