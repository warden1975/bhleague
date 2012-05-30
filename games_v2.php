<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
	define('DB_CLS', DIR . 'admin/class/db.cls.php');
	define('COMMON', DIR . 'bhlcommon.php');

	require DB_CLS;
	require COMMON;

	$db = new DB_Connect(MYSQL_INTRANET, 1);
	if (!$db) die("Can't connect to database.");

	$m = 'ppg';
	$mm = 'Points';
	$ppg1 = $r = $rpg1 = $apg1 = $ppg2 = $rpg2 = $apg2 = array();

	extract($_REQUEST);
	
	### NEW VARS ###
	$rimage[0] = "1st.png";
	$rimage[1] = "2nd.png";
	$rimage[2] = "3rd.png";
### NEW VARS ###	

$sql_ppg1 = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name, c.id as team_id,
sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as score, 
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
		where weekday(a.game_date) IN('1','5','6') and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team1' and c.id = '$team1' and a.game_date ='$gamedate'
		group by b.id 
order by score desc) as ave 
order by pg desc limit 3";

if ($rs = $db->query($sql_ppg1)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_assoc()) {
				$ppg1[] = $row;
			}
		}
		$rs->close();
	}

$sql_rpg1 = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name, c.id as team_id,
sum(if(a.player_id=b.id, game_rebounds, 0)) as score, 
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
		where weekday(a.game_date) IN('1','5','6') and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team1' and c.id = '$team1' and a.game_date ='$gamedate'
		group by b.id 
order by score desc) as ave 
order by pg desc limit 3";

if ($rs = $db->query($sql_rpg1)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_assoc()) {
				$rpg1[] = $row;
			}
		}
		$rs->close();
	}

$sql_apg1 = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name, c.id as team_id, 
sum(if(a.player_id=b.id, game_assists, 0)) as score,
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
		where weekday(a.game_date) IN('1','5','6') and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team1' and c.id = '$team1' and a.game_date ='$gamedate'
		group by b.id 
order by score desc) as ave 
order by pg desc limit 3";

if ($rs = $db->query($sql_apg1)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_assoc()) {
				$apg1[] = $row;
			}
		}
		$rs->close();
	}

$sql_ppg2 = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name, c.id as team_id,
sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0)) as score, 
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
		where weekday(a.game_date) IN('1','5','6') and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team2' and c.id = '$team2' and a.game_date ='$gamedate'
		group by b.id 
order by score desc) as ave 
order by pg desc limit 3";

if ($rs = $db->query($sql_ppg2)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_assoc()) {
				$ppg2[] = $row;
			}
		}
		$rs->close();
	}

$sql_rpg2 = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name,c.id as team_id, 
sum(if(a.player_id=b.id, game_rebounds, 0)) as score, 
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
		where weekday(a.game_date) IN('1','5','6') and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team2' and c.id = '$team2' and a.game_date ='$gamedate'
		group by b.id 
order by score desc) as ave 
order by pg desc limit 3";

if ($rs = $db->query($sql_rpg2)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_assoc()) {
				$rpg2[] = $row;
			}
		}
		$rs->close();
	}

$sql_apg2 = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg,ave.team_id from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name,c.id as team_id, 
sum(if(a.player_id=b.id, game_assists, 0)) as score,
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
		where weekday(a.game_date) IN('1','5','6') and 
			(select case (weekday(a.game_date)) 
			when '1' then b.team_id 
			when '5' then b.team_id2 
			when '6' then b.team_id3
			end) = '$team2' and c.id = '$team2' and a.game_date ='$gamedate'
		group by b.id 
order by score desc) as ave 
order by pg desc limit 3";
		
if ($rs = $db->query($sql_apg2)) {
		$rs_cnt = $rs->num_rows;
		if ($rs_cnt > 0) {
			while ($row = $rs->fetch_assoc()) {
				$apg2[] = $row;
			}
		}
		$rs->close();
	}




