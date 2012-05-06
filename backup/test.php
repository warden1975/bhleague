<html>
<head>
    <title>BH League :: Main Menu</title>
    <!--<link rel="stylesheet" type="text/css" href="/lib/extjs/resources/css/ext-all.css" />
 	<script type="text/javascript" src="/lib/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="/lib/extjs/ext-all.js"></script>-->
	<!--<link rel="stylesheet" type="text/css" href="SuperBoxSelect/superboxselect.css" />-->
	<link rel="stylesheet" type="text/css" href="menus.css" />
	<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
<!--	<link rel="stylesheet" type="text/css" href="./css/icons.css">
  <link rel="stylesheet" type="text/css" href="./css/Ext.ux.grid.RowActions.css">
  <link rel="stylesheet" type="text/css" href="./css/empty.css" id="theme">
  <link rel="stylesheet" type="text/css" href="./css/webpage.css">
  <link rel="stylesheet" type="text/css" href="./css/gridsearch.css">-->
 	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../extjs/ext-all.js"></script>
	<!--<script src="Exporter-all.js" type="text/javascript"></script>
	<script type="text/javascript" src="SuperBoxSelect/SuperBoxSelect.js"></script>
	 <script type="text/javascript" src="./js/WebPage.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.ThemeCombo.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.IconMenu.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.Toast.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.grid.Search.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.grid.RowActions.js"></script>
-->	  <script type="text/javascript" src="states.js"></script>
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
	</head>
<script type="text/javascript">
var example1;
Ext.onReady(function(){
   example1 = new Ext.Panel({
    applyTo:Ext.getBody(),
    title:'Example 1',
    width:250,
    height:250,
    frame:true,
    autoLoad:{
      url:'http://admin.bhleague.com/game_stats/'
    }
  });
});


</script>

<body>

</body>
</html>
