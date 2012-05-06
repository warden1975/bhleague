<?php
//session_unset();
//  session_start();
//  
//  $_SESSION['username'] = "fortune";
//  echo session_id();
?>
<html>
<head>
    <title>BH League :: Log In</title>

	<!--<link rel="stylesheet" type="text/css" href="menus.css" />-->
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
	</style>
	<script>
	
	var obj;
	var jsonData;
	var resultMessage;
	function fn_AKExt( message, title ){
	   Ext.Msg.show({
		  title: title,
		  msg: message ,
		  buttons: Ext.MessageBox.OK,
		  icon: Ext.MessageBox.INFO
		 });
	 }
	 function sleep(milliseconds) 
	 {
	  var start = new Date().getTime();
	  for (var i = 0; i < 1e7; i++) 
	  {
		if ((new Date().getTime() - start) > milliseconds)
		{
		  break;
		}
	  }
	}


	function setCookie(c_name,value,exdays)
	{
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie=c_name + "=" + c_value;
	}
	function getCookie(c_name)
	{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name)
		{
		return unescape(y);
		}
	  }
	}
	function fn_submitForm(button,event)
	{
	   var f = Ext.getCmp('login');
	   if( f.getForm().isValid() == true)
	   {
		   var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Posting login credentials. Please wait...'});
			mask.show();
			
		   var usernamex = document.getElementById('username');
		   var pwdx = document.getElementById('pwd');
		   setCookie("username",usernamex.value,365);
		   Ext.Ajax.request({
			 url : 'callback.php?action=checkAuth',
					  method: 'POST',
					  params :{username:usernamex.value, pwd:pwdx.value},
					  success: function ( result, request ) {
					  //alert(result.responseText);
						   jsonData = Ext.util.JSON.decode(result.responseText);
						   if(jsonData.status=='success')
						   {
								mask.hide();
								//alert(jsonData.username);
								
								//setCookie("username",jsonData.username,365);
								//Ext.util.Cookies("username",jsonData.username,365)
								//var z =getCookie("username");
//								alert(z);
								//sleep(1000);
								Ext.Msg.alert('Status', 'Login Successful!')								
								window.location = 'http://admin.bhleague.com/main.php'; ;
								//
						   }
						   else if(jsonData.status=='fail')
							{
								mask.hide();
								Ext.Msg.alert('Status', 'Invalid Username or Password')
							}
				   },
					  failure: function ( result, request ) {
						mask.hide();
						jsonData = Ext.util.JSON.decode(result.responseText);
						resultMessage = jsonData.status;
					   fn_AKExt(resultMessage, 'Error');
				   }
		   });
	   }
	   else
	   {
		alert("Username and Password cannot be blank!");
	   }
   }
	Ext.onReady(function() {

    Ext.QuickTips.init();
 
	// Create a variable to hold our EXT Form Panel. 
	// Assign various config options as seen.	 
    var login = new Ext.FormPanel({ 
        labelWidth:80,
        frame:true, 
		id: 'login',
        title:'Please Login', 
        defaultType:'textfield',
	monitorValid:true,
	// Specific attributes for the text fields for username / password. 
	// The "name" attribute defines the name of variables sent to the server.
        items:[{ 
                fieldLabel:'Username', 
                name:'username', 
				id:'username',
                allowBlank:false 
            },{ 
                fieldLabel:'Password', 
                name:'pwd', 
				id:'pwd',
                inputType:'password', 
                allowBlank:false ,
				listeners: {
				specialkey: function(field, e){
				if (e.getKey() == e.ENTER) {
				fn_submitForm('btnsubmit',e);
				}
				}
				}
            }],
 
	// All the magic happens after the user clicks the button     
        buttons:[{ 
                text:'Login',
                formBind: true,	
				id:'btnsubmit',
				 handler: fn_submitForm 
                // Function that fires when user clicks the button 
                
            }],
			keys: [{
                key: [Ext.EventObject.ENTER], fn: function(){
                    Ext.ComponentMgr.get('btnsubmit').fireEvent('click');
                }
        }]
    });
 
 
	// This just creates a window to wrap the login form. 
	// The login object is passed to the items collection.       
    var win = new Ext.Window({
        layout:'fit',
        width:300,
        height:150,
        closable: false,
        resizable: false,
        plain: true,
        border: false,
        items: [login]
	});
	win.show();
});

</script>
</head>
<body style="padding:10px;">

  
   

</body>
</html>