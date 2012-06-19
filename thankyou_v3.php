<?php
	error_reporting(E_ERROR);
	require_once('/home/bhleague/public_html/admin/class/db.cls.php');
	$db = new DB_Connect(MYSQL_INTRANET, true);
	require_once('wepay_config.php');
	
	if(!isset($_REQUEST['checkout_id']))
	{
	header("location: index.php");
	die;
	}
	$fp3 = fopen('log.txt','a+');						###### REMOVE THIS AFTER DEBUG ######
	fwrite($fp3, "\n NEW ACCOUNT \n");	###### REMOVE THIS AFTER DEBUG ######
		
	$code = $user_id = $access_token = $token_type = '';
	$code = (isset($_REQUEST['code']))?trim($_REQUEST['code']):'';
	
	$user_id = (isset($_REQUEST['user_id']))?trim($_REQUEST['user_id']):'';
	$access_token = (isset($_REQUEST['access_token']))?trim($_REQUEST['access_token']):'';
	$token_type = (isset($_REQUEST['token_type']))?trim($_REQUEST['token_type']):'';
	
	$sql = "SELECT * from `bhleague`.`wepay_accounts` where wepay_checkout_id ='".$_REQUEST['checkout_id']."'";
	fwrite($fp3, "THANK YOU QUERY 1--> " . $sql ."\n\n");
	$rec = $db->query($sql);
	if($rec->num_rows>0)
	{
		$row = $rec->fetch_assoc();
				
			
			$sql = "SELECT `id`,player_fname,player_lname,email,league from `bhleague`.`players` where (player_fname='".$row['last_name']."' AND player_lname ='".$row['first_name']."') OR email ='".$row['wepay_email']."' LIMIT 1";
			fwrite($fp3, "THANK YOU QUERY 2--> " . $sql ."\n\n");
			$rc = $db->query($sql);
			if($rc->num_rows>0)
			{
				$rowx = $rc->fetch_assoc();
				$sql ="UPDATE `bhleague`.`wepay_accounts` SET player_id ='".$rowx['id']."',first_name ='".$rowx['player_fname']."',last_name='".$rowx['player_lname']."'  WHERE id =".$row['id'];
				fwrite($fp3, "THANK YOU UPDATE QUERY 1--> " . $sql ."\n\n");
				$db->query($sql);
				
				$sql ="UPDATE `bhleague`.`players` SET status = 1 WHERE id =".$rowx['id']."";
				fwrite($fp3, "THANK YOU UPDATE QUERY 3--> " . $sql ."\n\n");
				$db->query($sql);
				
			$league = $rowx['league'];
			if($league=='1')
			 {
				$playoff ="Tuesday";
			 }
			 else if($league=='5')
			 {
				$playoff ="Saturday";
			 }
			 else if($league=='6')
			 {
				$playoff ="Saturday";
			 }
			
			 $to = "fortunato@golivemobile.com,rainier.lee@golivemobile.com";
			 $headers  = 'MIME-Version: 1.0' . "\r\n";
			 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			 $headers .= "From: noc@bhleague.com\r\nReply-To: noc@bhleague.com";
			 $subject = "Check Out For ".$playoff. " League";
			 $message = "Checkout complete for : <br>";
			 $message .= "First Name: ".$rowx['player_fname']."<br>";
			 $message .="Last Name: ".$rowx['player_lname']."<br>";
			 $message .="Email: ".$rowx['email']."<br>";
			 $message .="League: ".$playoff."<br>";
			 $mail_sent = @mail( $to, $subject, $message, $headers ); 
			 $mail_sent ? "Mail sent" : "Mail delivery failed";
							
			}
			
		
	}
	
	
	
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
	<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="extjs/ext-all.js"></script>
	
    <!--[if IE 6]>
    <link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->    
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->

	<script type="text/javascript" charset="utf-8" src="js_v3/jq.js"></script>
	<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
	<script type="text/javascript" charset="utf-8" src="js_v3/placeholder.js"></script>	
	<script type="text/javascript" charset="utf-8" src="js_v3/main.js"></script>	
     <script type="text/javascript" src="bhlcommon_v3.js"></script>
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
					<h2 class="title">Thank You</h2>
					<div class="about-page">
						<p style="font-family:Montserrat',sans-serif; font-size:18px; font-weight:800; text-align:center;">
					<br />
					
						Thank you for patronizing BHLeague.com charity drive</p>
						<br />
						
					<p style="font-family:Montserrat',sans-serif; font-size:18px; font-weight:800; text-align:center;">	
					Your commitment to helping BH League in our mission to help the less unfortunate is sincerely appreciated.
					<br />
					Please expect an email within 24 hours.
					<div style="clear:none; height:30px;"></div>
					
					</p>																							
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