<?php
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
require_once('wepay_config.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
//extract($_REQUEST);
$array = array();
$opt_pos= "<select name='position' id='position' style='width:215px;'>";

$sql = "SELECT `id`, team_name FROM bhleague.teams";

//echo $sql;exit;

$rs = $db->query($sql);


while ($row = $rs->fetch_assoc())
{
	$x ="'".$row['id']."'";
	$y = "'".$row['team_name']."'";
    $array[] = "[ {$x}, {$y} ]";

}
$z = ' [' . implode(', ', $array) . ']';
//echo $z;
//$p_arr = array();
$sql = "SELECT `id`, `position_abbv` FROM `bhleague`.`positions`";

	$rs1 = $db->query($sql);   
	while ($row1 = $rs1->fetch_array())  
	{	
	 	$opt_pos .= "<option value =".$row1[0].">".$row1[1]."</option>";
	}	
$opt_pos .= "</select>";	

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
		<!--<link rel="stylesheet" href="css_v2/style.css" type="text/css" media="screen" title="style" charset="utf-8" />-->
        <link rel="stylesheet" href="css_v2/signup.css" type="text/css" media="screen" title="style" charset="utf-8" />
		
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
		<script type="text/javascript" src="bhlcommon.js"></script>
		<script type="text/javascript" src="DataView-more.js"></script>
		<script type="text/javascript" src="data-view.js"></script>
	<script type="text/javascript">
					
	</script>
	<!--<script type="text/javascript" src="player.js"></script>-->
    <style type="text/css">
		.title_playerx{background:#00A9D3;font-size:18px;font-family:"Arvo",'Arial';font-weight:bold;color:white;letter-spacing:-1px;padding:0px 10px;text-shadow:0px -1px 1px #000;}	
		.title_profile{background:#00A9D3;font-size:18px;font-family:"Arvo",'Arial';font-weight:bold;color:white;letter-spacing:-1px;padding:0px 10px;text-shadow:0px -1px 1px #000;}			

		.widget-title {
		/*color:#333333 !important;
		text-decoration:underline !important;*/
		}
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
						<a href="signup.php">PAYMENTS</a>
						
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			
		<div id="content" class="dark-c">
			<div class="col col3-full">
				<div class="table1">
					<h2 class="title2">BH League Photos</h2>
					<div id="grid-dataview" class="sub-box">
                        

                        <div id="div_wepay_tue" style="float:left;display:none;position:inherit;margin-top:20px;margin-left:20px">
							<!--<a class="wepay-widget" href="https://stage.wepay.com/events/view/83508?widget_type=tickets&widget_ticket_id=83508&widget_auth_token=10983bc2506d465146dc3e778a93106c1980fba1&widget_show_description=1&widget_show_tickets=1">Buy Tickets for BH League BasketBall Tournament<script id="wepay-widget_script" type="text/javascript" src="https://stage.wepay.com//js/widget.wepay.js"></script></a>-->
                        </div>
                        <div id="div_wepay_sat" style="float:left;display:none;position:inherit;margin-top:20px;margin-left:20px">
                        	saturday
                        </div>      
                        <div id="div_wepay_sun" style="float:left;display:none;position:inherit;margin-top:20px;margin-left:20px">
							<!--<a class="wepay-widget" href="https://stage.wepay.com/events/view/83508?widget_type=tickets&widget_ticket_id=83508&widget_auth_token=10983bc2506d465146dc3e778a93106c1980fba1&widget_show_description=1&widget_show_tickets=1">Buy Tickets for BH League BasketBall Tournament<script id="wepay-widget_script" type="text/javascript" src="https://stage.wepay.com//js/widget.wepay.js"></script></a>-->
                        </div>    
                        <div style="clear:both;"></div>                                            
					</div>
				</div>			
			</div>
			
			<div class="clear"></div>
		</div>
		<?php include('footer.php'); ?>
	</div>
	</body>
    </html>