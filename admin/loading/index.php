<?php
//session_unset();
//  session_start();
//  
//  $_SESSION['username'] = "fortune";
//  echo session_id();
?>
<html>
<head>
    <title>BH League :: Loading</title>

	<!--<link rel="stylesheet" type="text/css" href="menus.css" />-->
	<!--<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />-->
 	<!--<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>-->
    <!--<script type="text/javascript" src="../extjs/ext-all.js"></script>-->
	<style>
		.cTextAlign{
    text-align: right;
	width:165px;

   }
   </style>
	<style type="text/css">
HTML, BODY { height: 100%; }
#loading-mask {
     position: absolute;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background: #000000;
     z-index: 1;
}
#loading {
     position: absolute;
     top: 40%;
     left: 45%;
     z-index: 2;
}
#loading SPAN {
     background: url('images/loader.gif') no-repeat left center;
     padding: 5px 30px;
     display: block;
}
</style>
	<script>
	
	var obj;
	var jsonData;
	var resultMessage;
	//function fn_AKExt( message, title ){
//	   Ext.Msg.show({
//		  title: title,
//		  msg: message ,
//		  buttons: Ext.MessageBox.OK,
//		  icon: Ext.MessageBox.INFO
//		 });
//	 }
//	 function sleep(milliseconds) 
//	 {
//	  var start = new Date().getTime();
//	  for (var i = 0; i < 1e7; i++) 
//	  {
//		if ((new Date().getTime() - start) > milliseconds)
//		{
//		  break;
//		}
//	  }
//	}
//
//
//	function setCookie(c_name,value,exdays)
//	{
//		var exdate=new Date();
//		exdate.setDate(exdate.getDate() + exdays);
//		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
//		document.cookie=c_name + "=" + c_value;
//	}
//	function getCookie(c_name)
//	{
//	var i,x,y,ARRcookies=document.cookie.split(";");
//	for (i=0;i<ARRcookies.length;i++)
//	{
//	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
//	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
//	  x=x.replace(/^\s+|\s+$/g,"");
//	  if (x==c_name)
//		{
//		return unescape(y);
//		}
//	  }
//	}
//	function fn_submitForm(button,event)
//	{
//	   var f = Ext.getCmp('login');
//	   if( f.getForm().isValid() == true)
//	   {
//		   var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Posting login credentials. Please wait...'});
//			mask.show();
//			
//		   var usernamex = document.getElementById('username');
//		   var pwdx = document.getElementById('pwd');
//		   setCookie("username",usernamex.value,365);
//		   Ext.Ajax.request({
//			 url : 'callback.php?action=checkAuth',
//					  method: 'POST',
//					  params :{username:usernamex.value, pwd:pwdx.value},
//					  success: function ( result, request ) {
//					  //alert(result.responseText);
//						   jsonData = Ext.util.JSON.decode(result.responseText);
//						   if(jsonData.status=='success')
//						   {
//								mask.hide();
//								//alert(jsonData.username);
//								
//								//setCookie("username",jsonData.username,365);
//								//Ext.util.Cookies("username",jsonData.username,365)
//								//var z =getCookie("username");
////								alert(z);
//								//sleep(1000);
//								Ext.Msg.alert('Status', 'Login Successful!')								
//								window.location = 'http://admin.bhleague.com/main.php'; ;
//								//
//						   }
//						   else if(jsonData.status=='fail')
//							{
//								mask.hide();
//								Ext.Msg.alert('Status', 'Invalid Username or Password')
//							}
//				   },
//					  failure: function ( result, request ) {
//						mask.hide();
//						jsonData = Ext.util.JSON.decode(result.responseText);
//						resultMessage = jsonData.status;
//					   fn_AKExt(resultMessage, 'Error');
//				   }
//		   });
//	   }
//	   else
//	   {
//		alert("Username and Password cannot be blank!");
//	   }
//   }
//	Ext.onReady(function() {
//  setTimeout(function(){
//    Ext.get('loading').remove();
//    Ext.get('loading-mask').fadeOut({remove:true});
//  }, 250);
//});


</script>
</head>
<body style="padding:10px;">
<div id="loading-mask"></div>
	<div id="loading">
		<span id="loading-message">Loading. Please wait...</span>
	</div>
	
	<script type="text/javascript">
		document.getElementById('loading-message').innerHTML = 'Loading Core API...';
	</script>
	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
	
	<script type="text/javascript">
		document.getElementById('loading-message').innerHTML = 'Loading ExtJS 3.0 ...';
	</script>
	 <script type="text/javascript" src="../extjs/ext-all.js"></script>

	<script type="text/javascript">
		Ext.onReady(function(){
			var loadingMask = Ext.get('loading-mask');
			var loading = Ext.get('loading');

			//	Hide loading message			
			loading.fadeOut({ duration: 2, remove: true });
			
			//	Hide loading mask
			loadingMask.setOpacity(0.9);
			loadingMask.shift({
				xy: loading.getXY(),
				width: loading.getWidth(),
				height: loading.getHeight(),
				remove: true,
				duration: 1,
				opacity: 0.1,
				easing: 'bounceOut'
			});
		});
	</script>
</body>
</html>