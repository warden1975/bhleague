<?php
define('DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DB_CLS', DIR . 'class/db.cls.php');

require DB_CLS;

$db = new DB_Connect(MYSQL_INTRANET, 1);
if (!$db) die("Can't connect to database.");

extract($_REQUEST);
$data = array();
$tdata = '[]';

$sql = "SELECT `id`, `team_name` FROM `bhleague`.`teams`";

if ($rs = $db->query($sql)) {
	$rs_cnt = $rs->num_rows;
	if ($rs_cnt > 0) {
		while ($row = $rs->fetch_object()) {
			$id = $row->id;
			$team_name = $row->team_name;
			$data[] = "[ '{$id}', '{$team_name}' ]";
		}
		#var_dump($data); exit;
	}
	$rs->close();
	
	$tdata = '[' . implode(',', $data) . ']';
}

$s_arr = array();

$sql = "SELECT `id`, `name` FROM `bhleague`.`seasons`";
if ($rsx = $db->query($sql)) {
	while ($rowx = $rsx->fetch_object()) $s_arr[] = "['{$rowx->id}','{$rowx->name}']";
	$rsx->close();
}
$zx = '['.implode(', ', $s_arr).']';

$db->close();
$db = NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BH League :: Games Stats</title>
<link rel="stylesheet" type="text/css" href="./SuperBoxSelect/superboxselect.css" />
<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="./css/icons.css">
<link rel="stylesheet" type="text/css" href="./css/Ext.ux.grid.RowActions.css">
<link rel="stylesheet" type="text/css" href="./css/empty.css" id="theme">
<link rel="stylesheet" type="text/css" href="./css/webpage.css">
<link rel="stylesheet" type="text/css" href="./css/gridsearch.css">

<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../extjs/ext-all.js"></script>
<script type="text/javascript" src="./SuperBoxSelect/SuperBoxSelect.js"></script>
<script type="text/javascript" src="./js/WebPage.js"></script>
<script type="text/javascript" src="./js/Ext.ux.ThemeCombo.js"></script>
<script type="text/javascript" src="./js/Ext.ux.IconMenu.js"></script>
<script type="text/javascript" src="./js/Ext.ux.Toast.js"></script>
<script type="text/javascript" src="./js/Ext.ux.grid.Search.js"></script>
<script type="text/javascript" src="./js/Ext.ux.grid.RowActions.js"></script>

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

