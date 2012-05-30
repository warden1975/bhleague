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
	<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
	
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
    <!-- base library
      -->   
    
    <!-- ExtJS library: base/adapter -->
    <script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="extjs/ext-all.js"></script>    
	<script type="text/javascript" src="rosters.js"></script>
	<script type="text/javascript" src="bhlcommon.js"></script>
	   
    
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
		<div id="content" class="dark-c">
				<div class="padding_clear"></div>
				  <div class="wrap1 bg-white">
					<div class="wrap">
					  <div class="col">
						<div class="table1">
						  <h2 class="title2">Team Rosters</h2>
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
								<h3 style="background-color:#4D4D4D;"><font color="#FF9933"><?php echo @$row['team_name']; ?></font>&nbsp;&nbsp;&nbsp;<img src="<?php echo @$row['mini_logo']; ?>" align="absmiddle"   /></h3>
								<!-- <h4><span>2012</span> Night League</h4>-->
								<div class="sub-box">
								  <div id="<?php echo @$teamcontainer; ?>" ></div>
								</div>
							  <?php } ?>
						  </div>
						</div>
					  </div>
					</div>
				</div>                            
			<div class="padding_clear"></div>
			<?php include('footer.php'); ?>
			<!--<div id="footer">
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
		</div>-->
	</div>
		
		
	</div>
  </body>
</html>

