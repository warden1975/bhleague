<?php

//extract($_REQUEST);
//extract($_COOKIE);
//echo $_SERVER['HTTP_REFERER'];
if($_SERVER['HTTP_REFERER']=='http://admin.bhleague.com/')
{
	session_start();
	if($_SESSION['authenticated'] == 'yes')
	{
		//continue;
		setcookie("auth","yes", time()+3600*24);
	}
	else
	{
		if (isset($_SESSION["username"])) {
			//$userinfo = $f->cookieAuth($_COOKIE["username"]);
		}
		else if (isset($_SESSION["adminusername"])) {
			//$userinfo = $f->cookieAuth($_SESSION["adminusername"]);
		}
		else {
			session_write_close();
			header("location:http://admin.bhleague.com/");die();
		}
	}
}
else if($_SERVER['HTTP_REFERER']=='')
{
	//session_start();
	if($_COOKIE['auth'] == 'yes')
	{
		//continue;
		//echo $_COOKIE['auth'];
	}
	else
	{
		
			session_write_close();
			header("location:http://admin.bhleague.com/");die();
		
	}
}
//?>
<html>
<head>
    <title>BH League :: Main Menu</title>
	<script language="javascript" >
	window.history.forward(1);
	</script>
	<link rel="stylesheet" type="text/css" href="menus.css" />
	<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
 	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../extjs/ext-all.js"></script>
	<style>
		.cTextAlign{
    text-align: right;
	width:165px;

   }
	.sports { background-image: url(images/sport_basketball.png); }
	.stats { background-image: url(images/statistics.png); }
	.group { background-image: url(images/groups.png); }
	.players { background-image: url(images/groups.png); }
	.player { background-image: url(images/player.png); }
	.list { background-image: url(images/application_view_list.png); }
	.logout { background-image: url(images/logout.png); }
	.schedule { background-image: url(images/schedule.png); }
	.upload { background-image: url(images/application_go.png); }
	</style>
	<script>
	function del_cookie(name) 
	{
		//document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';	
		Ext.util.Cookies.set(name, null, new Date("January 1, 1970"));
		Ext.util.Cookies.clear(name);
		window.location="http://admin.bhleague.com/";
	} 
	Ext.onReady(function() {
	
	var samplePanel = new Ext.Panel({
	width: 1650,
	height: 850,
	autoHeight:true,
	autoWidth:true,
	title: "Main Menu BH League",
	html: '<iframe id="ifrm_attachx" name="ifrm_attachx" src="" width="1600" height="820"  style="overflow:auto;  border-left:none; border-bottom:none; border-top:none; border-right:none;" ></iframe>',
	tbar: {
	items: [
	{
		xtype: 'button',
		text: 'Games',
		iconCls: 'sports', // 3	
		menu: { // 4
			showSeparator: false, // 5
			items: [{
				text: 'Game Schedule',
				iconCls: 'schedule',
				handler: function() 
				{
					showTarget('http://admin.bhleague.com/schedule/')
				}
			},
			{
				text: 'Post New Season',
				iconCls: 'schedule',
				handler: function() 
				{
					Ext.MessageBox.show({
						 title:'Post new season?',
						 msg: 'You are about to archive current season and create a new one. <br />Would you like to proceed?',
						 buttons: Ext.MessageBox.YESNOCANCEL,
						 fn: function(btn) {
							 console.log('Button Click', 'You clicked the button', btn);
							 if (btn = 'yes') {
								  var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Inserting records. Please wait...'});
								  mask.show();
								  Ext.Ajax.request({
									  params: {action: 'post'},
									  url: 'callback_autogen.php',
									  success: function (resp,form,action) {
										  console.log(resp.responseText);
										  mask.hide();
										  /*if (resp.responseText == '{success:true}') {
											  mask.hide();
											  Ext.MessageBox.alert('Message', 'Record successfully created');
											  //store.reload();
											  //grid.getstore();
											  //grid.getView().refresh();
										  } else {
											  mask.hide();
											  Ext.MessageBox.alert('Error', 'Some problem occurred');
										  }*/
									  },
									  failure: function (form,action) {
										  switch (action.failureType) {
											  case Ext.form.Action.CLIENT_INVALID:
												 Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
												 break;
											  case Ext.form.Action.CONNECT_FAILURE:
												 Ext.Msg.alert('Failure', 'Ajax communication failed');
												 break;
											  case Ext.form.Action.SERVER_INVALID:
												Ext.Msg.alert('Failure', action.result.msg);
												break;
											  default:
												Ext.Msg.alert('Failure',action.result.msg);
										  }
									  }
								  });
							 }
						 },
						 icon: Ext.MessageBox.QUESTION
					});
				}
			}]
		}
	},
	{
		xtype: 'button',
		text: 'Teams',
		iconCls: 'players', // 3	
		menu: { // 4
			showSeparator: false, // 5
			items: [{
				text: 'Game Stats',
				iconCls: 'stats',
				handler: function() 
				{
					showTarget('http://admin.bhleague.com/game_stats/')
				}
			},
			{
				text: 'Listing',
				iconCls: 'list',
				handler: function() 
				{
					showTarget('http://admin.bhleague.com/teams/')
				}
			}]
		}
	},
	{
		xtype: 'button',
		text: 'Players',
		iconCls: 'player', // 3	
		menu: { // 4
			showSeparator: false, // 5
			items: [{
				text: 'Player Stats',
				iconCls: 'stats',
				handler: function() 
				{
					showTarget('http://admin.bhleague.com/player_stats/')
				}
			},
			{
				text: 'Listing',
				iconCls: 'list',
				handler: function() 
				{
					showTarget('http://admin.bhleague.com/players/')
				}
			},
			{
				text: 'Positions',
				iconCls: 'list',
				handler: function() 
				{
					showTarget('http://admin.bhleague.com/positions/')
				}
			}]
		}
	},
	{
		xtype: 'button',
		text: 'Upload Image',
		iconCls: 'upload',
		handler: function() 
		{
			
			showTarget('http://admin.bhleague.com/uploader/')
			
		}
	},
	{
		xtype: 'button',
		text: 'Logout',
		iconCls: 'logout',
		handler: function() 
		{
			del_cookie('username');
			del_cookie('auth');
			<?php session_start(); unset($_SESSION);session_destroy(); ?>
			window.location="http://admin.bhleague.com/";
			
		}
	}
	]
	},
	renderTo: Ext.getBody()
	
	});
	});
	
	function showTarget(target)
	{
	
		document.getElementById("ifrm_attachx").src = target; 
	}
</script>
</head>
<body style="padding:10px;">

  
   

</body>
</html>
