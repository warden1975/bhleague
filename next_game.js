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
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function change(val) {
		return '<span style="color:green;">' + val + '</span>';
    }
	
	// tell the store how to process the data
	var reader = new Ext.data.ArrayReader({}, [
		{name: 'playing'},
		{name: 'game_time'}
	]);
	
	// get the data
    var proxy = new Ext.data.HttpProxy({
		//where to retrieve data
		url: 'next_game.php', //url to data object (server side script)
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
                id       :'playing',
                header   : 'Team', 
                width    : 160, 
                sortable : false, 
				renderer : Ext.bhlcommondata.format_underline,
                dataIndex: 'playing'
            },
            {
                header   : 'Time (PST)', 
                width    : 75, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'game_time',
				align	 : 'left'
            }/*,
            {
                header   : 'Teams', 
                width    : 150, 
                sortable : false, 
                renderer : change, 
                dataIndex: 'playing',
				align	 : 'left'
            }*/
        ],
        stripeRows: true,
        autoExpandColumn: 'playing',
		border: false,
		autoHeight: true,
		layout: 'fit',
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
    grid.render('grid-next_game');
	grid.getSelectionModel().selectFirstRow();
});