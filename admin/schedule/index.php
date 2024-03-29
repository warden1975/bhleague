<?php
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);

$array = array();

$sql = "SELECT `id`, team_name FROM bhleague.teams";
$rs = $db->query($sql);

while ($row = $rs->fetch_assoc())
{
	$x ="'".$row['id']."'";
	$y = "'".$row['team_name']."'";
    $array[] = "[ {$x}, {$y} ]";

}
$z = ' [' . implode(', ', $array) . ']';

$s_arr = array();

$sql = "SELECT `id`, `name` FROM `bhleague`.`seasons`";
if ($rsx = $db->query($sql)) {
	while ($rowx = $rsx->fetch_object()) $s_arr[] = "['{$rowx->id}','{$rowx->name}']";
	$rsx->close();
}
$zx = '['.implode(', ', $s_arr).']';
?>
<html>
	<head>
	<title>BH League :: Game Schedule</title>
	<link rel="stylesheet" type="text/css" href="SuperBoxSelect/superboxselect.css" />
	<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
	<link rel="stylesheet" type="text/css" href="./css/icons.css">
	<link rel="stylesheet" type="text/css" href="./css/Ext.ux.grid.RowActions.css">
	<link rel="stylesheet" type="text/css" href="./css/empty.css" id="theme">
	<link rel="stylesheet" type="text/css" href="./css/webpage.css">
	<link rel="stylesheet" type="text/css" href="./css/gridsearch.css">
    <link rel="stylesheet" type="text/css" href="http://www.bhleague.com/extjs/examples/ux/css/MultiSelect.css"/>
	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="../extjs/ext-all.js"></script>
	
	<script type="text/javascript" src="SuperBoxSelect/SuperBoxSelect.js"></script>
	<script type="text/javascript" src="./js/WebPage.js"></script>
	<script type="text/javascript" src="./js/Ext.ux.ThemeCombo.js"></script>
	<script type="text/javascript" src="./js/Ext.ux.IconMenu.js"></script>
	<script type="text/javascript" src="./js/Ext.ux.Toast.js"></script>
	<script type="text/javascript" src="./js/Ext.ux.grid.Search.js"></script>
	<script type="text/javascript" src="./js/Ext.ux.grid.RowActions.js"></script>
    <script type="text/javascript" src="http://www.bhleague.com/extjs/examples/ux/MultiSelect.js"></script>
    <script type="text/javascript" src="http://www.bhleague.com/extjs/examples/ux/ItemSelector.js"></script>
	<script type="text/javascript" src="CheckColumn.js"></script>
	<style>
