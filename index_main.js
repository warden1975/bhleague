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
        
    // create the data store.
    var store = new Ext.data.Store({
    	reader: reader,
        proxy: proxy
    });
	
	var store_sched = new Ext.data.Store({
    	reader: reader_sched,
        proxy: proxy_sched
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
				hidden : true,
                dataIndex: 'team_id'
            },
            {
                id       :'team',
                header   : 'Team', 
                width    : 160, 
                sortable : false, 
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
	
	store.load();
	store_sched.load();

    // render the grid to the specified div in the page
    grid.render('grid-standings');
	grid_sched.render('grid-next_game');
	
	//grid.getSelectionModel().selectFirstRow();
});