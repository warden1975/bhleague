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

$sql = "select date_format(game_date, '%M %e') as game_date, dayname(game_date) as game_dayname from bhleague.`schedule` where game_date >= now() group by game_date limit 1;";

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

$db->close();
$db = NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BHLeague</title>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" title="style" charset="utf-8" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="style" charset="utf-8" />
<link href='http://fonts.googleapis.com/css?family=Coda|Open+Sans:400italic,600italic,400,600,700' rel='stylesheet' type='text/css'>
<!--[if IE 6]>
    <link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
<!--[if IE 7]>
    <link rel="stylesheet" href="css/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
<script type="text/javascript" charset="utf-8" src="js/jq.js"></script>
<script type="text/javascript" charset="utf-8" src="js/placeholder.js"></script>
<script type="text/javascript" charset="utf-8" src="js/main.js"></script>

<!-- ** CSS ** -->
<!-- base library -->
<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
<!-- overrides to base library -->
<!-- page specific -->
<!--link rel="stylesheet" type="text/css" href="extjs/examples/shared/examples.css" /-->
<link rel="stylesheet" type="text/css" href="extjs/examples/grid/grid-examples.css" />
<style type=text/css>
/* style rows on mouseover */
    .x-grid3-row-over .x-grid3-cell-inner {
	font-weight: bold;
}
/* style for the "buy" ActionColumn icon */
    .x-action-col-cell img.buy-col {
	height: 16px;
	width: 16px;
	background-image: url(extjs/examples/shared/icons/fam/accept.png);
}
/* style for the "alert" ActionColumn icon */
    .x-action-col-cell img.alert-col {
	height: 16px;
	width: 16px;
	background-image: url(extjs/examples/shared/icons/fam/error.png);
}
</style>

