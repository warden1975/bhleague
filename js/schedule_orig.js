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
					 
	
	Date.prototype.monthNames = [
		"01 - January", "02 - February", "03 - March",
		"04 - April", "05 - May", "06 - June",
		"07 - July", "08 - August", "09 - September",
		"10 - October", "11 - November", "12 - December"
	];
	
	Date.prototype.getMonthName = function(z) {
		return this.monthNames[z];
	};
	
	Ext.apply(Ext.data.SortTypes, {
	    asMonth: function(month){
	        // expects an object with a first and last name property
	    var d = new Date();	
		return d.getMonthName(month -1);
	    }   
	});
	function mymonthName(v, record)
	{
		var d = new Date();	
		return d.getMonthName(record.month -1);
    }
    var Schedule = Ext.data.Record.create([
		{name: 'month',mapping: 'month',sortype:Ext.data.SortTypes.asDate,convert: mymonthName},								   
        {name: 'monthname',mapping: 'monthname',type:'asMonth'},
        {name: 'date',  mapping: 'date',type:'int',sortype:Ext.data.SortTypes.asInt},
        {name: 'game_time',type: 'string'},
        {name: 'teams',   mapping: 'teams'},
        {name: 'score', mapping: 'score'},
        
    ]);
    
    var myStore = new Ext.data.Store({
        url: 'schedule.php',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: 'rows',
            idProperty: 'id'
        }, Schedule)
    });
    
    var pivotGrid = new Ext.grid.PivotGrid({
        title     : 'BH League Game Schedule',
        width     : 800,
        autoHeight: true,
		remoteSort:true,
        renderTo  : 'grid-schedule',
        store     : myStore,
        aggregator: 'sum',
        measure   : 'score',
        
        viewConfig: {
            title: 'Schedule'
        },
        renderer  : function(score) {
	
			score = score.toString()
        if(score.length==1)
		{
		 return "--"
		}
		else if(score.length>1)
		{
			newString = score.replace('0','');
			return newString;
		}
       },

        leftAxis: [
            {
                width: 80,
                dataIndex: 'month',
				
            },
            {
                width: 90,
                dataIndex: 'date',
				
            },
			{
                width: 190,
                dataIndex: 'teams'
            }
        ],
        
        topAxis: [
            {
				width:100,
                dataIndex: 'game_time',
				align: 'center'
            }
        ]
		
    });
});