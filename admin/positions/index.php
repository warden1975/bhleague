<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BH League :: Player Position Data</title>
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
var teamx;

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
		{ name: 'position_name'},
		{ name: 'position_abbv'}
	]);
	
	/*teamx = new Ext.data.SimpleStore({
		fields: ['id', 'team_name'],
		data : 
	});*/
	
	var string_edit = new Ext.form.TextField({
		allowBlank: false
	});
	
	store = new Ext.data.Store({
		url: 'datasource.php',
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			id:'id'
		}, ds_model)
	});

	grid = new Ext.grid.EditorGridPanel({
		viewConfig: {
			emptyText: 'No records found'
		},
  
		renderTo: document.body,
		frame:true,
		title: 'BH League Player Positions',
		height:300,
		width:750,
		mode:'local',
		layout:'fit',
		autoHeight: true,
		enableColumnMove: false,
		store: store,
		clicksToEdit: 2,
		columns: [
			{header: "ID", dataIndex: 'id', width:100,sortable:true,align:'right'},
			{header: "Player Position", dataIndex: 'position_name', width:500,sortable:true,editor:string_edit},
			{header: "Position Symbol", dataIndex: 'position_abbv', width:100,sortable:true,editor:string_edit},
			{
				xtype: 'actioncolumn',
				width: 30,
				items: [{
					icon   : 'images/database_delete.png',  // Use a URL in the icon config
					tooltip: 'Remove Position ',
					handler: function(grid, rowIndex, colIndex) {
						var rec = store.getAt(rowIndex);
						DeleteRow(rec.get('id'),rec.get('position_name'),rec.get('position_abbv'));
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
						e.commit();
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
							title: 'Remove Position', 
							buttons: Ext.MessageBox.YESNOCANCEL,
							msg: 'Remove '+ sel.data.position_name + ' ' + sel.data.position_abbv + '?',
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
											Ext.Msg.alert('Error','Unable to delete Position'); 
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
			text: 'Add Position',
			icon: 'images/table_add.png',
			cls: 'x-btn-text-icon',
			handler: addNew
		},'-',{
			text: 'Refresh',
			icon: 'images/arrow_refresh.png',
			cls: 'x-btn-text-icon',
			handler: RefreshGrid
		}],plugins:[new Ext.ux.grid.Search({
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
              //msgCls: '#loading',
              removeMask: true,
              store: store
            }
          );

	loadMask.enable();
	store.load();
	loadMask.disable();
	
});

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
	var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Inserting records. Please wait...'});
	mask.show();
	var position_namex = document.getElementById('position_name').value;
	var position_abbvx = document.getElementById('position_abbv').value;
	
	Ext.Ajax.request({
	params: {action: 'insert', position_name: position_namex, position_abbv: position_abbvx},
	url: 'callback.php',
	success: function (resp,form,action) {
		if (resp.responseText == '{success:true}') {
			mask.hide();
			Ext.MessageBox.alert('Message', 'Record successfully inserted');
			store.reload();
			grid.getstore();
			grid.getView().refresh();
							
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
	grid.getstore();
	grid.getView().refresh();
}

function fnEditSave()
{
	var mask = new Ext.LoadMask(Ext.get(document.body), {msg:'Updating records. Please wait...'});
	mask.show();
	var idx = document.getElementById('id').value;
	var position_namex = document.getElementById('position_name').value;
	var position_abbvx = document.getElementById('position_abbv').value;
	
	Ext.Ajax.request({
	params: {action: 'update', id:idx, position_name: position_namex, position_abbv: position_abbvx},
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

function DeleteRow(ids, fname,lname)
{
	var sm=grid.getSelectionModel();
	var sel=sm.getSelections();
	Ext.Msg.show({
		title: 'Remove Player', 
		buttons: Ext.MessageBox.YESNOCANCEL,
		msg: 'Remove '+ fname+ ' ' + lname +'?',
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
						Ext.Msg.alert('Error','Unable to delete Position'); 
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
				xtype: 'textfield',
				fieldLabel: 'Player Position',
				name: 'position_name',
				id: 'position_name',
				align: 'left',
				width: 165

			},
			 {
				xtype: 'textfield',
				fieldLabel: 'Position Symbol',
				name: 'position_abbv',
				id: 'position_abbv',
				align: 'left',
				width:165

			}
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
		height: 160,
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