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
 function getTeam_Player_Profile(teamid)
	{
	var reader = new Ext.data.ArrayReader({}, [
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
		url: 'teams.php?action=get_team_roster_current&team_id=' + teamid, //url to data object (server side script)
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
		viewConfig:{
        emptyText:'No records to display'
    },
        columns: [
            {
                id       :'player',
                header   : 'Player', 
                width    : 160, 
                sortable : false, 
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
        /*height: 250,
        width: 600,*/
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
	function getTeam_Player_Profile_Prev(teamid)
	{
	var reader = new Ext.data.ArrayReader({}, [
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
		url: 'teams.php?action=get_team_roster_previous&team_id=' + teamid, //url to data object (server side script)
		method: 'GET'
	});
        
    // create the data store.
    var store2 = new Ext.data.Store({
    	reader: reader,
        proxy: proxy
    });

    // create the Grid
    var grid2 = new Ext.grid.GridPanel({
        store: store2,
		viewConfig:{
        emptyText:'No records to display'
    },
        columns: [
            {
                id       :'player',
                header   : 'Player', 
                width    : 160, 
                sortable : false, 
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
        /*height: 250,
        width: 600,*/
		autoHeight: true,
		border: false,
		layout: 'fit',
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid2'
    });
	
	store2.load();

    grid2.render('grid-team-rosters-prev');
	grid2.getSelectionModel().selectFirstRow();
	}
function getTeam_Profile(teamid,title)
	{
	var ds_model = Ext.data.Record.create([
		{name: 'game_date'},
		{name: 'game_time'},
		{name: 'team1',},
		{name: 'team2'},
		{name: 'score'}
	]);
	
	// get the data
   store = new Ext.data.Store({
			url: 'teams.php?action=get_team_stats_current&team_id=' + teamid,
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				id:'id'
			}, ds_model)
	    });
	
		var teamStore = new Ext.data.JsonStore({
		url: 'teams.php',
		baseParams:{action: 'getAllTeam'},
		root:'teams',
		fields:['id','team_name']
	
		});
	function myRenderer( val, meta, record, rowIndex, colIndex, store )
	 {
	    var rawscore = record.get("score");
		var score = rawscore.split(" - ")
		if(parseInt(score[0])> parseInt(score[1]))
		{
			return '<span style="color:green;">' + val + '%</span>';
		}
		else
		{
			return '<span style="color:red;">' + val + '%</span>';
		}
											 
		
	 }
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
				//renderer:  myRenderer,
				align	 : 'center'
            },
			{
                header   : 'Team 2', 
                width    : 160, 
                sortable : false, 
                dataIndex: 'team2',
				//renderer: myRenderer,
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
		tbar: [{
				xtype: 'combo',
				id:'teams',
				triggerAction: 'all',
				store: teamStore,
				valueField:'id',
				displayField:'team_name',
				typeAhead: true,
				forceSelection:true,
				mode: 'remote',
				emptyText:'Select Team Profile',
				width:160,
				float: false,
				listeners: {
					
					select: function(combo, record, index) {
						
						document.getElementById('grid-team-stats').innerHTML ="";
						document.getElementById('grid-team-rosters').innerHTML ="";
						document.getElementById('grid-team-stats-prev').innerHTML ="";
						document.getElementById('grid-team-rosters-prev').innerHTML ="";
						titlex = combo.getRawValue();
						getTeam_Profile(combo.getValue(),combo.getRawValue() +' (Current Season) ')
						getTeam_Player_Profile(combo.getValue())
						getTeam_Profile_Prev(combo.getValue(),titlex +' (Previous Season) ')
						getTeam_Player_Profile_Prev(combo.getValue() )
						
						
						
						
					}
					
				}
    
			}],
        stripeRows: true,
        autoExpandColumn: 'game_date',
        /*height: 250,
        width: 600,*/
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
	function getTeam_Profile_Prev(teamid,title)
	{
	var ds_model = Ext.data.Record.create([
		{name: 'game_date'},
		{name: 'game_time'},
		{name: 'team1',},
		{name: 'team2'},
		{name: 'score'}
	]);
	
	// get the data
   store4 = new Ext.data.Store({
			url: 'teams.php?action=get_team_stats_previous&team_id=' + teamid,
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				id:'id'
			}, ds_model)
	    });
	
		var teamStore = new Ext.data.JsonStore({
		url: 'teams.php',
		baseParams:{action: 'getAllTeam'},
		root:'teams',
		fields:['id','team_name']
	
		});
	
    // create the Grid
    var grid4 = new Ext.grid.GridPanel({
        store: store4,
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
				align	 : 'center'
            },
			{
                header   : 'Team 2', 
                width    : 160, 
                sortable : false, 
                dataIndex: 'team2',
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
        /*height: 250,
        width: 600,*/
		autoHeight: true,
		border: false,
		layout: 'fit',
        // config options for stateful behavior

        stateful: true,
        stateId: 'grid'
    });
	
	store4.load();

    grid4.render('grid-team-stats-prev');
	grid4.getSelectionModel().selectFirstRow();
	
	}
	function getteamname(idx)
	{
		Ext.Ajax.request({
		params: {action: 'getteamname', team_id: idx},
		url: 'teams.php',
		success: function (resp,form,action) {
		
		//alert(resp.responseText) ;
		titlex = resp.responseText;
		getTeam_Profile(idx,titlex + ' (Current Season)')
		getTeam_Profile_Prev(idx,titlex + ' (Previous Season)')
		getTeam_Player_Profile(idx)
		getTeam_Player_Profile_Prev(idx)
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
	function getteam_leader()
	{
		Ext.Ajax.request({
		params: {action: 'getteam_leader'},
		url: 'teams.php',
		success: function (resp,form,action) {
		
		//alert(resp.responseText) ;
		getteamname(resp.responseText)
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
	setteam_id();
	var tmz = document.getElementById('hid_team_id').value;
	if(isNaN(tmz)==false)
	{
		
	//alert("ZZZZ");
		var tmx = document.getElementById('hid_team_id').value;
		var teamx = getteamname(tmx)
	}
	else
	{
		//alert("CCCC");
		var leader = getteam_leader();
		//alert(leader)
	//var tmx = document.getElementById('hid_team_id').value;
//	var teamx = getteamname(tmx);
	}
	//alert("XXXX");
//	alert(tmx);
	
	//var titlex = "Team Profile: " + teamx;
	
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	
});