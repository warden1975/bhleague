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
		{name: 'player'},
		//{name: 'height'},
		//{name: 'weight',},
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
		title:'Player Stats',
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
			//{
//                header   : 'Height', 
//                width    : 100, 
//                sortable : false, 
//                renderer : change, 
//                dataIndex: 'height',
//				align	 : 'left'
//            },
			//{
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

function getGame_Profile(team1,team2,gamedate,uri)
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
		title:'Game Stats',
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
			title:'Game Stats',
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
		
		storex.load();
	
		gridx.render('grid-prev-games');
		gridx.getSelectionModel().selectFirstRow();
		
		}

	function getGames(idx,tm1,tm2)
	{
		//Ext.Ajax.request({
//		params: {action: 'getteamname', team_id: idx},
//		url: 'games_callback.php',
//		success: function (resp,form,action) {
//		
//		//alert(resp.responseText) ;
		//titlex = resp.responseText;
		//teamid,title,uri
		//Ext.getCmp('teams').setValue(idx);
		//titlex = resp.responseText + ' Game Stats';
		urx ='games_callback.php?action=get_game_stats&gamedate=' + idx + '&team1=' +tm1 + '&team2=' +tm2;
		//alert(idx);
		getGame_Profile(tm1,tm2,idx,urx )
		
		idz = idx
		//titlez = resp.responseText + ' Player Stats';
		urz ='games_callback.php?action=get_game_player_roster&gamedate=' + idx + '&team1=' +tm1 + '&team2=' +tm2;
		getGame_Player_Profile(tm1,tm2,idx,urz)
		
		urq ='games_callback.php?action=get_previous_matchup&gamedate=' + idx + '&team1=' +tm1 + '&team2=' +tm2;
		getGame_Revious_Match(tm1,tm2,idx,urq)
	
		//},
		
		//failure: function (form,action) {
//			  switch (action.failureType) {
//					  case Ext.form.Action.CLIENT_INVALID:
//						 Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
//						 break;
//					  case Ext.form.Action.CONNECT_FAILURE:
//						 Ext.Msg.alert('Failure', 'Ajax communication failed');
//						 break;
//					  case Ext.form.Action.SERVER_INVALID:
//						Ext.Msg.alert('Failure', action.result.msg);
//						break;
//					  default:
//						Ext.Msg.alert('Failure',action.result.msg);
//				  }
//		}
//	});
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
	setgame_params();
	var tmz = document.getElementById('hid_game_date').value;
	if(isValidDate(tmz)==false)
	{
		
	//alert("ZZZZ");
		var gamedate = document.getElementById('hid_game_date').value;
		alert(gamedate);
		var txm1 = document.getElementById('hid_team1').value;
		var txm2 = document.getElementById('hid_team2').value;
		
		getGames(gamedate,txm1,txm2);
		//var teamx = getGames(tmx)
	}
	else
	{
		alert('<?php echo $gamedate;?>');
		 getGames('<?php echo $gamedate;?>',<?php echo $team1;?>,<?php echo $team2;?>)
		//alert(leader)
	//var tmx = document.getElementById('hid_team_id').value;
//	var teamx = getteamname(tmx);
	}
	//alert("XXXX");
//	alert(tmx);
	
	//var titlex = "Team Profile: " + teamx;
	
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	
});