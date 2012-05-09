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

extract($_REQUEST);

$sql1 = "select concat(year(a.game_date), '-', month(a.game_date)) as season, concat(b.player_fname, ' ', b.player_lname) as player_name, 
	sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as points, 
	sum(if(a.player_id=b.id, game_rebounds, 0)) as rebounds, 
	sum(if(a.player_id=b.id, game_assists, 0)) as assists, 
	sum(if(a.player_id=b.id, 1, 0)) as games_played, 
	c.team_name, c.id as team_id 
from bhleague.players_stats a, bhleague.players b , bhleague.teams c  
where b.team_id = c.id and weekday(a.game_date) = '{$gameday}' and b.id = '{$player}'  
group by month(a.game_date), b.id 
order by points desc, rebounds desc, assists desc;";

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
where b.team_id = c.id and weekday(a.game_date) = '{$gameday}' and a.player_id = '{$player}'  
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
$sql2 = "select rank, r.points, r.player_id from (
  select @rank:=@rank+1 as rank, pt.points, pt.player_id  
	from (
		select b.id as player_id, 
			sum(if(a.player_id=b.id, ((a.game_points_1*1) + (a.game_points_2*2) + (a.game_points_3*3)), 0)) as points 
		from bhleague.players_stats a, bhleague.players b  
		where weekday(a.game_date) = '{$gameday}'  
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
<script type="text/javascript" src="bhlcommon.js"></script>
<script type="text/javascript">
Ext.bhlcommondata.player_stats = <?php echo $player_store; ?>;
</script>
<script type="text/javascript" src="player.js"></script>
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
          <div class="box bg1">
            <h3><?php echo $row->player_name;?></h3>
            <div class="sub-box">
              <div class="tabs">
                <div class="clear"></div>
              </div>
              <div class="people">
                <div class="person first">
                  <div class="img"> <img src="images/no-profile.gif" /> </div>
                  <div class="detail">
                    <div class="txt-single">
                      <div class="f_right" style="float:left"> Scoring Rank # <?php echo $row2->rank; ?> </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <div class="logo">
                  <div class="img">
                    <div class="the-info"> <?php echo $row->position; ?> </div>
                    <img src="<?php echo ($row->logo <> NULL) ? $row->logo : 'images/no-profile.gif'; ?>" /> </div>
                </div>
                <div class="clear"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="f_right">
          <div id="playerInfoB">
            <h3 class="playerinfo">2012 Season Statistics </h3>
            <dl id="prastats">
              <dt>PPG</dt>
              <dd><?php echo @$row->ppg; ?> </dd>
              <dt>RPG</dt>
              <dd><?php echo @$row->rpg; ?> </dd>
              <dt>APG</dt>
              <dd><?php echo @$row->rpg; ?> </dd>
              <dt>GAMES</dt>
              <dd><?php echo @$row->games_played; ?> </dd>
              <dt class="pralast">POINTS</dt>
              <dd class="pralast"><?php echo @$row->points; ?> </dd>
            </dl>
            <div class="clear"></div>
            <div class="playerInfoStatsPlayerInfoBorders"> Email: <span class="playerInfoValuePlayerInfoBorders"><?php echo @$row->player_email; ?> </span> </div>
            <div class="playerInfoStatsPlayerInfoBorders"> Height: <span class="playerInfoValuePlayerInfoBorders"><?php echo @$row->player_height; ?> </span> </div>
            <div class="playerInfoStatsPlayerInfoBorders"> Weight: <span class="playerInfoValuePlayerInfoBorders"><?php echo @$row->player_weight; ?> </span> </div>
            <div class="playerInfoStatsPlayerInfoBorders"> Position: <span class="playerInfoValuePlayerInfoBorders"><?php echo @$row->position; ?> </span> </div>
            <br />
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <br />
      <div class="content-bottom">
        <div class="box">
          <h4><span>BHL</span> - PLAYER STATS BY SEASON</h4>
          <div id="grid-player_stats" class="sub-box"></div>
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
