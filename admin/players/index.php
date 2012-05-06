<?php
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
//extract($_REQUEST);
$array = array();

$sql = "SELECT `id`, team_name FROM bhleague.teams";

//echo $sql;exit;

$rs = $db->query($sql);


while ($row = $rs->fetch_assoc())
{
	$x ="'".$row['id']."'";
	$y = "'".$row['team_name']."'";
    $array[] = "[ {$x}, {$y} ]";

}
$z = ' [' . implode(', ', $array) . ']';
//echo $z;
$p_arr = array();
$sql = "SELECT `id`, `position_abbv` FROM `bhleague`.`positions`";
if ($rs = $db->query($sql)) {
	while ($row = $rs->fetch_object()) $p_arr[] = "['{$row->id}','{$row->position_abbv}']";
	$rs->close();
}
$y = '['.implode(', ', $p_arr).']';
?>
<html>
	<head>
	<title>BH League :: Players Data</title>
	<link rel="stylesheet" type="text/css" href="SuperBoxSelect/superboxselect.css" />
	<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
	<link rel="stylesheet" type="text/css" href="./css/icons.css">
	<link rel="stylesheet" type="text/css" href="./css/Ext.ux.grid.RowActions.css">
	<link rel="stylesheet" type="text/css" href="./css/empty.css" id="theme">
	<link rel="stylesheet" type="text/css" href="./css/webpage.css">
	<link rel="stylesheet" type="text/css" href="./css/gridsearch.css">
	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="../extjs/ext-all.js"></script>
	<!--script src="Exporter-all.js" type="text/javascript"></script-->
	<script type="text/javascript" src="SuperBoxSelect/SuperBoxSelect.js"></script>
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
<script>
	var winLogin;
	var formLogin;
	var store;
	var grid;
	var formEdit;
	var winEdit;
	var mytime;
	var teamx;
	var positionx;
	
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
		//var employeex = Ext.getCmp('employee').getValue();
		var player_lnamex = document.getElementById('player_lname').value;
		var player_fnamex = document.getElementById('player_fname').value;
		var team_idx = Ext.getCmp('team_id').value;
		var team_id2x = Ext.getCmp('team_id2').value;
		var team_id3x = Ext.getCmp('team_id3').value;
		var emailx = document.getElementById('email').value;
		var heightx = document.getElementById('height').value;
		var weightx = document.getElementById('weight').value;
		var position_idx = Ext.getCmp('position_id').value;
		//var releasedx = Ext.getCmp('released').value;
		//var genrex = Ext.getCmp('genre').value;
		//var taglinex = document.getElementById('tagline').value;
		//$date = date('h:i:s', strtotime($today . " +9 hours"));;

		//Ext.getCmp('employee').getValue()
		
		Ext.Ajax.request({
		params: {action: 'insert', player_fname:player_fnamex, player_lname:player_lnamex, team_id:team_idx, team_id2:team_id2x, team_id3:team_id3x, email:emailx, height:heightx, weight:weightx,position_id:position_idx},
		url: 'callback.php',
		success: function (resp,form,action) {
			//var data;
			//data = Ext.decode(resp.responseText);
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
	function refreshPlayer()
	{
		//window.location.href ="index.php";
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
		//var releasedx = Ext.getCmp('released').value;
		//var genrex = Ext.getCmp('genre').value;
		//var taglinex = document.getElementById('tagline').value;
		//$date = date('h:i:s', strtotime($today . " +9 hours"));;

		//alert(Ext.getCmp('employee').getValue());
	
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
		    { name: 'player_fname'},			
			{ name: 'player_lname' },
			{ name: 'team_id', sortType: Ext.data.SortTypes.asInt },
			{ name: 'team_id2', sortType: Ext.data.SortTypes.asInt },
			{ name: 'team_id3', sortType: Ext.data.SortTypes.asInt },
			{ name: 'email' },
			{ name: 'height' },
			{ name: 'weight' },
			{ name: 'position_id', sortType: Ext.data.SortTypes.asInt}
		]);
		teamx = new Ext.data.SimpleStore({
	        fields: ['id', 'team_name'],
		    data : <?php echo $z; ?>
	    });
		
		positionx = new Ext.data.SimpleStore({
	        fields: ['pid', 'position_abbv'],
		    data : <?php echo $y; ?>
	    });
		
		var string_edit = new Ext.form.TextField({
			allowBlank: false
		});
		
		var email_edit = new Ext.form.TextField({
			vtype:'email',
			vtypeText: 'Invalid email format must be user@domain.com',
			allowBlank: true						
		});
		
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
			valueField: 'id',
			listeners: {
    			change: function(combo, newValue, oldValue){
                    console.log("Old Value: " + oldValue);
                    console.log("New Value: " + newValue);
                },
                select: function(combo, record, index){
                    console.log("Record name: " + record.data.name);
                    console.log("Index: " + index);
                }
  			}
		});
		
		Ext.namespace("Ext.ux");
		Ext.ux.comboBoxRenderer = function(combo) {
		  return function(value) {
			var idx = combo.store.find(combo.valueField, value);
			var rec = combo.store.getAt(idx);
			return (rec==null ? '' : rec.get(combo.displayField));
		  };
		}
		
		var position_edit = new Ext.form.ComboBox({
			typeAhead: true,
			triggerAction: 'all',
			allowBlank: true,
			editable: true,
			selectOnFocus: true,
			mode: 'local',
			store: positionx,
			displayField:'position_abbv',
			valueField: 'pid',
			listeners: {}
		});
	
	    grid = new Ext.grid.EditorGridPanel({
		 	viewConfig: {
				emptyText: 'No records found'
			},
			renderTo: document.body,
			frame:true,
			title: 'BH League Players',
	        /*height:500,*/
	        width:1130,
			mode:'local',
			/*layout:'fit',*/
			autoHeight: true,
			enableColumnMove: false,
	        store: store,
			clicksToEdit: 2,
	        columns: [
				{header: "ID", dataIndex: 'id', width:100,sortable:true,align:'right'},
				{header: "First Name", dataIndex: 'player_fname', width:150,sortable:true,editor:string_edit},
				{header: "Last Name", dataIndex: 'player_lname', width:150,sortable:true,editor:string_edit},
				{header: "Tuesday Team", dataIndex: 'team_id', width:100,sortable:true,editor:team_edit},
				{header: "Saturday Team", dataIndex: 'team_id2', width:100,sortable:true,editor:team_edit,renderer:Ext.ux.comboBoxRenderer(team_edit)},
				{header: "Sunday Team", dataIndex: 'team_id3', width:100,sortable:true,editor:team_edit,renderer:Ext.ux.comboBoxRenderer(team_edit)},
				{header: "Email", dataIndex: 'email', width:150,sortable:true,editor:email_edit},
				{header: "Height", dataIndex: 'height', width:90,sortable:true,editor:string_edit},
				{header: "Weight", dataIndex: 'weight', width:90,sortable:true,editor:string_edit},
				{header: "Position", dataIndex: 'position_id', width:50,sortable:true,editor:position_edit,renderer:Ext.ux.comboBoxRenderer(position_edit)},
				{
					xtype: 'actioncolumn',
					width: 30,
					items: [{
						icon   : 'images/database_delete.png',  // Use a URL in the icon config
						tooltip: 'Remove Player ',
						handler: function(grid, rowIndex, colIndex) {
							var rec = store.getAt(rowIndex);
							DeleteRow(rec.get('id'),rec.get('player_fname'),rec.get('player_lname'));
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
							if (resp.responseText == '{success:true}') {
							//mask.hide();
							//Ext.MessageBox.alert('Message', 'Record ' + e.record.id + ' successfully updated');
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
			tbar: [{
				text: 'Add Player',
				icon: 'images/table_add.png',
				cls: 'x-btn-text-icon',
				handler: addNew
			},'-',{
				text: 'Refresh',
				icon: 'images/arrow_refresh.png',
				cls: 'x-btn-text-icon',
				handler: refreshPlayer
			}],plugins:[new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				,position:top
				,minChars:3
				,autoFocus:true,
				width:180,
				//menuStyle:'radio'
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
						fieldLabel: 'First Name',
						name: 'player_fname',
						id: 'player_fname',
						align: 'left',
						allowBlank:false,
						width:165

					},
					 {
						xtype: 'textfield',
						fieldLabel: 'Last Name',
						name: 'player_lname',
						id: 'player_lname',
						align: 'left',
						allowBlank:true,
						width:165

					},
					 { 
					 xtype: 'combo',
					 name: 'team_id',
					 id: 'team_id',
					 fieldLabel: 'Tuesday Team',
					 allowBlank:true,
					 mode: 'local',
					 store: teamx,
					 displayField:'team_name',
					 valueField:'id',
					 triggerAction: 'all'
					},
					 { 
					 xtype: 'combo',
					 name: 'team_id2',
					 id: 'team_id2',
					 fieldLabel: 'Saturday Team',
					 allowBlank:true,
					 mode: 'local',
					 store: teamx,
					 displayField:'team_name',
					 valueField:'id',
					 triggerAction: 'all'
					},
					 { 
					 xtype: 'combo',
					 name: 'team_id3',
					 id: 'team_id3',
					 fieldLabel: 'Sunday Team',
					 allowBlank:true,
					 mode: 'local',
					 store: teamx,
					 displayField:'team_name',
					 valueField:'id',
					 triggerAction: 'all'
					},
					 {
						xtype: 'textfield',
						fieldLabel: 'Email',
						name: 'email',
						id: 'email',
						vtype:'email',
						vtypeText: 'Invalid email format must be user@domain.com',
						align: 'left',
						width:165,
						allowBlank: true

					},
					 {
						xtype: 'textfield',
						fieldLabel: 'Height',
						name: 'height',
						id: 'height',
						align: 'left',
						width:165

					},
					 {
						xtype: 'textfield',
						fieldLabel: 'Weight',
						name: 'weight',
						id: 'weight',
						align: 'left',
						width:165

					},
					 { 
					 xtype: 'combo',
					 name: 'position_id',
					 id: 'position_id',
					 fieldLabel: 'Position',
					 allowBlank:true,
					 mode: 'local',
					 store: positionx,
					 displayField:'position_abbv',
					 valueField:'pid',
					 triggerAction: 'all'
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
				height: 340,
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