$sql = "select rank.team, round(rank.pts_for - rank.pts_against, 0) as pts_diff from (
select b.team_name as team, 
	sum(if(a.game_winner=b.id,1,0)) as wins, 
	sum(if(a.game_loser=b.id,1,0)) as losses, 
	(sum(if(a.game_winner=b.id,a.winner_score,0)) + sum(if(a.game_loser=b.id,a.loser_score,0))) as pts_for,
	(sum(if(a.game_winner=b.id,a.loser_score,0)) + sum(if(a.game_loser=b.id,a.winner_score,0))) as pts_against 
from bhleague.games_stats a, bhleague.teams b 
where weekday(a.game_date) = '{$gameday}'  
group by b.id having pts_for <> 0 
order by wins desc, losses asc) as rank 
order by pts_diff desc limit 8;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$r[] = $row;
		}
	}
	$rs->close();
}

$sql = "select date_format(game_date, '%M %e') as game_date, dayname(game_date) as game_dayname from bhleague.`schedule` where game_date >= now() group by game_date limit 1;";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		$s = $rs->fetch_assoc();
	}
	$rs->close();
}

$db->close();
$db = NULL;
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
<!--	<script type="text/javascript" src="index_main_v2.js"></script>-->
    <script type="text/javascript" src="bhlcommon.js"></script>
    <script language="javascript">
		Ext.Element.prototype.setClass = function(cls,add_class){
		  add_class ? this.addClass(cls) : this.removeClass(cls)
		}	
		function togglebox_leaders(box) {
			var bp_el = Ext.get('box_point');
			var br_el = Ext.get('box_rebound');
			var ba_el = Ext.get('box_assist');
			
			var bp_el2 = Ext.get('box_point2');
			var br_el2 = Ext.get('box_rebound2');
			var ba_el2 = Ext.get('box_assist2');
			
			switch (box) {
				default:
				case 1:
					Ext.get('a_box_point').addClass('on');
					Ext.get('a_box_rebound').removeClass('on');
					Ext.get('a_box_assist').removeClass('on');	
					
					Ext.get('a_box_point2').addClass('on');
					Ext.get('a_box_rebound2').removeClass('on');
					Ext.get('a_box_assist2').removeClass('on');
														
					bp_el.show();
					br_el.setVisibilityMode(Ext.Element.DISPLAY);
					br_el.hide();
					ba_el.setVisibilityMode(Ext.Element.DISPLAY);
					ba_el.hide();
					
					bp_el2.show();
					br_el2.setVisibilityMode(Ext.Element.DISPLAY);
					br_el2.hide();
					ba_el2.setVisibilityMode(Ext.Element.DISPLAY);
					ba_el2.hide();
					break;
				case 2:
					Ext.get('a_box_point').removeClass('on');
					Ext.get('a_box_rebound').addClass('on');
					Ext.get('a_box_assist').removeClass('on');	
					
					Ext.get('a_box_point2').removeClass('on');
					Ext.get('a_box_rebound2').addClass('on');
					Ext.get('a_box_assist2').removeClass('on');	
									
					bp_el.setVisibilityMode(Ext.Element.DISPLAY);
					bp_el.hide();
					br_el.show();
					ba_el.setVisibilityMode(Ext.Element.DISPLAY);
					ba_el.hide();
					
					bp_el2.setVisibilityMode(Ext.Element.DISPLAY);
					bp_el2.hide();
					br_el2.show();
					ba_el2.setVisibilityMode(Ext.Element.DISPLAY);
					ba_el2.hide();
					break;
				case 3:
					Ext.get('a_box_point').removeClass('on');
					Ext.get('a_box_rebound').removeClass('on');
					Ext.get('a_box_assist').addClass('on');	
					
					Ext.get('a_box_point2').removeClass('on');
					Ext.get('a_box_rebound2').removeClass('on');
					Ext.get('a_box_assist2').addClass('on');
									
					bp_el.setVisibilityMode(Ext.Element.DISPLAY);
					bp_el.hide();
					br_el.setVisibilityMode(Ext.Element.DISPLAY);
					br_el.hide();
					ba_el.show();
					
					bp_el2.setVisibilityMode(Ext.Element.DISPLAY);
					bp_el2.hide();
					br_el2.setVisibilityMode(Ext.Element.DISPLAY);
					br_el2.hide();
					ba_el2.show();
					break;
			}
		}	
	</script>
    
  </head>
  <body>
   <script>