.cTextAlign {
	text-align: right;
	width:165px;
}
.x-grid3-col {
	border-left:  1px solid #EEEEEE;
	border-right: 1px solid #D2D2D2;
}
/* Also remove padding from table data (to compensate for added grid lines) */
.x-grid3-row td, .x-grid3-summary-row td {
	padding-left: 0px;
	padding-right: 0px;
}
</style>
	<script>
	var winLogin;
	var formLogin;
	var store;
	var grid;
	var formEdit;
	var winEdit;
	var mytime;
	var teamx;
	var winAutoGen;
	var formAutoGen;
	
	function trimAll(sString)
	{
		while (sString.substring(0,1) == ' ')
		{
			sString = sString.substring(1, sString.length);
		}
		while (sString.substring(sString.length-1, sString.length) == ' ')
		{
			sString = sString.substring(0,sString.length-1);
		}
		return sString;
	} 
	function fnSave()
	{
		var datex = document.getElementById('game_date').value;
		var timex = document.getElementById('game_time').value;
		var team1x = Ext.getCmp('team1').value;
		var team2x = Ext.getCmp('team2').value;
		var seasonxx = Ext.getCmp('season').value;
		var bye_weekx = document.getElementById('bye_week').value;

		if(team1x==team2x)
		{
			Ext.MessageBox.alert('Invalid Pairing', 'Team 1 and Team 2 should be different teams');
			return false;
		}
		else
		{
		var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Inserting records. Please wait...'});
		mask.show();
		Ext.Ajax.request({
		params: {action: 'insert', game_date: datex,game_time:timex,team1:team1x,team2:team2x,season:seasonxx,bye_week:bye_weekx},
		url: 'callback.php',
		success: function (resp,form,action) {
			if (resp.responseText == '{success:true}') {
				mask.hide();
				Ext.MessageBox.alert('Message', 'Record successfully inserted');
				store.reload();
				grid.getstore();
				grid.getView().refresh();
								
			} 
			else if(resp.responseText == 'DUPLICATE DATE')
			{
				mask.hide();
				Ext.MessageBox.alert('Error', 'Duplicate Entry ');
			}
			else 
			{
				
				mask.hide();
				Ext.MessageBox.alert('Error', 'Some problem occurred');
			}
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
	
	formLogin.getForm().reset();
	winLogin.destroy();
	}
	
	}
	function refreshPlayer()
	{
		store.reload();
		grid.getstore();
		grid.getView().refresh();
	}
	function fnEditSave()
	{
		var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Updating records. Please wait...'});
		mask.show();
		var idx = document.getElementById('id').value;
		var player_fnamex = document.getElementById('player_fname').value;
		var player_lnamex = document.getElementById('player_lname').value;
		
		Ext.Ajax.request({
		params: {action: 'update', id: idx,player_fname:player_fnamex,player_lname:player_lnamex},
		url: 'callback.php',
		success: function (resp,form,action) {
			if (resp.responseText == '{success:true}') {
				mask.hide();
				Ext.MessageBox.alert('Message', 'Record ' + idx + ' successfully updated');
				store.load();
				store.reload();
				grid.getstore();
				grid.getView().refresh();
								
			} else {
				mask.hide();
				Ext.MessageBox.alert('Error', 'Some problem occurred');
			}
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
	
	formEdit.getForm().reset();
	winEdit.destroy();
	
	
	}
	
	function DeleteRow(ids, team1,team2,mydate)
	{
		var sm=grid.getSelectionModel();
        var sel=sm.getSelections();
		Ext.Msg.show({
							title: 'Remove Schedule', 
							buttons: Ext.MessageBox.YESNOCANCEL,
							msg: 'Remove '+ team1+ ' ' + team2 +' macthup on ' + mydate + '?',
							fn: function(btn){
								if (btn == 'yes'){
									var conn = new Ext.data.Connection();
									conn.request({
										url: 'callback.php',
										params: {
											action: 'delete',
											id: ids
										},
										success: function(resp,opt) { 
											//grid.getStore().remove(sel); 
											grid.getView().refresh();
											store.reload();
										},
										failure: function(resp,opt) { 
											Ext.Msg.alert('Error','Unable to delete Player'); 
										}
									});
								}
							}
						});
	}
	function Update_ByeWeek(idx, valuex)
	{
		var sm=grid.getSelectionModel();
        var sel=sm.getSelections();
		//Ext.Msg.show({
							//title: 'Remove Schedule', 
							//buttons: Ext.MessageBox.YESNOCANCEL,
							//msg: 'Remove '+ team1+ ' ' + team2 +' macthup on ' + mydate + '?',
							//fn: function(btn){
							//	alert(idx);
//								alert(valuex);
									var conn = new Ext.data.Connection();
									conn.request({
										url: 'callback.php',
										params: {
											action: 'update_bye_week',
											id: idx,
											bye_week: valuex
										},
										success: function(resp,opt) { 
											//alert(resp.responseText);
											grid.getView().refresh();
											store.reload();
										},
										failure: function(resp,opt) { 
											Ext.Msg.alert('Error','Unable to update Bye Week'); 
										}
									});
								//}
							//}
						//});
	}
	
	Ext.onReady(function(){
		Ext.BLANK_IMAGE_URL = 'images/s.gif';
		Ext.QuickTips.init();
		
		this.rowActions = new Ext.ux.grid.RowActions({
			 actions:[{
				 iconCls:'icon-minus'
				,qtip:'Delete Record'
				,style:'margin:0 0 0 3px'
			}]
		});
		this.rowActions.on('action', this.onRowAction, this);
		
		var ds_model = Ext.data.Record.create([
			{ name: 'id', sortType: Ext.data.SortTypes.asInt },
		    { name: 'game_date', sortType: Ext.data.SortTypes.asDate },			
			{ name: 'game_time' , sortType: Ext.data.SortTypes.asDate },
			{ name: 'team1'},
			{ name: 'team2'},
			{ name: 'team1_score', sortType: Ext.data.SortTypes.asInt },
			{ name: 'team2_score', sortType: Ext.data.SortTypes.asInt },
			{ name: 'season', sortType: Ext.data.SortTypes.asInt },
			{ name: 'bye_week', sortType: Ext.data.SortTypes.asInt }
			
			
		]);
		 teamx = new Ext.data.SimpleStore({
	        fields: ['id', 'team_name'],
		    data : <?php echo $z; ?>
	    });
		var string_edit = new Ext.form.TextField({
			allowBlank: false
		});
		
		seasonx = new Ext.data.SimpleStore({
	        fields: ['id', 'name'],
		    data : <?php echo $zx; ?>
	    });
		
		//var bye_wee_store = new Ext.data.SimpleStore({
//        fields: ['bye_week_value', 'bye_week_display'],
//        data: [['0', 'No'], ['1', 'Yes']]
//
//       });
	  // var bye_week_edit = new Ext.form.ComboBox({
//			typeAhead: true,
//			triggerAction: 'all',
//			allowBlank: true,
//			editable: true,
//			selectOnFocus: true,
//			mode: 'local',
//			store: bye_wee_store,
//			displayField:'bye_week_display',
//			valueField: 'bye_week_value',
//			listeners: {}
//		});
//		
	     store = new Ext.data.Store({
			url: 'datasource.php',
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				id:'id'
			}, ds_model)
	    });
		
		var team_edit = new Ext.form.ComboBox({
			typeAhead: true,
			triggerAction: 'all',
			mode: 'local',
			store: teamx,
			displayField:'team_name',
			valueField: 'id'
			
		});
		
		
		
		
		
		
	   var bye_weekz = new Ext.grid.CheckColumn({
       header: 'Bye Week',
       dataIndex: 'bye_week',
	   name: 'bye_week',
	   id:'bye_week',
       width: 70
	   //editor:bye_week_edit
       });
	  // bye_weekz.on('click', function(element, evt, record) {
//       alert(record.get('game_time'));
//	});
		
		var season_edit = new Ext.form.ComboBox({
			typeAhead: true,
			triggerAction: 'all',
			allowBlank: true,
			editable: true,
			selectOnFocus: true,
			mode: 'local',
			store: seasonx,
			displayField:'name',
			valueField: 'id',
			listeners: {}
		});
	
	    grid = new Ext.grid.EditorGridPanel({
		 	viewConfig: {
				emptyText: 'No records found'
			},

			renderTo: document.body,
			frame:true,
			title: 'BH Game Schedule',
	        height:300,
	        width:1250,
			mode:'local',
			layout:'fit',
			autoHeight: true,
			enableColumnMove: false,
	        store: store,
			clicksToEdit: 1,
	        columns: [
				{header: "ID", dataIndex: 'id', width:100,sortable:true,align:'right'},
				{header: "Date", dataIndex: 'game_date', width:150,sortable:true,editor:new Ext.form.DateField({format: 'Y-m-d'})},
				{header: "Time", dataIndex: 'game_time', width:150,sortable:true,editor: {
                    xtype: 'timefield',
                    format:('H:i:s'),
                    increment: 15

                } },
				{header: "Team 1", dataIndex: 'team1', width:180,sortable:true,editor:team_edit},
				{header: "Team 2", dataIndex: 'team2', width:180,sortable:true,editor:team_edit},
				{header: "Team 1 Score", dataIndex: 'team1_score', width:120,sortable:true,editor:team_edit},
				{header: "Team 2 Score", dataIndex: 'team2_score', width:120,sortable:true,editor:team_edit},
				{header: "Season", dataIndex: 'season', width:90,sortable:true,editor:season_edit},
				bye_weekz,

				{
					xtype: 'actioncolumn',
					width: 30,
					items: [{
						icon   : 'images/database_delete.png',  // Use a URL in the icon config
						tooltip: 'Remove Player ',
						handler: function(grid, rowIndex, colIndex) {
							var rec = store.getAt(rowIndex);
							DeleteRow(rec.get('id'),rec.get('team1'),rec.get('team2'),rec.get('game_date'));
						}
					}]
				}
				
	        ],
			
			sm: new Ext.grid.RowSelectionModel({
				singleSelect: true
			}),
			
			listeners: {
				afteredit: function(e){
					var conn = new Ext.data.Connection();
					conn.request({
						url: 'callback.php',
						params: {
							action: 'update',
							id: e.record.id,
							field: e.field,
							value: e.value
						},
						success: function(resp,opt) {
							
							if (resp.responseText == '{success:true}') {
								e.record.commit();
								store.reload();
								grid.getstore();
								grid.getView().refresh();
							}
							else if(resp.responseText == '{invalid pairings}') {
							
								Ext.MessageBox.alert('Invalid pairing', 'Team 1 and Team 2 should be different teamns');
								
								store.reload();
								grid.getstore();
								grid.getView().refresh();
							
							}
						},
						failure: function(resp,opt) {
							e.reject();
							Ext.MessageBox.alert('Error', 'Some problem occurred');
						}
					});
				}
			},
			keys: [
				{
					key: 46,
					fn: function(key,e){
						var sm = grid.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							Ext.Msg.show({
								title: 'Remove Player', 
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: 'Remove '+ sel.data.player_fname + ' ' + sel.data.player_lname + '?',
								fn: function(btn){
									if (btn == 'yes'){
										var conn = new Ext.data.Connection();
										conn.request({
											url: 'callback.php',
											params: {
												action: 'delete',
												id: sel.data.id
											},
											success: function(resp,opt) { 
												grid.getStore().remove(sel); 
											},
											failure: function(resp,opt) { 
												Ext.Msg.alert('Error','Unable to delete Player'); 
											}
										});
									}
								}
							});
						}
					},
					ctrl:false,
					stopEvent:true
				}
			],
			tbar: [
				  {
					  text: 'Add Schedule',
					  icon: 'images/table_add.png',
					  cls: 'x-btn-text-icon',
					  handler: addNew
				  },
				  {
					  text: 'Auto-Generate',
					  icon: 'images/table_add.png',
					  cls: 'x-btn-text-icon',
					  handler: autoGenerate
				  },
				  '-',
				  {
					  text: 'Refresh',
					  icon: 'images/arrow_refresh.png',
					  cls: 'x-btn-text-icon',
					  handler: refreshPlayer			}],plugins:[new Ext.ux.grid.Search({
					  iconCls:'icon-zoom'
					  ,position:top
					  ,minChars:3
					  ,autoFocus:true,
					  width:180
				  }), this.rowActions],
	    });
		var loadMask = new Ext.LoadMask(
            Ext.getBody(),
            {
              msg:'Loading Data...This May Take Awhile',
              removeMask: true,
              store: store
            }
          );
		
		loadMask.enable();
		store.load();
		loadMask.disable();
		 
		
	});
	
	var ds_teams = new Ext.data.ArrayStore({
        data: <?php echo $z; ?>,
        fields: ['value','text'],
        sortInfo: {
            field: 'text',
            direction: 'ASC'
        }
    });
	
	function autoGenerate() {
		formAutoGen = new Ext.FormPanel({
			frame: false, border: false, buttonAlign: 'center',
			method: 'GET', id: 'formAutoGen',
			bodyStyle: 'padding:10px 10px 15px 15px;background:#dfe8f6;',
			width: 700, labelWidth: 150,
			items: [{
						xtype: 'datefield',
						fieldLabel: 'Game Start Date',
						name: 'game_date',
						id:'game_date',
						format: 'Y-m-d',
						width:160,
						allowBlank:false
					},
						new Ext.form.TimeField({						
							fieldLabel: 'Game Time',
							minValue: '00:00:00',
							maxValue: '23:00:00',
							increment: 15,
							format:'H:i:s',
							name:'game_time',
							id:'game_time',
							allowBlank: false
						}),
					{ 
						xtype: 'itemselector',
						name: 'teams',
						id: 'teams',
						fieldLabel: 'Teams',
						imagePath: 'http://www.bhleague.com/extjs/examples/ux/images/',
						multiselects: [{
							width: 250,
							height: 200,
							store: ds_teams,
							displayField: 'text',
							valueField: 'value'
						},{
							width: 250,
							height: 200,
							store: [['0', 'Select from list']],
							tbar:[{
								text: 'clear',
								handler:function(){
									formAutoGen.getForm().findField('teams').reset();
								}
							}]
						}]
					}],
			buttons: [{
						text: 'Save', handler: fnSave_AutoGen
					},
					{ 
						text: 'Cancel', handler: function() {
							formAutoGen.getForm().reset();
							winAutoGen.destroy();
						}
					}]
			});
		
		winAutoGen = new Ext.Window({
			title: 'Auto-Generate Schedule',
			layout: 'fit',
			width: 750,
			height: 350,
			y: 80,
			resizable: false,
			closable: true,
			items: [formAutoGen]
		});

		winAutoGen.show();
	}
	
	function fnSave_AutoGen() {
		if (formAutoGen.getForm().isValid()) {
			console.log(formAutoGen.getForm().getValues(true));
		
			var datex = formAutoGen.getForm().findField('game_date').value;
			var timex = formAutoGen.getForm().findField('game_time').value;
			var teamsx = formAutoGen.getForm().findField('teams').getValue();
		
			console.log('date: '+datex+', time: '+timex+', teams: '+teamsx);

			var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Inserting records. Please wait...'});
			mask.show();
			Ext.Ajax.request({
				params: {action: 'create', game_date: datex, game_time: timex, teams: teamsx},
				url: 'callback_autogen.php',
				success: function (resp,form,action) {
					console.log(resp.responseText);
					//mask.hide();
					if (resp.responseText == '{success:true}') {
						mask.hide();
						Ext.MessageBox.alert('Message', 'Record successfully created');
						store.reload();
						grid.getstore();
						grid.getView().refresh();
					} else {
						mask.hide();
						Ext.MessageBox.alert('Error', 'Some problem occurred');
					}
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
	
			formAutoGen.getForm().reset();
			winAutoGen.destroy();
			
		} else return false;
	
	}
	
	function addNew()
	{	
		formLogin = new Ext.FormPanel({
			frame: false, border: false, buttonAlign: 'center',
			method: 'POST', id: 'frmAddNew',
			bodyStyle: 'padding:10px 10px 15px 15px;background:#dfe8f6;',
			width: 500, labelWidth: 150,
			items: [{
						xtype: 'datefield',
						fieldLabel: 'Game Date',
						name: 'game_date',
						id:'game_date',
						format: 'Y-m-d',
						width:160,
						allowBlank:false
					},
						new Ext.form.TimeField({						
							fieldLabel: 'Game Time',
							minValue: '00:00:00',
							maxValue: '23:00:00',
							increment: 15,
							format:'H:i:s',
							name:'game_time',
							id:'game_time',
							allowBlank: false
						}),
					{ 
						xtype: 'combo',
						name: 'team1',
						id: 'team1',
						fieldLabel: 'Team 1',
						mode: 'local',
						store: teamx,
						displayField:'team_name',
						valueField:'id',
						triggerAction: 'all',
						allowBlank: false
					},
					{ 
						xtype: 'combo',
						name: 'team2',
						id: 'team2',
						fieldLabel: 'Team 2',
						mode: 'local',
						store: teamx,
						displayField:'team_name',
						valueField:'id',
						triggerAction: 'all',
						allowBlank: false
					},
					{ 
					 xtype: 'combo',
					 name: 'season',
					 id: 'season',
					 fieldLabel: 'Season',
					 allowBlank:true,
					 mode: 'local',
					 store: seasonx,
					 displayField:'name',
					 valueField:'id',
					 triggerAction: 'all'
					},
					{
					xtype: 'checkbox',
					fieldLabel: 'Bye Week',
					name: 'bye_week',
					id:'bye_week'
					}
				],
			buttons: [{
						text: 'Save', handler: fnSave
					},
					{ 
						text: 'Cancel', handler: function() {
							formLogin.getForm().reset();
							winLogin.destroy();
						}
					}]
			});
		
		winLogin = new Ext.Window({
			title: 'Add New',
			layout: 'fit',
			width: 430,
			height: 230,
			y: 340,
			resizable: false,
			closable: true,
			items: [formLogin]
		});

		winLogin.show();
	}
	</script>
	</head>
	<body style="padding:20px; overflow:auto;">
</body>
</html>