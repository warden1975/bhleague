/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.onReady(function(){
    Ext.QuickTips.init();

    // NOTE: This is an example showing simple state management. During development,
    // it is generally best to disable state management as dynamically-generated ids
    // can change across page loads, leading to unpredictable results.  The developer
    // should ensure that stable state ids are set for stateful components in real apps.    
    //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	/*Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
            expires: new Date(new Date().getTime()+(1000*60*60*24*365)) // 1 year
        }));*/

    /**
     * Custom function used for column renderer
     * @param {Object} val
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
	//$dx[] = "['{$player_name}', '{$game_date}', '{$team1}','{$team2}', '{$score}',  {$points}, {$rebounds}, {$assists}]";
	var store = new Ext.data.ArrayStore({
		fields: ['season', 'team_id', 'team', 'games', 'ppg', 'rpg', 'apg'],
		data: Ext.bhlcommondata.player_stats
	});
	
	var storex = new Ext.data.ArrayStore({
		fields: [ 'game_date', 'team_id', 'position','team1x','team1','team2x','team2', 'score',  'points', 'rebounds','assists'],
		data: Ext.bhlcommondata.player_games
	});

    // create the Grid
    var gridx = new Ext.grid.GridPanel({
        store: storex,
        columns: [
				  
            
			{
                id       :'game_date',
                header   : 'Game Date', 
                width : 150,
                sortable : false, 
                dataIndex: 'game_date'
            },
            {
                header   : '', 
                //width    : 190, 
                sortable : false, 
				hidden   : true,
                //renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'team_id',
				align	 : 'center'
            },
			{
                header   : 'Position', 
                width    : 100, 
                sortable : false, 
                //renderer : change, 
                dataIndex: 'position',
				align	 : 'center'
            },
			{
                header   : 'team1x', 
                //width    : 190, 
                sortable : false, 
				hidden   : true,
                //renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'team1x',
				align	 : 'center'
            },
            {
                header   : 'Team 1', 
                width    : 140, 
                sortable : false, 
                //renderer : change, 
                dataIndex: 'team1',
				align	 : 'center'
            },
			{
                header   : 'team2x', 
                width    : 60, 
                sortable : false, 
				hidden   : true,
                //renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'team2x',
				align	 : 'center'
            },
            {
                header   : 'Team 2', 
                width    : 140, 
                sortable : false, 
                //renderer : change, 
                dataIndex: 'team2',
				align	 : 'center'
            },
			{
                header   : 'Score', 
                width    : 100, 
                sortable : false, 
                //renderer : change, 
                dataIndex: 'score',
				align	 : 'center'
            },
			{
                header   : 'Points', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'points',
				align	 : 'center'
            },
			{
                header   : 'Rebounds', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'rebounds',
				align	 : 'center'
            },
			{
                header   : 'Assists', 
                width    : 100, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'assists',
				align	 : 'center'
            }
        ],
        stripeRows: true,
       // autoExpandColumn: 'season',
		border: false,
		autoHeight: true,
		layout: 'fit',
		listeners: {
			cellclick: function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex);  // Get the Record
//
//				var data = record.get('team_id');
//				window.location.href='teams_v2.php?team_id='+data;
              //alert(columnIndex)
			  if(columnIndex==4)
			  {
				  var data1 = record.get('team1x');
				  //alert(record.get('team1x'));
				  window.location.href='teams_v2.php?team_id='+data1;
			  }
			  else if(columnIndex==6)
			  {
				 var data2 = record.get('team2x');
				 //alert(data);
				  window.location.href='teams_v2.php?team_id='+data2; 
			  }
		
			}
		},
        // config options for stateful behavior
        stateful: true,
        stateId: 'gridx'
    });
	
	//store.load();

    // render the grid to the specified div in the page
    gridx.render('grid-player-stats-game');
	
	
	var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {
                id       :'season',
                header   : 'Season', 
                width    : 100, 
                sortable : false, 
                dataIndex: 'season'
            },
			{
                id       :'team_id',
                header   : 'Team', 
                hidden   : true, 
                sortable : false, 
                dataIndex: 'season'
            },
            {
                header   : 'Team', 
                width    : 190, 
                sortable : false, 
                renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'team',
				align	 : 'center'
            },
            {
                header   : 'Games Played', 
                width    : 190, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'games',
				align	 : 'center'
            },
            {
                header   : 'PPG', 
                width    : 150, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'ppg',
				align	 : 'center'
            },
			{
                header   : 'RPG', 
                width    : 150, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'rpg',
				align	 : 'center'
            },
			{
                header   : 'APG', 
                width    : 150, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'apg',
				align	 : 'center'
            }
        ],
        stripeRows: true,
       // autoExpandColumn: 'season',
		border: false,
		autoHeight: true,
		layout: 'fit',
		listeners: {
			cellclick: function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex);  // Get the Record

				var data = record.get('team_id');
				window.location.href='teams_v2.php?team_id='+data;
		
			}
		},
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid'
    });
	
	//store.load();

    // render the grid to the specified div in the page
    grid.render('grid-player-stats-season');
	
	//grid.getSelectionModel().selectFirstRow();
});