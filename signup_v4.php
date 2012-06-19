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
    
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400italic,400,700,700italic|Montserrat' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" href="css_v3/reset.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <link rel="stylesheet" href="css_v3/style.css" type="text/css" media="screen" title="style" charset="utf-8" />
	<link rel="stylesheet" href="css2/signup.css" type="text/css" media="screen" title="style" charset="utf-8" />
	<link rel="stylesheet" href="css2/button.css" type="text/css" media="screen" title="style" charset="utf-8" />
	<!--<link rel="stylesheet" href="css2/style.css" type="text/css" media="screen" title="style" charset="utf-8" />-->
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
	<script type="text/javascript" charset="utf-8" src="js_v3/cycle.js"></script>	
	 <script type="text/javascript" src="bhlcommon_v3.js"></script>
	 <!--<style type="text/css">

.css3btn-gray {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #050505;
	padding: 5px 20px;
	background: -moz-linear-gradient(
		top,
		#ffffff 0%,
		#ebebeb 50%,
		#dbdbdb 50%,
		#b5b5b5);
	background: -webkit-gradient(
		linear, left top, left bottom, 
		from(#ffffff),
		color-stop(0.50, #ebebeb),
		color-stop(0.50, #dbdbdb),
		to(#b5b5b5));
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border: 1px solid #949494;
	-moz-box-shadow:
		0px 1px 3px rgba(000,000,000,0.5),
		inset 0px 0px 2px rgba(212,212,212,1);
	-webkit-box-shadow:
		0px 1px 3px rgba(000,000,000,0.5),
		inset 0px 0px 2px rgba(212,212,212,1);
	text-shadow:
		0px -1px 0px rgba(196,196,196,0.2),
		0px 1px 0px rgba(255,255,255,1);
}

.css3btn-gray:hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #050505;
	padding: 5px 20px;
	background: -moz-linear-gradient(
		top,
		#ffffff 0%,
		#ebebeb 39%,
		#dbdbdb 10%,
		#b5b5b5);
	background: -webkit-gradient(
		linear, left top, left bottom, 
		from(#ffffff),
		color-stop(0.39, #ebebeb),
		color-stop(0.10, #dbdbdb),
		to(#b5b5b5));
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border: 1px solid #949494;
	-moz-box-shadow:
		0px 1px 3px rgba(000,000,000,0),
		inset 0px 0px 16px rgba(212,212,212,1);
	-webkit-box-shadow:
		0px 1px 3px rgba(000,000,000,0),
		inset 0px 0px 16px rgba(212,212,212,1);
	text-shadow:
		0px -1px 0px rgba(196,196,196,0.2),
		0px 1px 0px rgba(255,255,255,1);
}-->






</style>
	<script type="text/javascript">
		Ext.namespace('Ext.bhl');
		Ext.Element.prototype.setClass = function(cls,add_class){
		  add_class ? this.addClass(cls) : this.removeClass(cls)
		}	
		Ext.bhl.wepay_link = "<?php echo WEPAY_DOMAIN.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM.WEPAY_REDIRECT_URI; ?>";
		Ext.bhl.wepay_link_sat = "<?php echo WEPAY_DOMAIN.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM_SAT.WEPAY_REDIRECT_URI_SAT; ?>";
		Ext.bhl.wepay_link_sun = "<?php echo WEPAY_DOMAIN.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM_SUN.WEPAY_REDIRECT_URI_SUN; ?>";
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
				var ItemErr = {"fname" : "First Name cannot be blank .", "lname" : "Last Name cannot be blank.", "email" : "Email cannot be blank.", "league" : "Please select a League."};
				
				
				var b_err=0;
				for(var i=0; i<frmItem.length; i++) {
					var item = frmItem[i];
					//alert(Ext.get(item).getValue());
//					alert(i);
					 if(item == 'league')
					 {
						if(Ext.get(item).getValue() == "0")
						{
							b_err =1;
							alert(ItemErr['league']);
							return ;
							break;
						}
					 }
					 else if(Ext.get(item).getValue().trim() == '')
					 {
					 	b_err =1;
						alert(ItemErr[item]);
						return;
						break;
					 }
					 
				}		
				if(b_err==0)
				{
						mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Redirecting to Wepay Website. Please wait...'});
						mask.show();
		
				Ext.Ajax.request({
		
					url: 'signup_callback.php?action=insert',
		
					params: Ext.lib.Ajax.serializeForm('frmaaddNotification'),
		
					success: function(response){
		
						var result = response.responseText;
						//alert(result);
						var jsonData = Ext.util.JSON.decode(result);
						if(jsonData.status == "success" || jsonData.status == "duplicate"){
							//alert(jsonData.status+" <--> "+jsonData.id);
							
							var lgid = document.getElementById("league").value
							if(lgid=='1')
							{
							window.location = Ext.bhl.wepay_link+"&pid="+jsonData.id;
							}
							else if(lgid=='5')
							{
							window.location = Ext.bhl.wepay_link_sat+"&pid="+jsonData.id;
							}
							else if(lgid=='6')
							{
							window.location = Ext.bhl.wepay_link_sun+"&pid="+jsonData.id;
							}
							mask.hide();
						}
						else
						{
							mask.hide();
							alert(jsonData.status+" <--> "+jsonData.error);
						}
						//Ext.setValues('grid-next_game') = result;
		
						//Ext.fly('grid-next_game').dom.innerHTML = result;
		
					}
		
				});
				
				}
		
		}			
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
							<a href="#">LEAGUE SPONSORS</a>												
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
					<div class="table1">
						<h2 class="title2">Player Sign Up <small><div class="req">*</div> Required Fields </small></h2>
						<div id="grid-player_stats" class="sub-box">
	                        <div id="div_frm" class="form-reg" >
	                				<div class="form_left">
										<form name="frmaaddNotification" id="frmaaddNotification" method="post" action="signup_callback.php?action=insert&timestamp=<?php echo time() ?>">
                
										<table style="padding-top:20px; padding-bottom:20px;" >
											<tr>
												<td align="right" valign="middle" style="padding-left:10px; font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">First Name :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="fname" id="fname" size="32" height="50" />&nbsp;<font color="#FF0000">*</font></td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Last Name :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="lname" id="lname" size="32" height="50" style="" />&nbsp;<font color="#FF0000">*</font> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Email :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="email" id="email" size="32" />&nbsp;<font color="#FF0000">*</font> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Height :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="height" id="height" size="32"/> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Weight :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="weight" id="weight" size="32"/> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Position :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><?php echo $opt_pos; ?>&nbsp;<font color="#FF0000">*</font> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">League :</td>
												 <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
							
												<td style="padding-left:10px; padding-bottom:2px; font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;" valign="top">
												  <select name="league" id="league" style="width:215px;" onchange="toggle_wepay(this.value)">
												  <option value="0">-Select-</option>                                      
												  <option value="1">Tuesday</option>
												  <option value="5">Saturday</option>
												  <option value="6">Sunday</option>
												  </select>&nbsp;<font color="#FF0000">*</font>
												  </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Jersey Size :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="jersey_size" id="jersey_size" size="32"/> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Jersey Number :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="jersey_no" id="jersey_no" size="32"/> </td>
											</tr>
											
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Phone Number :</td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="phone" id="phone" size="32"/> </td>
											</tr>
											
											<tr>
												  <td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Referred By :</td>
												  <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												  <td style="padding-left:10px;padding-bottom:2px;" valign="top"><input type ="text" name ="referrer" id="referrer"  size="32"/> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;font-family:'Montserrat',sans-serif; font-size:14px; font-weight:normal;">Occupation :</td>
												 <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="occupation" id="occupation" size="32"/> </td>
											</tr>
											<tr>
												<td align="right" valign="middle" style="padding-left:10px;"></td>
												 <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
												<td style="padding-left:10px; padding-bottom:2px;" valign="top"><br /><a class="button2" onclick="Ext.bhl.proc();" style="text-decoration:none; color:#333333;" href="#"><span><b>Submit</b></span></a></td>
											</tr>
										</table>
										</form>
									</div>
									<div class="form_right">									
										<div id="fade" class="pics">
									
								            <img src="http://bhleague.com/bhleague_logos/brick_city_w.png" width="100%"/>
											<img src="http://bhleague.com/bhleague_logos/showtime_w.png" width="100%"/>
											<img src="http://bhleague.com/bhleague_logos/flamingos_w.png" width="100%"/>										
											<img src="http://bhleague.com/bhleague_logos/knicks_w.png" width="70%"/>	
											<img src="http://bhleague.com/bhleague_logos/reformed_w.png" width="80%"/>
											<img src="http://bhleague.com/bhleague_logos/wise_guys_w.png" width="60%"/>	
											
											<img src="http://bhleague.com/bhleague_logos/purple_rain_w.png" width="40%"/>
											<img src="http://bhleague.com/bhleague_logos/the_billy_hoyles_w.png" width="50%"/>
											<img src="http://bhleague.com/bhleague_logos/the_chemists_w.png" width="70%"/>										
											<img src="http://bhleague.com/bhleague_logos/shoot_kill_w.png" width="60%"/>	
											<img src="http://bhleague.com/bhleague_logos/clubbers_w.png" width="100%"/>
											<img src="http://bhleague.com/bhleague_logos/the_beavers_w.png" width="100%"/>
											<img src="http://bhleague.com/bhleague_logos/the_politiks3_w.png" width="100%"/>
											<img src="http://bhleague.com/bhleague_logos/the_pugamaniacs3_w.png" width="100%"/>								
								        </div>
									</div>
									<div class="padding_clear"></div>
									<div id="div_wepay_tue" style="float:left;display:none;position:inherit;margin-top:20px;margin-left:20px">
							<!--<a class="wepay-widget" href="https://stage.wepay.com/events/view/83508?widget_type=tickets&widget_ticket_id=83508&widget_auth_token=10983bc2506d465146dc3e778a93106c1980fba1&widget_show_description=1&widget_show_tickets=1">Buy Tickets for BH League BasketBall Tournament<script id="wepay-widget_script" type="text/javascript" src="https://stage.wepay.com//js/widget.wepay.js"></script></a>-->
                        </div>
                        <div id="div_wepay_sat" style="float:left;display:none;position:inherit;margin-top:20px;margin-left:20px">
                        	saturday
                        </div>      
                        <div id="div_wepay_sun" style="float:left;display:none;position:inherit;margin-top:20px;margin-left:20px">
							<!--<a class="wepay-widget" href="https://stage.wepay.com/events/view/83508?widget_type=tickets&widget_ticket_id=83508&widget_auth_token=10983bc2506d465146dc3e778a93106c1980fba1&widget_show_description=1&widget_show_tickets=1">Buy Tickets for BH League BasketBall Tournament<script id="wepay-widget_script" type="text/javascript" src="https://stage.wepay.com//js/widget.wepay.js"></script></a>-->
                        </div>    
	                
	                        </div>
						</div>
					</div>			
				</div>			
			</div>
		</div>
		<div class="clear"></div>		
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
	<script>
		$('#fade').cycle();
	</script>
  </body>
</html>