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
	<script type="text/javascript">
		Ext.namespace('Ext.bhl');
		Ext.Element.prototype.setClass = function(cls,add_class){
		  add_class ? this.addClass(cls) : this.removeClass(cls)
		}	
		Ext.bhl.wepay_link = "<?php echo WEPAY_DOMAIN_LIVE.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM_LIVE_TUE.WEPAY_REDIRECT_URI; ?>";
		Ext.bhl.wepay_link_sat = "<?php echo WEPAY_DOMAIN_LIVE.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM_LIVE_SAT.WEPAY_REDIRECT_URI_SAT; ?>";
		Ext.bhl.wepay_link_sun = "<?php echo WEPAY_DOMAIN_LIVE.WEPAY_AUTHORIZE_URI.WEPAY_AUTHORIZE_PARAM_LIVE_SUN.WEPAY_REDIRECT_URI_SUN; ?>";
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
						<a href="signup.php">PAYMENTS</a>
						
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			
		<div id="content" class="dark-c">
			<div class="col col3-full">
				<div class="table1">
					<h2 class="title2">Player Sign Up</h2>
					<div id="grid-player_stats" class="sub-box">
                        <div id="div_frm" style="float:left" class="form-reg" >
                            <form name="frmaaddNotification" id="frmaaddNotification" method="post" action="signup_callback.php?action=insert&timestamp=<?php echo time() ?>">
                
                                <table style="padding-top:20px; padding-bottom:20px;" >
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">First Name :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="fname" id="fname" size="32" height="50" />&nbsp;<font color="#FF0000">*</font></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Last Name :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="lname" id="lname" size="32" height="50" />&nbsp;<font color="#FF0000">*</font> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Email :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="email" id="email" size="32" />&nbsp;<font color="#FF0000">*</font> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Height :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="height" id="height" size="32"/> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Weight :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="weight" id="weight" size="32"/> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Position :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><?php echo $opt_pos; ?>&nbsp;<font color="#FF0000">*</font> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">League :</td>
                                     <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top">
                                      <select name="league" id="league" style="width:215px;" onchange="toggle_wepay(this.value)">
                                      <option value="0">-Select-</option>                                      
                                      <option value="1">Tuesday</option>
                                      <option value="5">Saturday</option>
                                      <option value="6">Sunday</option>
                                      </select>&nbsp;<font color="#FF0000">*</font>
                                      </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Jersey Size :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="jersey_size" id="jersey_size" size="32"/> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Jersey Number :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="jersey_no" id="jersey_no" size="32"/> </td>
                                </tr>
                                
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Phone Number :</td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="phone" id="phone" size="32"/> </td>
                                </tr>
                                
                                <tr>
                                      <td align="right" valign="middle" style="padding-left:10px;">Referred By :</td>
                                      <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                      <td style="padding-left:10px;padding-bottom:2px;" valign="top"><input type ="text" name ="referrer" id="referrer"  size="32"/> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;">Occupation :</td>
                                     <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"><input type ="text" name ="occupation" id="occupation" size="32"/> </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding-left:10px;"></td>
                                     <td style="padding-left:10px; padding-bottom:2px;" valign="top"></td>
                                    <td style="padding-left:10px; padding-bottom:2px;" valign="top"> <input type="button" style="padding:10px;font-size:15px !important;color:#FFF;   font: bold 84% 'trebuchet ms',helvetica,sans-serif;   background-color:#00A9D3;   border: 1px solid;   border-color: #696 #363 #363 #696;   " id="btnReferesh" value="   Submit   " onclick="Ext.bhl.proc();"  /> </td>
                                </tr>
								</p>
								<!--  &nbsp;&nbsp;&nbsp;&nbsp;Notes:-->
								  <br />
								  &nbsp;&nbsp;<font color="#FF0000">*</font> Required Fields
								 <!-- <br />
								  &nbsp;&nbsp;&nbsp;&nbsp;Please use the same email you used for signing up in BHLeague.com site and Wepay pay gateway  to facilitate easier tracking of payments.-->
								</p> 
                                
                            </table>
                		</form>
                <!--
                <a class="wepay-widget" href="https://www.wepay.com/events/view/92488?widget_type=tickets&widget_ticket_id=92488&widget_auth_token=3cff0814cfaf9a56fefd2f49c619ff97f8d73561&widget_show_description=1&widget_show_tickets=1">Buy Tickets for Tuesday Night BH League<script id="wepay-widget_script" type="text/javascript" src="https://www.wepay.com/min/js/widget.wepay.js"></script></a>
                -->
                
                <!--
				https://stage.wepay.com/v2/oauth2/authorize?client_id=85533&redirect_uri=http://www.bhleague.com/process.php&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user                 
                
                # NOTE #
                MUST INCLUDE PLAYER ID ON REDIRECT URI
                
                -->
                        </div>

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
	</div>
	<?php include('footer.php'); ?>
	</body>
    </html>