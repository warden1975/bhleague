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
	
	// tell the store how to process the data
	var reader = new Ext.data.ArrayReader({}, [
		{name: 'team_id', type: 'int'},									   
		{name: 'team'},
		{name: 'wins', type: 'float'},
		{name: 'losses', type: 'float'},
		{name: 'win_pct', type: 'float'},
		{name: 'pt_diff', type: 'float'},
		{name: 'gb', type: 'float'}
	]);
	
	var reader_sched = new Ext.data.ArrayReader({}, [
		{name: 'playing'},
		{name: 'game_time'}
	]);
	
	var reader_top20 = new Ext.data.ArrayReader({}, [
		{name: 'player_id'},
		{name: 'player'},
		{name: 'height'},
		{name: 'weight',},
		{name: 'position'},
		{name: 'ppg', type: 'float'},
		{name: 'rpg', type: 'float'},
		{name: 'apg', type: 'float'},
		{name: 'games', type: 'float'}
	]);
	
	var reader_top10 = new Ext.data.ArrayReader({}, [
		{name: 'player_id'},
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
		url: 'standings.php', //url to data object (server side script)
		method: 'GET'
	});
	
	var proxy_sched = new Ext.data.HttpProxy({
		//where to retrieve data
		url: 'next_game.php', //url to data object (server side script)
		method: 'GET'
	});
	
	var proxy_top20 = new Ext.data.HttpProxy({
		url: 'rosters_callback.php?action=top20', //url to data object (server side script)
		method: 'GET'
	});
	
	var proxy_top10 = new Ext.data.HttpProxy({
		url: 'rosters_callback.php?action=top10', //url to data object (server side script)
		method: 'GET'
	});
        
    // create the data store.
    var store = new Ext.data.Store({
    	reader: reader,
        proxy: proxy
    });
	
	var store_sched = new Ext.data.Store({
    	reader: reader_sched,
        proxy: proxy_sched
    });
	
	var store_top20 = new Ext.data.Store({
    	reader: reader_top20,
        proxy: proxy_top20
    });
	
	var store_top10 = new Ext.data.Store({
    	reader: reader_top10,
        proxy: proxy_top10
    });

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
			{
                id       :'team_id',
                header   : '', 
                width    : 160, 
                sortable : false, 
				hidden 	 : true,
                dataIndex: 'team_id'
            },
            {
                id       :'team',
                header   : 'Team', 
                width    : 160, 
                sortable : false, 
				renderer : Ext.bhlcommondata.format_underline, 
                dataIndex: 'team'
            },
            {
                header   : 'Wins', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'wins',
				align	 : 'center'
            },
            {
                header   : 'Losses', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'losses',
				align	 : 'center'
            },
            {
                header   : 'Win %', 
                width    : 75, 
                sortable : false, 
                renderer : pctChange, 
                dataIndex: 'win_pct',
				align	 : 'center'
            },
			{
                header   : 'Point Diff.', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'pt_diff',
				align	 : 'center'
            },
			{
                header   : 'GB', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'gb',
				align	 : 'center'
            }
        ],
        stripeRows: true,
        autoExpandColumn: 'team',
		listeners: {
			cellclick: function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex);  // Get the Record

				var data = record.get('team_id');
				window.location.href='teams.html?team_id='+data;
		
			}
		},
		border: false,
		autoHeight: true,
		layout: 'fit',
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid'
    });
	
	var grid_sched = new Ext.grid.GridPanel({
        store: store_sched,
        columns: [
            {
				id		 : 'playing', 
                header   : 'Teams', 
                width    : 150, 
                sortable : false, 
                dataIndex: 'playing',
				renderer : Ext.bhlcommondata.format_underline,
				align	 : 'left'
            },
            {
                header   : 'Time (PST)', 
                width    : 75, 
                sortable : false,
                dataIndex: 'game_time',
				align	 : 'left'
            }
        ],
        stripeRows: true,
        autoExpandColumn: 'playing',
		border: false,
		autoHeight: true,
		layout: 'fit',
        stateful: true,
        stateId: 'grid'
    });
	
	var grid_top20 = new Ext.grid.GridPanel({
        store: store_top20,
        columns: [
			{
                id       : 'player_id',
                header   : 'ID',
                sortable : false, 
                dataIndex: 'player_id', 
				hidden	 : true
            },
            {
                header   : 'Player', 
                width    : 315, 
                sortable : false,
				renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'player'
            },
			{
                header   : 'Height', 
                hidden	 : true, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'height',
				align	 : 'left'
            },
			{
                header   : 'Weight', 
                hidden	 : true, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'weight',
				align	 : 'center'
            },
			{
                header   : 'Position', 
                hidden	 : true, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'position',
				align	 : 'center'
            },
			{
                header   : 'PPG', 
                width    : 95, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'ppg',
				align	 : 'center'
            },
			{
                header   : 'RPG', 
                width    : 95, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'rpg',
				align	 : 'center'
            },
			{
                header   : 'APG', 
                width    : 95, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'apg',
				align	 : 'center'
            },
			{
                header   : 'Games Played', 
                width    : 95, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'games',
				align	 : 'center'
            }
        ],
        stripeRows: true,
        //autoExpandColumn: 'player',
		autoHeight: true,
		border: false,
		layout: 'fit',
		listeners: {
			cellclick: function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex);
				var data = record.get('player_id');
				window.location.href='player.php?player='+data;
			}
		},
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid'
    });
	
	var grid_top10 = new Ext.grid.GridPanel({
        store: store_top10,
        columns: [
			{
                id       : 'player_id',
                header   : '',
				width	 : 160, 
                sortable : false, 
                dataIndex: 'player_id', 
				hidden	 : true
            },
            {
                header   : 'Player', 
                width    : 315, 
                sortable : false,
				renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'player'
            },
			{
                header   : 'Height', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'height',
				align	 : 'left'
            },
			{
                header   : 'Weight', 
                hidden	 : true, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'weight',
				align	 : 'center'
            },
			{
                header   : 'Position', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'position',
				align	 : 'center'
            },
			{
                header   : 'PPG', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'ppg',
				align	 : 'center'
            },
			{
                header   : 'RPG', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'rpg',
				align	 : 'center'
            },
			{
                header   : 'APG', 
                width    : 80, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'apg',
				align	 : 'center'
            },
			{
                header   : 'Games Played', 
                hidden	 : true, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'games',
				align	 : 'center'
            }
        ],
        stripeRows: true,
        //autoExpandColumn: 'player',
		autoHeight: true,
		border: false,
		layout: 'fit',
		listeners: {
			cellclick: function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex);
				var data = record.get('player_id');
				window.location.href='player.php?player='+data;
			}
		},
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid'
    });
	
	store.load();
	store_sched.load();
	store_top20.load();
	store_top10.load();

    // render the grid to the specified div in the page
    grid.render('grid-standings');
	grid_sched.render('grid-next_game');
	grid_top20.render('grid-top20');
	grid_top10.render('grid-top10');
	
	//grid.getSelectionModel().selectFirstRow();
});