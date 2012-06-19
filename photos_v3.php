<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
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
	<link rel="stylesheet" href="css_v3/photo.css" type="text/css" media="screen" title="style" charset="utf-8" />
	<link rel="stylesheet" href="css_v3/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
	
    <!--[if IE 6]>
    <link rel="stylesheet" href="css_v3/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->    
    <!--[if IE 7]>
    <link rel="stylesheet" href="css_v3/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" href="css_v3/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
		<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />     
		
		<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="extjs/ext-all.js"></script>    
    <script type="text/javascript" src="bhlcommon_v3.js"></script>
		
	<script type="text/javascript" charset="utf-8" src="js_v3/jq.js"></script>
	<script type="text/javascript" charset="utf-8" src="js_v3/placeholder.js"></script>	
	<script type="text/javascript" charset="utf-8" src="js_v3/main.js"></script>	
	<script src="js_v3/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
			  $(document).ready(function(){
			    $("a[rel^='prettyPhoto']").prettyPhoto({
					social_tools: ''
				});
			  });

	</script>
  </head>
  <body>
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
				<div class="col">
					<h2 class="title">Photos</h2>
					<div id="player_photos">
							<?php
								$sql = "SELECT * from `bhleague`.`photos`";
								$rec = $db->query($sql);
								if($rec->num_rows>0)
								{
									while($row = $rec->fetch_assoc())
									{
								
								
							?>
							<div class="photo">
								<a href="<?php echo $row["url"]; ?>" rel="prettyPhoto[pp_gal]" title="<?php echo $row["title"]; ?>"><img src="<?php echo $row["url"]; ?>" /></a>
							</div>	
							<?php }
							} ?>		
						<div class="clear"></div>										
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