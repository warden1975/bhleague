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
$pg = $r = array();
$lg = array();

extract($_REQUEST);

$m = strtoupper($m);

switch ($m) {
	default:
	case 'PPG':
		$m_sql = "sum(if(a.player_id=b.id, ((game_points_1*1) + (game_points_2*2) + (game_points_3*3)), 0))";
		$mm = 'Points';
		$m_class = '<a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=PPG" class="on">POINTS</a> | <a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=RPG">REBOUND</a> | <a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=APG">ASSIST</a>';
		break;
	case 'APG':
		$m_sql = "sum(if(a.player_id=b.id, game_assists, 0))";
		$mm = 'Assists';
		$m_class = '<a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=PPG">POINTS</a> | <a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=RPG">REBOUND</a> | <a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=APG" class="on">ASSIST</a>';
		break;
	case 'RPG':
		$m_sql = "sum(if(a.player_id=b.id, game_rebounds, 0))";
		$mm = 'Rebounds';
		$m_class = '<a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=PPG">POINTS</a> | <a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=RPG" class="on">REBOUND</a> | <a href="http://www.bhleague.com/games.php?gamedate='.$gamedate.'&team1='.$team1.'&team2='.$team2.'&m=APG">ASSIST</a>';
		break;
}

$sql = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name, 
{$m_sql} as score, 
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
where b.team_id = c.id and a.game_date = '$gamedate' and b.team_id = $team1   
group by b.id 
order by score desc) as ave 
order by pg desc limit 3";

//echo $sql;exit;
		
if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_assoc()) {
			$pg[] = $row;
		}
	}
	$rs->close();
}

$sql = "select ave.player_id, ave.player_name, ave.team_name, ave.score, round((ave.score / ave.games_played), 2) as pg from (
select b.id as player_id, upper(concat(substr(b.player_fname,1,1), '. ', b.player_lname)) as player_name, 
c.team_name, 
{$m_sql} as score, 
sum(if(a.player_id=b.id, 1, 0)) as games_played
from bhleague.players_stats a, bhleague.players b, bhleague.teams c 
where b.team_id = c.id and a.game_date = '$gamedate' and b.team_id = $team2   
group by b.id 
order by score desc) as ave 
order by pg desc limit 3";
		
