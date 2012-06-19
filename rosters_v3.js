/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
 //*************************************************************************************
 function showplayerRosterByTeam(teamid,container)
 {
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
		{name: 'cnt'},
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
		url: 'rosters_callback.php?action=playerdetails&team_id=' + teamid, //url to data object (server side script)
		method: 'GET'
	});
        
    // create the data store.
    var store = new Ext.data.Store({
    	reader: reader,
        proxy: proxy
    });

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
			{
				header   : '',
				width    : 30, 
				sortable : false,
				dataIndex: 'cnt'
			},
			{
                id       : 'player_id',
                header   : 'ID', 
                sortable : false, 
                dataIndex: 'player_id', 
				hidden	 : true
            },
            {
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
        //autoExpandColumn: 'player',
		autoHeight: true,
		border: false,
		layout: 'fit',
		listeners: {
			cellclick: function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex);
				var data = record.get('player_id');
				window.location.href='player_v3.php?player='+data;

			}
		},
        // config options for stateful behavior
        stateful: true,
        stateId: 'grid'
    });
	
	store.load();
	
	function RefreshGrid() {
		store.reload();
		grid.getView().refresh();
	}

    // render the grid to the specified div in the page
    grid.render(container);
	grid.getSelectionModel().selectFirstRow(); 
 }
 //*************************************************************************************
Ext.onReady(function(){
    Ext.QuickTips.init();

    // NOTE: This is an example showing simple state management. During development,
    // it is generally best to disable state management as dynamically-generated ids
    // can change across page loads, leading to unpredictable results.  The developer
    // should ensure that stable state ids are set for stateful components in real apps.    
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	
	//var gameday = Ext.getcmp('local-gamedays').value;
	
	var len=teams.length;
	for(var i=0; i<len; i++) {
		showplayerRosterByTeam(teams[i],teams_container[i])
	}

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    
});