<!-- ** Javascript ** -->
<!-- ExtJS library: base/adapter -->
<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
<!-- ExtJS library: all widgets -->
<script type="text/javascript" src="extjs/ext-all.js"></script>
<!-- Overrides to base library -->
<script type="text/javascript" src="index_main_test.js"></script>
<script type="text/javascript" src="bhlcommon.js"></script>
<script type="text/javascript">
Ext.bhlcommondata.month_games = <?php echo $month_games; ?>;
function togglebox_leaders(box) {
	var bp_el = Ext.get('box_point');
	var br_el = Ext.get('box_rebound');
	var ba_el = Ext.get('box_assist');
	
	switch (box) {
		default:
		case 1:
			bp_el.show();
			br_el.setVisibilityMode(Ext.Element.DISPLAY);
			br_el.hide();
			ba_el.setVisibilityMode(Ext.Element.DISPLAY);
			ba_el.hide();
			break;
		case 2:
			bp_el.setVisibilityMode(Ext.Element.DISPLAY);
			bp_el.hide();
			br_el.show();
			ba_el.setVisibilityMode(Ext.Element.DISPLAY);
			ba_el.hide();
			break;
		case 3:
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
<div id="fb-root"></div>
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
<div id="header">
  <div class="wrap">
    <h1 class="logo"><a href="index.php"><img src="images/logo.png" /></a></h1>
    <div class="menu"><a href="index.php" class="first">HOME</a> <a href="standings.html">STANDINGS</a> <a href="schedule.html">SCHEDULES</a> <a href="league_leaders.html">LEAGUE LEADERS</a> <a href="rosters.php">ROSTERS</a> <a href="payments.html" class="last">PAYMENTS</a>
      <div class="clear"></div>
    </div>
  </div>
</div>
<div id="header2">
  <div class="wrap">
	<div class="f_right">
    	<div class="fb-like" data-href="http://www.bhleague.com/" data-send="false" data-width="250" data-colorscheme="dark" data-show-faces="false" data-font="lucida grande"></div>
	</div>
    <div id="game_league_id">
      <input type="text" id="local-gamedays" size="20"/>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div id="content">
<div class="wrap1 bg-white">
<div class="wrap">
  <div class="content-top">
    <div class="f_left">
      <div id="box_point" class="box bg1" style="margin-bottom:10px;">
        <h3>BHL BASKETBALL LEADERS</h3>
        <div class="sub-box">
          <div class="tabs"> <a href="rosters.php" class="f_right">VIEW SCORE</a> <a href="#" class="on">POINTS</a> | <a href="#" onclick="togglebox_leaders(2);">REBOUND</a> | <a href="#" onclick="togglebox_leaders(3);">ASSIST</a>
            <div class="clear"></div>
          </div>
          <div class="people">
            <?php
			  $first = true;
			  foreach ($ppg as $k) {
			  if ($first) {
			  ?>
            <div class="person first">
              <?php
			  	$first = false;
			  } else {
              ?>
              <div class="person">
                <?php
			  }
              ?>
                <div class="img">
                  <div class="the-info"> <?php echo $k['team_name']; ?> </div>
                  <img src="images/no-profile.gif" />
                </div>
                <div class="detail">
                  <div class="f_left"> <a href="player.php?player=<?php echo $k['player_id']; ?>" class="f_left" style="text-decoration:none; color:#FFF"><?php echo $k['player_name']; ?></a> </div>
                  <div class="f_right"> <?php echo @number_format($k['pg'], 1); ?> PPG </div>
                  <div class="clear"></div>
                </div>
              </div>
              <?php
			  }
			  ?>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        
        <div id="box_rebound" class="box bg1" style="margin-bottom:10px; display:none;">
        <h3>BHL BASKETBALL LEADERS</h3>
        <div class="sub-box">
          <div class="tabs"> <a href="rosters.php" class="f_right">VIEW SCORE</a> <a href="#" onclick="togglebox_leaders(1);">POINTS</a> | <a href="#" class="on">REBOUND</a> | <a href="#" onclick="togglebox_leaders(3);">ASSIST</a>
            <div class="clear"></div>
          </div>
          <div class="people">
            <?php
			  $first = true;
			  foreach ($rpg as $k) {
			  if ($first) {
			  ?>
            <div class="person first">
              <?php
			  	$first = false;
			  } else {
              ?>
              <div class="person">
                <?php
			  }
              ?>
                <div class="img">
                  <div class="the-info"> <?php echo $k['team_name']; ?> </div>
                  <img src="images/no-profile.gif" />
                </div>
                <div class="detail">
                  <div class="f_left"> <a href="player.php?player=<?php echo $k['player_id']; ?>" class="f_left" style="text-decoration:none; color:#FFF"><?php echo $k['player_name']; ?></a> </div>
                  <div class="f_right"> <?php echo @number_format($k['pg'], 1); ?> RPG </div>
                  <div class="clear"></div>
                </div>
              </div>
              <?php
			  }
			  ?>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        
        <div id="box_assist" class="box bg1" style="margin-bottom:10px; display:none;">
        <h3>BHL BASKETBALL LEADERS</h3>
        <div class="sub-box">
          <div class="tabs"> <a href="rosters.php" class="f_right">VIEW SCORE</a> <a href="#" onclick="togglebox_leaders(1);">POINTS</a> | <a href="#" onclick="togglebox_leaders(2);">REBOUND</a> | <a href="#" class="on">ASSIST</a>
            <div class="clear"></div>
          </div>
          <div class="people">
            <?php
			  $first = true;
			  foreach ($apg as $k) {
			  if ($first) {
			  ?>
            <div class="person first">
              <?php
			  	$first = false;
			  } else {
              ?>
              <div class="person">
                <?php
			  }
              ?>
                <div class="img">
                  <div class="the-info"> <?php echo $k['team_name']; ?> </div>
                  <img src="images/no-profile.gif" />
                </div>
                <div class="detail">
                  <div class="f_left"> <a href="player.php?player=<?php echo $k['player_id']; ?>" class="f_left" style="text-decoration:none; color:#FFF"><?php echo $k['player_name']; ?></a> </div>
                  <div class="f_right"> <?php echo @number_format($k['pg'], 1); ?> APG </div>
                  <div class="clear"></div>
                </div>
              </div>
              <?php
			  }
			  ?>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        
      </div>
      <div class="f_right">
        <div class="box" style="margin-bottom:10px">
          <h4><span>BHL</span> - SCHEDULE <span><?php echo @$s['game_dayname']; ?></span> <?php echo @$s['game_date']; ?></h4>
          <div id="grid-next_game" class="sub-box"></div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="content-bottom">
        <div class="f_left">
          <div class="box">
            <h4><span>BHL</span> - BASKETBALL STANDINGS</h4>
            <div id="grid-standings" class="sub-box"></div>
          </div>
        </div>
        <div class="f_right">
          <div class="box">
            <h3>POWER RANKINGS</h3>
            <div class="sub-box">
              <table class="greenbox">
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
                    <td><?php echo ($kk_sign == -1 or $kk_sign == 0) ? '<img src="images/down.png" />' : '<img src="images/up.png" />'; ?></td>
                  </tr>
                  <?php
				}
				?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3"><a href="standings.html">View complete rankings</a></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <br />
      <div class="content-bottom">
 		<div class="f_left">
          <div class="box" style="margin-bottom:10px">
            <h4><span>BHL</span> - TOP 20 PLAYERS</h4>
            <div id="grid-top20" class="sub-box"></div>
          </div>
        </div>
        <!--div class="f_right">
          <div class="box disqus" style="margin-bottom:10px; line-height: 12px;">
            <h4><span>BHL</span> - WALL COMMENTS</h4>
            <div class="sub-box">
              <div id="disqus_thread"></div>
              <script type="text/javascript">
                    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                    var disqus_shortname = 'bhleague'; // required: replace example with your forum shortname
  
                    /* * * DON'T EDIT BELOW THIS LINE * * */
                    (function() {
                        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                    })();
                </script>
              <noscript>
              Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a>
              </noscript>
              <a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a> </div>
          </div>
        </div-->
		<div class="clear"></div>
      </div>
      <br />
      <div class="content-bottom">
        <div class="f_left">
          <div class="box">
            <h4><span>BHL</span> - HOTTEST PLAYERS  <span>* Previous two games</span></h4>
            <div id="grid-top10" class="sub-box"></div>
          </div>
        </div>
        <div class="f_right">
          <div class="box">
            <h3>HOTTEST TEAMS</h3>
            <div class="sub-box">
              <table class="greenbox">
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
        </div>
        <div class="clear"></div>
      </div>
      <br />
    </div>
  </div>
</div>
<div id="footer">
  <div class="wrap">
    <div class="f_left"> &copy; COPYRIGHT 2012 BH LEAGUE.com </div>
    <div class="f_right"> <a href="index.php" class="first">HOME</a> <a href="#">MOBILE</a> <a href="#">TERMS OF USE</a> <a href="#">PRIVACY POLICY</a> <a href="#">FAQ</a> <a href="#" class="last">CONTACT</a> </div>
    <div class="clear"></div>
  </div>
</div>
</body>
