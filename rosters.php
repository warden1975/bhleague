<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

$action = NULL;
extract($_REQUEST);
$d = array();

#$sql = "SELECT `id`,team_name,mini_logo from bhleague.teams ";

$sql = "select a.id, b.team_name, b.mini_logo  
from 
(
select team_id as id from bhleague.players where team_id <> 0 and 1 = '{$gameday}' group by team_id union 
select team_id2 as id from bhleague.players where team_id2 <> 0 and 5 = '{$gameday}' group by team_id2 union 
select team_id3 as id from bhleague.players where team_id3 <> 0 and 6 = '{$gameday}' group by team_id3
) a 
inner join bhleague.teams b 
on a.id = b.id;";

$result = $db->query($sql);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Rosters</title>
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
<!--link rel="stylesheet" type="text/css" href="extjs/shared/examples.css" /-->
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
<!-- overrides to base library -->
<script type="text/javascript" src="rosters.js"></script>
<script type="text/javascript" src="bhlcommon.js"></script>
</head>

<body>
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
        <div class="box"> 
          <script type="text/javascript">
			var teams = new Array();
			var teams_container = new Array();
          </script>
          <?php
		  while($row = $result->fetch_array()){
			@$teamcontainer ="grid-rosters".@$row['id'];
		  ?>
			<script type="text/javascript" >
                teams.push(<?php echo @$row['id']; ?>);
                teams_container.push("<?php echo @$teamcontainer ; ?>");
            </script>
            <h3><?php echo @$row['team_name']; ?>&nbsp;&nbsp;&nbsp;<img src="<?php echo @$row['mini_logo']; ?>" align="absmiddle"  /></h3>
            <!-- <h4><span>2012</span> Night League</h4>-->
            <div class="sub-box">
              <div id="<?php echo @$teamcontainer; ?>"></div>
            </div>
		  <?php } ?>
        </div>
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
</html>