if ($rx = $db->query($sql)) {
	$rs_cntx = $rx->num_rows;
	if ($rs_cntx > 0) {
		while ($rowx = $rx->fetch_assoc()) {
			$lg[] = $rowx;
		}
	}
	$rx->close();
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
<title>GAME PROFILE</title>

<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" title="style" charset="utf-8" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="style" charset="utf-8" />
<link href='http://fonts.googleapis.com/css?family=Coda|Open+Sans:400italic,600italic,400,600,700' rel='stylesheet' type='text/css'>
<!--[if IE 6]>
    <link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
<!--[if IE 7]>
    <link rel="stylesheet" href="css/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
<script type="text/javascript" charset="utf-8" src="js/jq.js"></script>
<script type="text/javascript" charset="utf-8" src="js/placeholder.js"></script>
<script type="text/javascript" charset="utf-8" src="js/main.js"></script>

<!-- ** CSS ** -->
<!-- base library -->
<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
<!-- overrides to base library -->
<!-- page specific -->
<!--link rel="stylesheet" type="text/css" href="extjs/examples/shared/examples.css" /-->
<link rel="stylesheet" type="text/css" href="extjs/examples/grid/grid-examples.css" />
<style type=text/css>
/* style rows on mouseover */
    .x-grid3-row-over .x-grid3-cell-inner {
	font-weight: bold;
}
/* style for the "buy" ActionColumn icon */
    .x-action-col-cell img.buy-col {
	height: 16px;
	width: 16px;
	background-image: url(extjs/examples/shared/icons/fam/accept.png);
}
/* style for the "alert" ActionColumn icon */
    .x-action-col-cell img.alert-col {
	height: 16px;
	width: 16px;
	background-image: url(extjs/examples/shared/icons/fam/error.png);
}
</style>

<!-- ** Javascript ** -->
<!-- ExtJS library: base/adapter -->
<script type="text/javascript" src="extjs/adapter/ext/ext-base.js"></script>
<!-- ExtJS library: all widgets -->
<script type="text/javascript" src="extjs/ext-all.js"></script>
<!-- Overrides to base library -->
<!--<script type="text/javascript" src="games.js"></script>-->
<script type="text/javascript" src="bhlcommon.js"></script>
</head>
<body >
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
		{name: 'height'},
		{name: 'weight',},
		{name: 'position'},
		{name: 'ppg', type: 'float'},
		{name: 'rpg', type: 'float'},
		{name: 'apg', type: 'float'},
		{name: 'games', type: 'float'}
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
			{
                header   : 'Height', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'height',
				align	 : 'left'
            },
			{
                header   : 'Weight', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'weight',
				align	 : 'center'
            },
			{
                header   : 'Position', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'position',
				align	 : 'center'
            },
			{
                header   : 'PPG', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'ppg',
				align	 : 'center'
            },
			{
                header   : 'RPG', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'rpg',
				align	 : 'center'
            },
			{
                header   : 'APG', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'apg',
				align	 : 'center'
            },
			{
                header   : 'Games Played', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'games',
				align	 : 'center'
            }
        ],
        stripeRows: true,
        autoExpandColumn: 'player',
         listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);  // Get the Record
					//var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
					var data = record.get('player_id');
					//console.log('fieldName: '+fieldName+', data: '+data);
					window.location.href='player.php?player='+data;
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
					window.location.href='teams.html?team_id='+data;
					}
					else if(columnIndex==3)
					{
					//alert(columnIndex);
					var data = record.get('team2x');
					window.location.href='teams.html?team_id='+data;	
					}
					//window.location.href='teams.html?team_id='+data2;
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
					window.location.href='teams.html?team_id='+data;
					}
					else if(columnIndex==3)
					{
					//alert(columnIndex);
					var data = record.get('team2x');
					window.location.href='teams.html?team_id='+data;	
					}
					//window.location.href='teams.html?team_id='+data2;
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
		//alert(leader)
	//var tmx = document.getElementById('hid_team_id').value;
//	var teamx = getteamname(tmx);
	}
	//alert("XXXX");
//	alert(tmx);
	
	//var titlex = "Team Profile: " + teamx;
	
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	
});
//function setgame_params()
//{
//	var game_date= FORM_DATA['game_date'];
//	var tmx1 = FORM_DATA['team1'];
//	var tmx2 = FORM_DATA['team2'];
//	document.getElementById('hid_game_date').value = game_date;
//	document.getElementById('hid_team1').value = tmx1;
//	document.getElementById('hid_team2').value = tmx2;
//	
//	}
</script>
<div id="header">
  <div class="wrap">
    <h1 class="logo"><a href="index.php"><img src="images/logo.png" /></a></h1>
    <div class="menu"><a href="index.php" class="first">HOME</a> <a href="standings.html">STANDINGS</a> <a href="schedule.html">SCHEDULES</a> <a href="league_leaders.html">LEAGUE LEADERS</a> <a href="rosters.php">ROSTERS</a> <a href="payments.html" class="last">PAYMENTS</a>
      <div class="clear"></div>
    </div>
  </div>
</div>
<div id="header2">
  <div class="wrap">
  	<div id="game_league_id"><input type="text" id="local-gamedays" size="20"/><input type="hidden" id="hid_game_date" name="hid_game_date" value="" /><input type="hidden" id="hid_team1" name="hid_team1" value="" /><input type="hidden" id="hid_team2" name="hid_team2" value="" /></div>
    <div class="clear"></div>
  </div>
