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
		
		<link href='http://fonts.googleapis.com/css?family=Arvo:700,400italic,700italic,400' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css_v2/bebas/stylesheet.css" type="text/css"   charset="utf-8" />
		<link rel="stylesheet" href="css_v2/roboto/stylesheet.css" type="text/css"  charset="utf-8" />	
		
		<link rel="stylesheet" href="css_v2/reset.css" type="text/css" media="screen" title="style" charset="utf-8" />
		<link rel="stylesheet" href="css_v2/style.css" type="text/css" media="screen" title="style" charset="utf-8" />
        <link rel="stylesheet" href="css_v2/photo.css" type="text/css" media="screen" title="style" charset="utf-8" />
		<link rel="stylesheet" href="css_v2/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
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
		<script src="js_v2/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>		
		<!-- ** CSS ** -->
		<!-- base library-->
		<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />     
		
		<!-- ExtJS library: base/adapter -->
		<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
		<!-- ExtJS library: all widgets -->
		<script type="text/javascript" src="extjs/ext-all.js"></script>    
		<script type="text/javascript" src="bhlcommon.js"></script>
		<script type="text/javascript" charset="utf-8">
		  $(document).ready(function(){
		    $("a[rel^='prettyPhoto']").prettyPhoto({
				social_tools: ''
			});
		  });
		
		</script>
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
						<a href="photo-nick.php" target="_self">PHOTOS</a>
						<a href="signup.php">SIGN UP</a>
						
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			
			<div class="col col3-full">
				<div class="table1" style="background:none">
					<h2 class="title2">Photos</h2>
					<div id="grid-player_stats">                        
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
							}?>		
															
						</div>
					</div>
				</div>			
			</div>
			
			<div class="clear"></div>

	</div>
	<div class="padding_clear"></div>	
	<?php include('footer.php'); ?>
	</body>
    </html>