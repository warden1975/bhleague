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
		var myStore2;
		var myStore3;
		var grid;
		
		var colModel = new Ext.grid.ColumnModel([
		{header: "ID", dataIndex: 'player_id', width:0,sortable:false,align:'left',hidden:true},
		{header: "Player", dataIndex: 'player', width:100,sortable:false,renderer : Ext.bhlcommondata.format_underline,align:'left'},
		{header: "PPG", dataIndex: 'ppg', width:180,sortable:false,align:'right'}
	
	 ]);

        var ds_model = Ext.data.Record.create([	
			{ name: 'player_id' },		
		    { name: 'player'},			
			{ name: 'ppg', sortType: Ext.data.SortTypes.asFloat }
		]);
    
	 myStore = new Ext.data.Store({
			url: 'league_leaders.php?action=get_player_points',
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',	
				id: 'player_id'
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
		   listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);  // Get the Record
					//var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
					var data = record.get('player_id');
					//console.log('fieldName: '+fieldName+', data: '+data);
					window.location.href='player_v2.php?player='+data;
				}
			},
			sm: new Ext.grid.RowSelectionModel({
				singleSelect: true
			})
			
		
	    });
	
     var ds_model2 = Ext.data.Record.create([	
	 		{name:'player_id'},		
		    { name: 'player'},			
			{ name: 'rpg', sortType: Ext.data.SortTypes.asFloat }
	]);
     myStore2 = new Ext.data.Store({
        url: 'league_leaders.php?action=get_player_rebounds',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: 'rows1',
            idProperty: 'player_id',
			totalProperty: 'results1'
        }, ds_model2)
    });
    
	myStore2.load()
    var grid2 = new Ext.grid.EditorGridPanel({
		 	viewConfig: {
				emptyText: 'No records found'
			},

			renderTo: 'grid-league-rebounds',
			frame:true,
			title: 'Rebounds',
	        width:320,
			mode:'local',
			layout:'fit',
			autoHeight: true,
			enableColumnMove: false,
	        store: myStore2,
	        columns: [
				{header: "ID", dataIndex: 'player_id', width:0,sortable:false,align:'left',hidden:true},
				{header: "Player", dataIndex: 'player', width:120,sortable:false,renderer : Ext.bhlcommondata.format_underline,align:'left'},
				{header: "RPG", dataIndex: 'rpg', width:190,sortable:false,align:'right'}
				
	        ],
			listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);
					var data = record.get('player_id');
					window.location.href='player_v2.php?player='+data;
				}
			},
			sm: new Ext.grid.RowSelectionModel({
				singleSelect: true
			})
	    });
	
	//var PlayerAssists = Ext.data.Record.create([
//		{name: 'player_id',mapping: 'player_id'},								   
//        {name: 'assists',mapping: 'assists',type:'float'}
//        
//    ]);
    var ds_model3 = Ext.data.Record.create([
			{name:'player_id'},			
		    { name: 'player'},			
			{ name: 'apg', sortType: Ext.data.SortTypes.asFloat }
		]);
	   myStore3 = new Ext.data.Store({
        url: 'league_leaders.php?action=get_player_assists',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: 'rows2',
            idProperty: 'player_id',
			totalProperty: 'results2'
        }, ds_model3)
    });
	   
	   myStore3.load()
	  
	   var grid3 = new Ext.grid.EditorGridPanel({
		 	viewConfig: {
				emptyText: 'No records found'
			},

			renderTo: 'grid-league-assists',
			frame:true,
			title: 'Assists',
	        width:300,
			mode:'local',
			layout:'fit',
			autoHeight: true,
			enableColumnMove: false,
	        store: myStore3,
	        columns: [
				{header: "ID", dataIndex: 'player_id', width:0,sortable:false,align:'left',hidden:true},
				{header: "Player", dataIndex: 'player', width:100,sortable:false,renderer : Ext.bhlcommondata.format_underline,align:'left'},
				{header: "APG", dataIndex: 'apg', width:170,sortable:false,align:'right'}
				
	        ],
			listeners: {
				cellclick: function(grid, rowIndex, columnIndex, e) {
					var record = grid.getStore().getAt(rowIndex);
					var data = record.get('player_id');
					window.location.href='player_v2.php?player='+data;
				}
			},
			sm: new Ext.grid.RowSelectionModel({
				singleSelect: true
			})
	    });
   // var myStore3 = new Ext.data.Store({
//        url: 'league_leaders.php?action=get_player_assists',
//        autoLoad: true,
//        reader: new Ext.data.JsonReader({
//            root: 'rows2',
//            idProperty: 'id'
//        }, PlayerAssists)
//    });
    
    //var pivotGrid3 = new Ext.grid.PivotGrid({
//        title     : 'Assists',
//        /*width     : 250,*/
//		layout: 'fit',
//        autoHeight: true,
//		remoteSort: true,
//        renderTo  : 'grid-league-assists',
//        store     : myStore3,
//        aggregator: 'sum',
//        measure   : 'assists',
//		align: 'right',
//        
//      
//        leftAxis: [
//            {
//                width: 100,
//                dataIndex: 'player_id',
//				align:'right'
//				
//            }
//        ],
//        
//        topAxis: [
//           
//        ]
//		
//    });
});