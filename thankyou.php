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
	<script type="text/javascript">
		Ext.namespace('Ext.bhl');
		Ext.Element.prototype.setClass = function(cls,add_class){
		  add_class ? this.addClass(cls) : this.removeClass(cls)
		}	
		Ext.bhl.wepay_link = "<?php echo WEPAY_DOMAIN.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM.WEPAY_REDIRECT_URI; ?>";
		function toggle_wepay(box) {
			try{
				switch (box) {
					
					case '1':
						Ext.get('div_wepay_tue').setStyle('display', 'inline');
						Ext.get('div_wepay_sat').setStyle('display', 'none');
						Ext.get('div_wepay_sun').setStyle('display', 'none');
						break;
					case '5':
						Ext.get('div_wepay_tue').setStyle('display', 'none');
						Ext.get('div_wepay_sat').setStyle('display', 'inline');
						Ext.get('div_wepay_sun').setStyle('display', 'none');
						break;
					case '6':
						Ext.get('div_wepay_tue').setStyle('display', 'none');
						Ext.get('div_wepay_sat').setStyle('display', 'none');
						Ext.get('div_wepay_sun').setStyle('display', 'inline');						
						break;
				}
			}catch(e){
				alert("Error: "+e);
			}
		}	
		Ext.bhl.proc = function(){
				//alert(frmaaddNotification.getForm().getValues());
			
				//var frmItem = ['fname','lname','email','height','weight','league','jersey_size','jersey_no','phone','referrer','occupation']; 
				var frmItem = ['fname','lname','email','league'];// for testing
				var ItemErr = {"fname" : "First Name is Blank.", "lname" : "Last Name is Blank.", "email" : "Email is Blank.", "league" : "Please select a League."};
				
				
				for(var i=0; i<frmItem.length; i++) {
					var item = frmItem[i];
					 if(item == 'league'){
						if(Ext.get(item).getValue() == "0"){
							alert(ItemErr['league']);
							return;
							break;
						}
					 }else if(Ext.get(item).getValue().trim() == ''){
						alert(ItemErr[item]);
						return;
						break;
					 }
				}		

				Ext.Ajax.request({
		
					url: 'signup_callback.php?action=insert',
		
					params: Ext.lib.Ajax.serializeForm('frmaaddNotification'),
		
					success: function(response){
		
						var result = response.responseText;
						alert(result);
						var jsonData = Ext.util.JSON.decode(result);
						if(jsonData.status == "success"){
							alert(jsonData.status+" <--> "+jsonData.id);
							window.location = Ext.bhl.wepay_link+"&pid="+jsonData.id;
						}else
							alert(jsonData.status+" <--> "+jsonData.error);
						//Ext.setValues('grid-next_game') = result;
		
						//Ext.fly('grid-next_game').dom.innerHTML = result;
		
					}
		
				});
		
		}			
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
						<a href="payments_v2.html">PAYMENTS</a>
						
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			
		<div id="content" class="dark-c">
			<div class="col col3-full">
				<div class="table1">
					<h2 class="title2">Thank You</h2>
					<p style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; text-align:center;">
					<br />
					
						Thank you for patronizing BHLeague.com charity drive</p>
						<br />
						
					<p style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:200; text-align:center;">	
					Your commitment to helping BH League in our mission to help the less unfortunate is sincerely appreciated.
					<br />
					Please expect an email within 24 hours.
					<div style="clear:none; height:30px;"></div>
					
					</p>
						
					
					
				</div>			
			</div>
			
			<div class="clear"></div>
		</div>
		<?php include('footer.php'); ?>
	</div>
	</body>
    </html>