</div>
<div id="content">
  <div class="wrap1 bg-white">
    <div class="wrap">
      <div class="content-top">
          <div class="box bg1 same-width">
            <h3>BHL GAME LEADERS</h3>
			<div class="f_left2">
            	<div class="sub-box">
              <div class="tabs"> <a href="rosters.html" class="f_right">VIEW SCORE</a> <?php echo $m_class; ?>
                <div class="clear"></div>
              </div>
              <div class="people">
              <?php
			  $first = true;
			  foreach ($pg as $k) {
			  if ($first) {
			  ?>
                <div class="person first">
              <?php
			  	$first = false;
			  } else {
              ?>
                <div class="person">
              <?php
			  }
              ?>
                  <div class="img">
                    <div class="the-info"> <?php echo $k['team_name']; ?> </div>
                    <img src="images/no-profile.gif" /> </div>
                  <div class="detail">
                    <div class="f_left"> <a href="player.php?player=<?php echo $k['player_id']; ?>" class="f_left" style="text-decoration:none; color:#FFF"><?php echo $k['player_name']; ?></a> </div>
                    <div class="f_right"> <?php echo @number_format($k['pg'], 1); ?> <?php echo $m; ?> </div>
                    <div class="clear"></div>
                  </div>
                </div>
              <?php
			  }
			  ?>
                <div class="clear"></div>
              </div>
            </div>
			</div>
			<div class="f_right2">
            	<div class="sub-box">
              <div class="tabs"> <a href="rosters.html" class="f_right">VIEW SCORE</a> <?php echo $m_class; ?>
                <div class="clear"></div>
              </div>
              <div class="people">
              <?php
			  $first = true;
			  foreach ($lg as $kg) {
			  if ($first) {
			  ?>
                <div class="person first">
              <?php
			  	$first = false;
			  } else {
              ?>
                <div class="person">
              <?php
			  }
              ?>
                  <div class="img">
                    <div class="the-info"> <?php echo $kg['team_name']; ?> </div>
                    <img src="images/no-profile.gif" /> </div>
                  <div class="detail">
                    <div class="f_left"> <a href="player.php?player=<?php echo $kg['player_id']; ?>" class="f_left" style="text-decoration:none; color:#FFF"><?php echo $kg['player_name']; ?></a> </div>
                    <div class="f_right"> <?php echo @number_format($kg['pg'], 1); ?> <?php echo $m; ?> </div>
                    <div class="clear"></div>
                  </div>
                </div>
              <?php
			  }
			  ?>
                <div class="clear"></div>
              </div>
            </div>
			</div>
			<div class="clear"></div>
          </div>

		 <div class="clear"></div>
         <div class="box">		
       <!--   <h3>BHL GAME PROFILE</h3>-->
          		<h4><span>BHL GAME PROFILE</span></h4>
	          <div class="sub-box">
	            <div id="grid-team-stats"></div>
			  </div>
		  </div>
         <div class="box">		
       <!--   <h3>BHL GAME PROFILE</h3>-->
          		<h4><span>BHL GAME PLAYERS</span></h4>
	          <div class="sub-box">
	            <div id="grid-team-rosters"></div>
			  </div>
		  </div>
         <div class="box">		
       <!--   <h3>BHL GAME PROFILE</h3>-->
          		<h4><span>BHL PREVIOUS GAME MATCHUP</span></h4>
	          <div class="sub-box">
	            <div id="grid-prev-games"></div>
			  </div>
		  </div>

			
			 
          </div>
        </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<div id="footer">
  <div class="wrap">
    <div class="f_left"> &copy; COPYRIGHT 2012 BH LEAGUE.com </div>
    <div class="f_right"> <a href="index.php" class="first">HOME</a> <a href="#">MOBILE</a> <a href="#">TERMS OF USE</a> <a href="#">PRIVACY POLICY</a> <a href="#">FAQ</a> <a href="#" class="last">CONTACT</a> </div>
    <div class="clear"></div>
  </div>
</div>
</body>
