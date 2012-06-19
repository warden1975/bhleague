<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'admin/class/db.cls.php');
define('COMMON', DIR . 'bhlcommon.php');

require DB_CLS;
require COMMON;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

//$team_id = 0;

extract($_REQUEST);

$current_year = date("Y");
$previous_year = $current_year -1;

$array = array();

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


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Team Profile</title>

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
<!--<script type="text/javascript" charset="utf-8" src="js/placeholder.js"></script>-->
<!--<script type="text/javascript" charset="utf-8" src="js/main.js"></script>-->
<!-- ** CSS ** -->
<!-- base library -->
<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
<!-- overrides to base library -->
<!-- page specific -->
<!--link rel="stylesheet" type="text/css" href="extjs/shared/examples.css" /-->
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
<!-- overrides to base library -->
<script type="text/javascript" src="standings.js"></script>
<script type="text/javascript" src="bhlcommon.js"></script>
</head>

<body >
<script>
function setteam_id()
{
	var team_id= FORM_DATA['team_id'];
	document.getElementById('hid_team_id').value = team_id
	
}
</script> 
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
 function getTeam_Player_Profile(teamid,title,uri)
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
		title:title,
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

function getTeam_Profile(teamid,title,uri,sz)
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
	
		teamx = new Ext.data.SimpleStore({
	        fields: ['id', 'team_name'],
		    data : <?php echo $z; ?>
	    });
		//teamStore.load();
		//Ext.getCmp('teams').setValue(teamid);
		
		 
          var seasonStore = new Ext.data.SimpleStore({
            fields: ['id','description'],
            data: [
              ["1","Current Season"],["2","Previous Season"]               
                  ]
			//listeners: {
//                load: {
//                        fn: function() {
//                                Ext.getCmp('season').setValue(sz);
//                        }
//                }
//        },
			
        });
	
    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
		title:title,
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
				align	 : 'left'
            },
			{
                header   : 'Team 2', 
                width    : 160, 
                sortable : false, 
                dataIndex: 'team2',
				renderer : Ext.bhlcommondata.format_underline,
				align	 : 'left'
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
		tbar: [{
				xtype: 'combo',
				ref:'teams',
				id:'teams',
				store: teamx,
				displayField: 'team_name',
				valueField: 'id',
				selectOnFocus: true,
				mode: 'local',
				typeAhead: true,
				editable: false,
				triggerAction: 'all',
				value:teamid,
				emptyText:'Select Team Profile',
				width:160,
				//float: false,
				listeners: {
					
					select: function(combo, record, index) {
						
						document.getElementById('grid-team-stats').innerHTML ="";
						document.getElementById('grid-team-rosters').innerHTML ="";
						var idx = Ext.getCmp('season').value;
						var teamidz = combo.getValue();
						var title = combo.getRawValue()
						
						if(idx=='1')
						{
							
//							
//							alert(idx);
//							
							title_teamx = title  + ':( Game Stats Current Season ) ';
							//alert(title_teamx);
							title_player = title + ':( Player Stats Current Season) ';
//
							urx ='teams_callback.php?action=get_team_stats_current&team_id=' + teamidz
							urz ='teams_callback.php?action=get_team_roster_current&team_id=' + teamidz
							//sx =idx
							getTeam_Profile(teamidz,title_teamx,urx,idx )
							getTeam_Player_Profile(teamidz,title_player,urz)
							//alert(teamidz)
							getTeamLogo(teamidz)
							//combo.setValue(teamidz)
							
						}
						else if(idx=='2')
						{
							//teamidz = combo.getValue()
							//alert(idx);
							title_teamx = title+ ':( Game Stats Previous Season ) ';
							title_player = title + ':( Player Stats Previous Season) ';
							urx ='teams_callback.php?action=get_team_stats_previous&team_id=' + teamidz
							urz ='teams_callback.php?action=get_team_roster_previous&team_id=' + teamidz
							//sx =idx
							getTeam_Profile(teamidz,title_teamx,urx,idx )
							getTeam_Player_Profile(teamidz,title_player,urz)
							getTeamLogo(teamidz)
							//combo.setValue(teamidz)
							
						}

					}
					
				}
    
			},'-',{
				xtype: 'combo',
				ref:'season',
				id:'season',
				store: seasonStore,
				fieldLabel: 'Select Season',
				displayField: 'description',
				valueField: 'id',
				selectOnFocus: true,
				mode: 'local',
				typeAhead: true,
				editable: false,
				triggerAction: 'all',
				value:sz,
				listeners: {
					
					select: function(combo, record, index) 
					{
						
							document.getElementById('grid-team-stats').innerHTML ="";
							document.getElementById('grid-team-rosters').innerHTML ="";
							var titlev = Ext.getCmp('teams').getRawValue();
							var idx = Ext.getCmp('teams').value;
							var sx = combo.getValue()
							
							if(sx=='1')
							{
								
								titlex = Ext.getCmp('teams').getRawValue() + ' (Game Stats: Current Season)';
								
								urx ='teams_callback.php?action=get_team_stats_current&team_id=' + idx;
								
								getTeam_Profile(idx,titlex,urx,sx )
								
								idz = idx
								titlez = titlev + ' (Player Stats: Current Season)';
								urz ='teams_callback.php?action=get_team_roster_current&team_id=' + idx;
								getTeam_Player_Profile(idz,titlez,urz )
								combo.setValue(sx)
							}
	
							else if(sx=='2')
							{
								//var titlev = Ext.getCmp('teams').getRawValue();
//								var idx = Ext.getCmp('teams').value;
								titlex = titlev + ' (Game Stats: Previous Season)';
								//alert(titlev);
								urx ='teams_callback.php?action=get_team_stats_previous&team_id=' + idx;
								//alert(idx);
								getTeam_Profile(idx,titlex,urx,sx )
								
								idz = idx
								titlez = titlev + ' (Player Stats: Previous Season)';
								urz ='teams_callback.php?action=get_team_roster_previous&team_id=' + idx;
								getTeam_Player_Profile(idz,titlez,urz )
								combo.setValue(sx)
							}
		
						}
	
					}
					
				}],
    
			
        stripeRows: true,
        autoExpandColumn: 'game_date',
        listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);  // Get the Record
					
					//alert(columnIndex);
					//var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
					if(columnIndex==2)
					{
					var data = record.get('team1x');
					window.location.href='teams.php?team_id='+data;
					}
					else if(columnIndex==3)
					{
					var data = record.get('team2x');
					window.location.href='teams.php?team_id='+data;	
					}
					//window.location.href='teams.php?team_id='+data2;
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

	function getteamname(idx,si)
	{
		if(si==null)
		{
			si='1';
		}
		//alert(idx);
		Ext.Ajax.request({
		params: {action: 'getteamname', team_id: idx},
		url: 'teams_callback.php',
		success: function (resp,form,action) {
		
		//alert(resp.responseText) ;
		//titlex = resp.responseText;
		//teamid,title,uri
		//Ext.getCmp('teams').setValue(idx);
		titlex = resp.responseText + ' (Game Stats: Current Season)';
		urx ='teams_callback.php?action=get_team_stats_current&team_id=' + idx;
		//alert(idx);
		getTeam_Profile(idx,titlex,urx,si )
		
		idz = idx
		titlez = resp.responseText + ' (Player Stats: Current Season)';
		urz ='teams_callback.php?action=get_team_roster_current&team_id=' + idx;
		getTeam_Player_Profile(idz,titlez,urz )
		//}
//		else if(seasonx=='2')
//		{
		//getTeam_Player_Profile(idx,titlex + ' (Player Stats: Current Season)','teams_callback.php?action=get_team_roster_current')		
		//}
		//getTeam_Player_Profile_Prev(idx)
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
	function getTeamLogo(teamid)
	{
		var bhl_img_url = 'teams_callback.php?action=getteam_logo&team_id=' + teamid; 
	
	var bhl_store = new Ext.data.JsonStore ({
		url: bhl_img_url,
		root: 'logo',
		fields: ['team', 'path']
	});
	bhl_store.load({
		callback: function(records, operation, success) {
        	var path = records[0].get('path');
			var team = records[0].get('team');
			//console.log('url: '+bhl_img_url+', img_path: '+path+', team: '+team);
			Ext.get('bhlogo_img').dom.src = path;
			Ext.fly('bhlogo_team').update(team);
    	}
	});
	}
	function getteam_leader()
	{
		Ext.Ajax.request({
		params: {action: 'getteam_leader'},
		url: 'teams_callback.php',
		success: function (resp,form,action) {
		
		//Ext.getCmp('season').setValue('1');
		getteamname(resp.responseText,'1')
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
	//setteam_id();
	var tmz = document.getElementById('hid_team_id').value;
	if(isNaN(tmz)==false)
	{
		
			//alert("CCCC");
		var tmx = document.getElementById('hid_team_id').value;
		
		var teamx = getteamname('<?php echo $_REQUEST['team_id']; ?>')
	}
	else
	{
		
		var leader = getteam_leader();
		//alert(leader)
	//var tmx = document.getElementById('hid_team_id').value;
//	var teamx = getteamname(tmx);
	}
	//alert("XXXX");
//	alert(tmx);
	
	
	
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	// Get Team Logo image path - WG
	//var bhl_tid = document.getElementById('hid_team_id').value;
	var bhl_img_url = 'teams_callback.php?action=getteam_logo&team_id=' + <?php echo $_REQUEST['team_id']; ?> 
	
	var bhl_store = new Ext.data.JsonStore ({
		url: bhl_img_url,
		root: 'logo',
		fields: ['team', 'path']
	});
	bhl_store.load({
		callback: function(records, operation, success) {
        	var path = records[0].get('path');
			var team = records[0].get('team');
			//alert('url: '+bhl_img_url+', img_path: '+path+', team: '+team);
			Ext.get('bhlogo_img').dom.src = path;
			Ext.fly('bhlogo_team').update(team);
    	}
	});
	//
	
});
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
    <div id="game_league_id">
      <input type="text" id="local-gamedays" size="20"/>
      <input type="hidden" id="hid_team_id" name="hid_team_id" value="" />
    </div>
    <div class="clear"></div>
  </div>
</div>
<div id="content">
  <div class="wrap1 bg-white">
    <div class="wrap">
      <div class="content-top">
        <div class="f_left">
          <div class="box bg1 auto-height">
            <h3>Team Profile</h3>
            <div class="sub-box">
              <div class="tabs">
                <div class="clear"></div>
              </div>
              <div class="people">
                <div class="logo2">
                  <div class="img"> <img id="bhlogo_img" src="images/no-profile.gif" />
                    <div id="bhlogo_team" class="the-info">  </div>
                  </div>
                </div>
                <div class="clear"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="content-top">
        <div class="box">
          <h4><span>BHL</span> - TEAM PROFILE</h4>
          <div id="grid-team-stats"></div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="content-top">					
		<div class="box">
            <h4><span>BHL</span> - TEAM PLAYERS</h4>
            <div id="grid-team-rosters"></div>
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
</html>