<script type="text/javascript">
var winLogin;
var formLogin;
var store;
var grid;
var formEdit;
var winEdit;
var mytime;
var team_winnerx;
var team_loserx;

Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = 'images/s.gif';
	Ext.QuickTips.init();
	
	this.rowActions = new Ext.ux.grid.RowActions({
		 actions:[{
			 iconCls:'icon-minus',
			 qtip:'Delete Record',
			 style:'margin:0 0 0 3px'
		}]
	});
	
	this.rowActions.on('action', this.onRowAction, this);
  
	var ds_model = Ext.data.Record.create([
		{ name: 'id', sortType: Ext.data.SortTypes.asInt },
		{ name: 'game_date', sortType: Ext.data.SortTypes.asDate },
		{ name: 'game_winner', sortType: Ext.data.SortTypes.asInt },
		{ name: 'winner_score', sortType: Ext.data.SortTypes.asInt },
		{ name: 'game_loser', sortType: Ext.data.SortTypes.asInt },
		{ name: 'loser_score', sortType: Ext.data.SortTypes.asInt },
		{ name: 'season', sortType: Ext.data.SortTypes.asInt },
	]);
	
	team_winnerx = new Ext.data.SimpleStore({
		fields: ['game_winner_id', 'game_winner_name'],
		data: <?php echo $tdata; ?>
	});
	
	team_loserx = new Ext.data.SimpleStore({
		fields: ['game_loser_id', 'game_loser_name'],
		data: <?php echo $tdata; ?>
	});
	
	var string_edit = new Ext.form.TextField({
		allowBlank: false
	});
	
	
	seasonx = new Ext.data.SimpleStore({
	        fields: ['id', 'name'],
		    data : <?php echo $zx; ?>
	    });
	
	var number_edit = new Ext.form.NumberField({
		allowBlank: false,
		allowDecimals:false,
		allowNegative: false,
		minValue: 0
	});
	
	store = new Ext.data.Store({
		url: 'datasource.php',
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			id:'id'
		}, ds_model)
	});
	
	Ext.util.Format.comboRenderer = function(combo){
		return function(value){
			var record = combo.findRecord(combo.valueField, value);
			return record ? record.get(combo.displayField) : combo.valueNotFoundText;
		}
	}
	
	var team_winner_edit = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		mode: 'local',
		store: team_winnerx,
		displayField: 'game_winner_name',
		valueField: 'game_winner_id'
	});
	
	var team_loser_edit = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		mode: 'local',
		store: team_loserx,
		displayField: 'game_loser_name',
		valueField: 'game_loser_id'
	});
	
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
		title: 'BH League Games Statistics',
		height:300,
		width:900,
		mode:'local',
		layout:'fit',
		autoHeight: true,
		enableColumnMove: false,
		store: store,
		clicksToEdit: 2,
		columns: [
			{header: "ID", dataIndex: 'id', width:100,sortable:true,align:'right'},
			{header: "Game Date", dataIndex: 'game_date', width:160,sortable:true,editor:new Ext.form.DateField({format: 'Y-m-d'})},
			{header: "Winner", dataIndex: 'game_winner', width:160,sortable:true,editor:team_winner_edit,renderer: Ext.util.Format.comboRenderer(team_winner_edit)},
			{header: "Winner Score", dataIndex: 'winner_score', width:90,sortable:true,editor:number_edit,align:'right'},
			{header: "Loser", dataIndex: 'game_loser', width:160,sortable:true,editor:team_loser_edit,renderer: Ext.util.Format.comboRenderer(team_loser_edit)},
			{header: "Loser Score", dataIndex: 'loser_score', width:90,sortable:true,editor:number_edit,align:'right'},
			{header: "Season", dataIndex: 'season', width:90,sortable:true,editor:season_edit},
			{
				xtype: 'actioncolumn',
				width: 30,
				items: [{
					icon   : 'images/database_delete.png',  // Use a URL in the icon config
					tooltip: 'Remove Game Stats',
					handler: function(grid, rowIndex, colIndex) {
						var rec = store.getAt(rowIndex);
						DeleteRow(rec.get('id'));
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
						e.record.commit();
						RefreshGrid();
						store.reload();
						grid.getstore();
						grid.getView().refresh();
					},
					failure: function(resp,opt) {
						e.reject();
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
							title: 'Remove Game Stats', 
							buttons: Ext.MessageBox.YESNOCANCEL,
							msg: 'Remove '+ sel.data.id + '?',
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
											Ext.Msg.alert('Error','Unable to delete Team'); 
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
		tbar: [{
			text: 'Add Game',
			icon: 'images/table_add.png',
			cls: 'x-btn-text-icon',
			handler: addNew
		},'-',{
			text: 'Refresh',
			icon: 'images/arrow_refresh.png',
			cls: 'x-btn-text-icon',
			handler: RefreshGrid
		}]/*,plugins:[new Ext.ux.grid.Search({
			iconCls:'icon-zoom'
			,position:top
			,minChars:3
			,autoFocus:true,
			width:180
		}), this.rowActions]*/,
	});
	
	var loadMask = new Ext.LoadMask(
            Ext.getBody(),
            {
              msg:'Loading Data...This May Take Awhile',
              //msgCls: '#loading',
              removeMask: true,
              store: store
            }
          );

	loadMask.enable();
	store.load();
	loadMask.disable();
});

function fnSave()
{
	var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Inserting records. Please wait...'});
	mask.show();
	var game_datex = Ext.getCmp('game_date').value;
	var game_winnerx = Ext.getCmp('game_winner').value;
	var winner_scorex = document.getElementById('winner_score').value;
	var game_loserx = Ext.getCmp('game_loser').value;
	var loser_scorex = document.getElementById('loser_score').value;
	var seasonxx = Ext.getCmp('season').value;
	
	Ext.Ajax.request({
	params: {action: 'insert', game_date: game_datex, game_winner: game_winnerx, winner_score: winner_scorex, game_loser: game_loserx, loser_score:loser_scorex,season:seasonxx},
	url: 'callback.php',
	success: function (resp,form,action) {
		if (resp.responseText == '{success:true}') {
			mask.hide();
			Ext.MessageBox.alert('Message', 'Record successfully inserted');
			store.reload();
			grid.getstore();
			grid.getView().refresh();
		}
		else if(resp.responseText == '{duplicate:true}')
		{
			mask.hide();
			Ext.MessageBox.alert('Invalid', 'Duplicate Entry ');
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

function RefreshGrid()
{
	store.reload();
	grid.getView().refresh();
}

function fnEditSave()
{
	var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Updating records. Please wait...'});
	mask.show();
	var idx = document.getElementById('id').value;
	var game_datex = Ext.getCmp('game_date').value;
	var game_winnerx = Ext.getCmp('game_winner').value;
	var winner_scorex = document.getElementById('winner_score').value;
	var game_loserx = Ext.getCmp('game_loser').value;
	var loser_scorex = document.getElementById('loser_score').value;
	
	Ext.Ajax.request({
	params: {action: 'update', id: idx, game_date: game_datex, game_winner: game_winnerx, winner_score: winner_scorex, game_loser: game_loserx, loser_score: loser_scorex},
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

function DeleteRow(ids)
{
	var sm=grid.getSelectionModel();
	var sel=sm.getSelections();
	Ext.Msg.show({
		title: 'Remove Player', 
		buttons: Ext.MessageBox.YESNOCANCEL,
		msg: 'Remove '+ ids +'?',
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

function addNew()
{	
	formLogin = new Ext.FormPanel({
		frame: false, border: false, buttonAlign: 'center',
		method: 'POST', id: 'frmAddNew',
		bodyStyle: 'padding:10px 10px 15px 15px;background:#dfe8f6;',
		width: 500, labelWidth: 150,
		items: [
				{
					xtype: 'datefield',
					fieldLabel: 'Game Date',
					name: 'game_date',
					id: 'game_date',
					format: 'Y-m-d',
					width:160,
				},
				 /*{
					xtype: 'textfield',
					fieldLabel: 'Description',
					name: 'team_desc',
					id: 'team_desc',
					align: 'left',
					width:165
	
				}*/
				 { 
					 xtype: 'combo',
					 name: 'game_winner',
					 id: 'game_winner',
					 fieldLabel: 'Winner',
					 mode: 'local',
					 store: team_winnerx,
					 displayField:'game_winner_name',
					 valueField:'game_winner_id',
					 triggerAction: 'all'
				 },
				 new Ext.form.NumberField({  
					 fieldLabel: 'Winner Score', 
					 allowBlank: false, 
					 name:'winner_score', 
					 id:'winner_score', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0}),
				{ 
					 xtype: 'combo',
					 name: 'game_loser',
					 id: 'game_loser',
					 fieldLabel: 'Loser',
					 mode: 'local',
					 store: team_loserx,
					 displayField:'game_loser_name',
					 valueField:'game_loser_id',
					 triggerAction: 'all'
				 },
				 new Ext.form.NumberField({  
					 fieldLabel: 'Loser Score', 
					 allowBlank: false, 
					 name:'loser_score', 
					 id:'loser_score', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0}),
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
			],
			buttons: [
				{ text: 'Save', handler: fnSave },
				{ text: 'Cancel', handler: function() {
						formLogin.getForm().reset();
						winLogin.destroy();
					}
				}
			]
		});
		
		winLogin = new Ext.Window({
		title: 'Add New',
		layout: 'fit',
		width: 430,
		height: 300,
		y: 340,
		resizable: false,
		closable: true,
		items: [formLogin]
	});

	winLogin.show();
}
</script>
</head>

<body style="padding:20px;">
</body>
</html>