/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
  function change(val) {
        if (val > 0) {
            return '<span style="color:green;">' + val + '</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }
	
	

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function pctChange(val) {
        if (val > 0) {
            return '<span style="color:green;">' + val + '%</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }
 function getGame_Player_Profile(team1,team2,gamedate,uri)
	{
	var reader = new Ext.data.ArrayReader({}, [
		{name: 'player_id',type: 'int'},
		{name: 'player'},
		//{name: 'height'},
//		{name: 'weight',},
		{name: 'position'},
		{name: 'ppg', type: 'float'},
		{name: 'rpg', type: 'float'},
		{name: 'apg', type: 'float'},
		//{name: 'games', type: 'float'}
	]);
	
	// get the data
    var proxy = new Ext.data.HttpProxy({
		//where to retrieve data
		url: uri , //url to data object (server side script)
		method: 'GET'
	});
        
    // create the data store.
    var store1 = new Ext.data.Store({
    	reader: reader,
        proxy: proxy
    });

    // create the Grid
    var grid1 = new Ext.grid.GridPanel({
        store: store1,
		//title:'Player Stats',
		viewConfig:{
        emptyText:'No records to display'
    },
        columns: [
            {
                id       :'player',
                header   : 'Player', 
                width    : 160, 
                sortable : false, 
				renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'player'
            },
			//{
//                header   : 'Height', 
//                width    : 100, 
//                sortable : false, 
//                renderer : change, 
//                dataIndex: 'height',
//				align	 : 'left'
//            },
//			{
//                header   : 'Weight', 
//                width    : 100, 
//                sortable : false, 
//                renderer : change, 
//                dataIndex: 'weight',
//				align	 : 'center'
//            },
			{
                header   : 'Position', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'position',
				align	 : 'center'
            },
			{
                header   : 'Points', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'ppg',
				align	 : 'center'
            },
			{
                header   : 'Rebounds', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'rpg',
				align	 : 'center'
            },
			{
                header   : 'Assists', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'apg',
				align	 : 'center'
            }//,
//			{
//                header   : 'Games Played', 
//                width    : 100, 
//                sortable : false, 
//                renderer : change, 
//                dataIndex: 'games',
//				align	 : 'center'
//            }
        ],
        stripeRows: true,
        autoExpandColumn: 'player',
         listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);  // Get the Record
					//var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
					var data = record.get('player_id');
					//console.log('fieldName: '+fieldName+', data: '+data);
					window.location.href='player_v2.php?player='+data;
				}
			},
		autoHeight: true,
		border: false,
		layout: 'fit',
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid'
    });
	
	store1.load();

    grid1.render('grid-team-rosters');
	grid1.getSelectionModel().selectFirstRow();
	}

function getGame_Profile(team1,team2,gamedate,uri)
{
	var ds_model = Ext.data.Record.create([
		{name: 'team1x', type:'int'},
		{name: 'team2x', type:'int'},
		{name: 'game_date'},
		{name: 'game_time'},
		{name: 'team1',},
		{name: 'team2'},
		{name: 'score'}
	]);
	
	// get the data
   		store = new Ext.data.Store({
			url: uri ,
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				id:'id'
			}, ds_model)
	    });
	
		
    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
		//title:'Game Stats',
		viewConfig:{
        emptyText:'No records to display'
    },
        columns: [
            {
                id       :'game_date',
                header   : 'Game Date', 
                width    : 140, 
                sortable : false, 
				align	 : 'center',
                dataIndex: 'game_date'
            },
			{
                header   : 'Time', 
                width    : 160, 
                sortable : false, 
                dataIndex: 'game_time',
				align	 : 'center'
            },
			{
                header   : 'Team 1', 
                width    : 160, 
                sortable : false, 
                dataIndex: 'team1',
				renderer : Ext.bhlcommondata.format_underline,
				align	 : 'center'
            },
			{
                header   : 'Team 2', 
                width    : 160, 
                sortable : false, 
                dataIndex: 'team2',
				renderer : Ext.bhlcommondata.format_underline,
				align	 : 'center'
            },
			{
                header   : 'Score', 
                width    : 140, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'score',
				align	 : 'center'
            }
        ],
		
        stripeRows: true,
        autoExpandColumn: 'game_date',
        listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);  // Get the Record
					
					//alert(columnIndex);
					//var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
					if(columnIndex==2)
					{
					//alert(columnIndex);
					var data = record.get('team1x');
					window.location.href='teams_v2.php?team_id='+data;
					//alert(window.location.hash);
					}
					else if(columnIndex==3)
					{
					//alert(columnIndex);
					var data = record.get('team2x');
					window.location.href='teams_v2.php?team_id='+data;	
					//alert(window.location.hash);
					}
					//window.location.href='teams_v2.php?team_id='+data2;
				}
			},
		autoHeight: true,
		border: false,
		layout: 'fit',
        // config options for stateful behavior

        stateful: true,
        stateId: 'grid'
    });
	
	store.load();

    grid.render('grid-team-stats');
	grid.getSelectionModel().selectFirstRow();
	
	}
	function getGame_Revious_Match(team1,team2,gamedate,uri)
	{
		var ds_model = Ext.data.Record.create([
			{name: 'game_date'},
			{name: 'game_time'},
			{name: 'team1',},
			{name: 'team2'},
			{name: 'score'}
		]);
		
		// get the data
			storex = new Ext.data.Store({
				url: uri ,
				reader: new Ext.data.JsonReader({
					root:'rows',
					totalProperty: 'results',
					id:'id'
				}, ds_model)
			});
		
			
		// create the Grid
		var gridx = new Ext.grid.GridPanel({
			store: storex,
			//title:'Game Stats',
			viewConfig:{
			emptyText:'No records to display'
		},
			columns: [
				{
					id       :'game_date',
					header   : 'Game Date', 
					width    : 140, 
					sortable : false, 
					align	 : 'center',
					dataIndex: 'game_date'
				},
				{
					header   : 'Time', 
					width    : 160, 
					sortable : false, 
					dataIndex: 'game_time',
					align	 : 'center'
				},
				{
					header   : 'Team 1', 
					width    : 160, 
					sortable : false, 
					dataIndex: 'team1',
					renderer : Ext.bhlcommondata.format_underline,
					align	 : 'center'
				},
				{
					header   : 'Team 2', 
					width    : 160, 
					sortable : false, 
					dataIndex: 'team2',
					renderer : Ext.bhlcommondata.format_underline,
					align	 : 'center'
				},
				{
					header   : 'Score', 
					width    : 140, 
					sortable : false, 
					renderer : change, 
					dataIndex: 'score',
					align	 : 'center'
				}
			],
			
			stripeRows: true,
			autoExpandColumn: 'game_date',
			listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);  // Get the Record
					
					//alert(columnIndex);
					//var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
					if(columnIndex==2)
					{
					//alert(columnIndex);
					var data = record.get('team1x');
					window.location.href='teams_v2.php?team_id='+data;
					//alert(window.location.hash);
					}
					else if(columnIndex==3)
					{
					//alert(columnIndex);
					var data = record.get('team2x');
					window.location.href='teams_v2.php?team_id='+data;
					//alert(window.location.hash);	
					}
					//window.location.href='teams_v2.php?team_id='+data2;
				}
			},
			autoHeight: true,
			border: false,
			layout: 'fit',
			// config options for stateful behavior
	
			stateful: true,
			stateId: 'grid'
		});
		
		storex.load();
	
		gridx.render('grid-prev-games');
		gridx.getSelectionModel().selectFirstRow();
		
		}

	function getGames(idx,tm1,tm2)
	{
		urx ='games_callback.php?action=get_game_stats&gamedate=' + idx + '&team1=' +tm1 + '&team2=' +tm2;
		//alert(idx);
		getGame_Profile(tm1,tm2,idx,urx )
		
		idz = idx
		//titlez = resp.responseText + ' Player Stats';
		urz ='games_callback.php?action=get_game_player_roster&gamedate=' + idx + '&team1=' +tm1 + '&team2=' +tm2;
		getGame_Player_Profile(tm1,tm2,idx,urz)
		
		urq ='games_callback.php?action=get_previous_matchup&gamedate=' + idx + '&team1=' +tm1 + '&team2=' +tm2;
		getGame_Revious_Match(tm1,tm2,idx,urq)

	}
	function isValidDate(value) 
	{
		var dateWrapper = new Date(value);
		return isNaN(dateWrapper.getDate());
	}
	
	function getLast_GameDate()
	{
		Ext.Ajax.request({
		params: {action: 'get_Date'},
		url: 'games_callback.php',
		success: function (resp,form,action) {
		
		//Ext.getCmp('season').setValue('1');
		getGames('2012-04-24 00:00:00',13,8)
		//team_id.setValue(team_id.getStore().getAt(resp.responseText).get(cb.valueField));
			 
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
Ext.onReady(function(){
    Ext.QuickTips.init();

	
	//alert(document.getElementById('hid_team_id').value);
    // NOTE: This is an example showing simple state management. During development,
    // it is generally best to disable state management as dynamically-generated ids
    // can change across page loads, leading to unpredictable results.  The developer
    // should ensure that stable state ids are set for stateful components in real apps.    
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
   
	
	// `game_date`,`game_time`,team1,team2,team1_score,team2_score
	//setgame_params();
	var tmz = document.getElementById('hid_game_date').value;
	if(isValidDate(tmz)==false)
	{
		
	//alert("ZZZZ");
		var gamedate = document.getElementById('hid_game_date').value;
		//alert(gamedate);
		var txm1 = document.getElementById('hid_team1').value;
		var txm2 = document.getElementById('hid_team2').value;
		
		getGames(gamedate,txm1,txm2);
		//var teamx = getGames(tmx)
	}
	else
	{
		var gamedate ='<?php echo $gamedate;?>';
		var txm1 =<?php echo $team1;?>;
		var txm2 =<?php echo $team2;?>;
		 getGames(gamedate,txm1,txm2)
		
	}
	//alert("XXXX");
//	alert(tmx);
	
	//var titlex = "Team Profile: " + teamx;
	
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	
});

</script>
	<!--<div id="grid-top10"></div>
	<div id="grid-next_game"></div>
	<div id="grid-top20"></div>
	<div id="grid-standings"></div>-->            
    
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
		<input type="hidden" id="hid_game_date" name="hid_game_date" value="" /><input type="hidden" id="hid_team1" name="hid_team1" value="" /><input type="hidden" id="hid_team2" name="hid_team2" value="" />
		<div id="content">
			<h2 class="title-full">Game Leaders</h2>
			<div class="col1 col">				
				<div class="sub"><a id="a_box_point" href="#" class="on" onclick="togglebox_leaders(1);">POINTS</a> <a id="a_box_rebound" href="#" onclick="togglebox_leaders(2);">REBOUND</a> <a id="a_box_assist" href="#" onclick="togglebox_leaders(3);">ASSIST</a></div>
				<div class="padding_clear"></div>
                <div id="box_point">
                    <div class="people">
                        <ul>
                            <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($ppg1 as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
                            <li <?php echo $li_class;?>>
                                <a href="#">
                                    <h5><a href="teams_v2.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo strtoupper($team_name); ?></a></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <a href="player_v2.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
                        </ul>
                    </div>
            	</div>
                <div id="box_rebound" style="display:none;">
                    <div class="people">
                        <ul>
                            <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($rpg1 as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
                            <li <?php echo $li_class;?>>
                                <a href="#">
                                    <h5><a href="teams_v2.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo strtoupper($team_name); ?></a></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <a href="player_v2.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                RPG									
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
                        </ul>
                    </div>
            	</div>   <!--<div id="box_rebound" >-->  
                <div id="box_assist" style="display:none;">
                    <div class="people">
                        <ul>
                            <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($apg1 as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
                            <li <?php echo $li_class;?>>
                                <a href="#">
                                    <h5><a href="teams_v2.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo strtoupper($team_name); ?></a></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <a href="player_v2.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                APG									
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
                        </ul>
                    </div>
            	</div>   <!--<div id="box_assist" >-->                            
			</div>
			<div class="col1-r col">
				<div class="sub"><a id="a_box_point2" href="#" class="on" onclick="togglebox_leaders(1);">POINTS</a> <a id="a_box_rebound2" href="#" onclick="togglebox_leaders(2);">REBOUND</a> <a id="a_box_assist2" href="#" onclick="togglebox_leaders(3);">ASSIST</a></div>
				<div class="padding_clear"></div>
                <div id="box_point2">
                    <div class="people">
                        <ul>
                            <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($ppg2 as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
                            <li <?php echo $li_class;?>>
                                <a href="#">
                                    <h5><a href="teams_v2.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo strtoupper($team_name); ?></a></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <a href="player_v2.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                PPG									
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
                        </ul>
                    </div>
            	</div>
                <div id="box_rebound2" style="display:none;">
                    <div class="people">
                        <ul>
                            <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($rpg2 as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
                            <li <?php echo $li_class;?>>
                                <a href="#">
                                    <h5><a href="teams_v2.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo strtoupper($team_name); ?></a></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <a href="player_v2.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                RPG									
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
                        </ul>
                    </div>
            	</div>   <!--<div id="box_rebound" >-->  
                <div id="box_assist2" style="display:none;">
                    <div class="people">
                        <ul>
                            <!-- BEGIN TOP THREE -->
                            <?php 
                            foreach ($apg2 as $idx => $k) {
                                $image = $rimage[$idx];
                                $team_name = $k['team_name'];	
                                $player_name = $k['player_name']; 
                                $pg = @number_format($k['pg'], 1);		
                                $li_class = ($idx == 0)?'class="first"':'';
                            ?>
                            <li <?php echo $li_class;?>>
                                <a href="#">
                                    <h5><a href="teams_v2.php?team_id=<?php echo $k['team_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo strtoupper($team_name); ?></a></h5>
                                    <div class="img">
                                        <div class="img-person">
                                            <img src="images/<?php echo $image; ?>" class="rank" />
                                            <img src="images/person.png" />
                                        </div>
                                        <div class="name">
                                            <a href="player_v2.php?player=<?php echo $k['player_id']; ?>"  style="text-decoration:none; color:#FFFFFF"><?php echo $player_name; ?></a>
                                        </div>
                                    </div>
                                    <div class="point">
                                        <img src="images/tridown.png" />
                                        <div class="p_point">
                                            <div class="big">
                                                <?php echo $pg; ?>
                                            </div>
                                            <div class="small">
                                                APG									
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                            <!-- END TOP THREE -->
                        </ul>
                    </div>
            	</div>   <!--<div id="box_assist" >-->                            
			</div>


			<div class="padding_clear"></div>
		</div>
		<div id="content" class="dark-c">
			<div class="col">
				<div class="table1">
					<h2 class="title2">Game Profile</h2>
                    <div id="grid-team-stats" class="sub-box"></div>
				</div>
				<div class="table1">
					<h2 class="title2">Game Players
						<div class="links">
							<!--<a href="#" class="active">CURRENT <img src="images/arrowup3.png"></a>
							<a href="#">PAST TWO WEEKS <img src="images/arrowup3.png"></a>-->
						</div>
					</h2>
					 <div id="grid-team-rosters" class="sub-box"></div>
				</div>
                <div class="table1">
					 <h2 class="title2">Previous Game Matchup</h2>
					 <div id="grid-prev-games" class="sub-box"></div>
                </div>
			</div>
			
			<div class="clear"></div>
		</div>
		<?php include('footer.php'); ?>
		<!--<div id="footer">
			<div class="f_left">
				&copy; COPYRIGHT 2012 BH LEAGUE.com
			</div>
			<div class="f_right">
				<a href="#">HOME</a>
				<a href="#">STANDINGS</a>
				<a href="#">SCHEDULES</a>
				<a href="#">LEAGUE LEADERS</a>
				<a href="#">ROSTERS</a>
				<a href="#">PAYMENTS</a>
			</div>
			<div class="clear"></div>
		</div>-->
	</div>
  </body>
</html>

