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
$pg = $r = array();

extract($_REQUEST);

$m = strtoupper($m);

switch ($m) {
	default:
	case 'PPG':
		$m_sql = "sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0))";
		$mm = 'Points';
		$m_class = '<a href="index.php?m=PPG" class="on">POINTS</a> | <a href="index.php?m=RPG">REBOUND</a> | <a href="index.php?m=APG">ASSIST</a>';
		break;
	case 'APG':
		$m_sql = "sum(if(a.player_id=b.id, game_assists, 0))";
		$mm = 'Assists';
		$m_class = '<a href="index.php?m=PPG">POINTS</a> | <a href="index.php?m=RPG">REBOUND</a> | <a href="index.php?m=APG" class="on">ASSIST</a>';
		break;
	case 'RPG':
		$m_sql = "sum(if(a.player_id=b.id, game_rebounds, 0))";
		$mm = 'Rebounds';
		$m_class = '<a href="index.php?m=PPG">POINTS</a> | <a href="index.php?m=RPG" class="on">REBOUND</a> | <a href="index.php?m=APG">ASSIST</a>';
		break;
}

$sql = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
	c.team_name, 
	{$m_sql} as score, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
where b.team_id = c.id and weekday(a.game_date) = '{$gameday}'   
group by b.id 
order by score desc) as ave 
order by pg desc limit 3;";
		
if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$pg[] = $row;
		}
	}
	$rs->close();
}

$sql = "select rank.team, round(rank.pts_for - rank.pts_against, 0) as pts_diff from (
select b.team_name as team, 
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
<script type="text/javascript" src="index_main.js"></script>
<script type="text/javascript" src="bhlcommon.js"></script>
</head>
<body>
<div id="header">
  <div class="wrap">
    <h1 class="logo"><a href="index.php"><img src="images/logo.png" /></a></h1>
    <div class="menu"><a href="index.php" class="first">HOME</a> <a href="standings.html">STANDINGS</a> <a href="schedule.html">SCHEDULES</a> <a href="league_leaders.html">LEAGUE LEADERS</a> <a href="rosters.html" class="last">ROSTERS</a>
      <div class="clear"></div>
    </div>
  </div>
</div>
<div id="header2">
  <div class="wrap">
  	<div id="game_league_id"><input type="text" id="local-gamedays" size="20"/></div>
    <div class="clear"></div>
  </div>
</div>
<div id="content">
  <div class="wrap1 bg-white">
    <div class="wrap">
      <div class="content-top">
        <div class="f_left">
          <div class="box bg1">
            <h3>BHL BASKETBALL LEADERS</h3>
            <div class="sub-box">
              <div class="tabs"> <a href="rosters.html" class="f_right">VIEW SCORE</a> <?php echo $m_class; ?>
                <div class="clear"></div>
              </div>
              <div class="people">
              <?php
			  $first = true;
			  foreach ($pg as $k) {
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
                    <img src="images/no-profile.gif" /> </div>
                  <div class="detail">
                    <div class="f_left"> <a href="player.php?player=<?php echo $k['player_id']; ?>" class="f_left" style="text-decoration:none; color:#FFF"><?php echo $k['player_name']; ?></a> </div>
                    <div class="f_right"> <?php echo @number_format($k['pg'], 1); ?> <?php echo $m; ?> </div>
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
          <div class="box">
            <h3>BHL BASKETBALL SCHEDULE</h3>
            <h4><span><?php echo $s['game_dayname']; ?></span> <?php echo $s['game_date']; ?></h4>
            <div id="grid-next_game" class="sub-box"></div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="content-bottom">
        <div class="f_left">
          <div class="box">
            <h3>BHL BASKETBALL STANDINGS</h3>
            <h4><span>2012</span> - Night League</h4>
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
                    <td><?php echo $kk['team']; ?></td>
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
