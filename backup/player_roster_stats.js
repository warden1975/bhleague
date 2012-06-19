/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
  "id": "4",
            "month": "February",
            "date": "21st",
            "game_time": "05:45:00",
            "teams": "Clubbers vs. The Beavers",
            "score": "57 - 67",
            "team1": "11",
            "team2": "12"
 */
Ext.onReady(function() {
		
		var myStore;
		
		var colModel = new Ext.grid.ColumnModel([
		{header: "Player", dataIndex: 'player', width:100,sortable:false,align:'left'},
		{header: "Points", dataIndex: 'points', width:180,sortable:false,align:'right'},
	
	 ]);

        var ds_model = Ext.data.Record.create([			
		    { name: 'player'},			
			{ name: 'points', sortType: Ext.data.SortTypes.asFloat }
		]);
    
	 myStore = new Ext.data.Store({
			url: 'league_leaders.php?action=get_player_points',
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				id: 'id'
			}, ds_model)
	    });
	 
	 myStore.load()
    
       grid = new Ext.grid.EditorGridPanel({
		 	viewConfig: {
				emptyText: 'No records found'
			},
			
			renderTo: 'grid-league-points',
			frame:true,
			title: 'Points',
	        width:310,
			mode:'local',
			layout:'fit',
			autoHeight: true,
			enableColumnMove: false,
	        store: myStore,
	       // columns: [
//				{header: "Player", dataIndex: 'player', width:100,sortable:false,align:'left'},
//				{header: "Points", dataIndex: 'points', width:180,sortable:false,align:'right'},
//				
//	        ],
		   cm: colModel,
			sm: new Ext.grid.RowSelectionModel({
				singleSelect: true
			})
			
		
	    });
	
     
   
});