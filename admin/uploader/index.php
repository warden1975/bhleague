<?php
require_once('/home/bhleague/public_html/admin/class/db.cls.php');
$db = new DB_Connect(MYSQL_INTRANET, true);
//extract($_REQUEST);
$array = array();
$arrx = array();

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


$sql = "SELECT CONCAT(player_fname, ' ', player_lname) as player, `id` as idx FROM bhleague.players ORDER BY team_id";

//echo $sql;exit;

$rx = $db->query($sql);


while ($rowx = $rx->fetch_assoc())
{
	$ix ="'".$rowx['idx']."'";
	$iy = "'".$rowx['player']."'";
    $arrx[] = "[ {$ix}, {$iy} ]";

}
$iz = ' [' . implode(', ', $arrx) . ']';
//echo $iz; exit;
?>

<html>
<head>
    <title>BH League :: Players Stats</title>
    <!--<link rel="stylesheet" type="text/css" href="/lib/extjs/resources/css/ext-all.css" />
 	<script type="text/javascript" src="/lib/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="/lib/extjs/ext-all.js"></script>-->
	<link rel="stylesheet" type="text/css" href="SuperBoxSelect/superboxselect.css" />
	<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
	<!--<link rel="stylesheet" type="text/css" href="./css/icons.css">-->
	<!--<link rel="stylesheet" type="text/css" href="./css/Ext.ux.upload.css">-->
	<link rel="stylesheet" type="text/css" href="./css/examples.css">	
	<link rel="stylesheet" type="text/css" href="./css/fileuploadfield.css"/>  
 	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../extjs/ext-all.js"></script>
	<!--<script src="Exporter-all.js" type="text/javascript"></script>-->
	 <!--<script type="text/javascript" src="js/Ext.ux.grid.RowActions.js"></script>-->
	<!--<script type="text/javascript" src="SuperBoxSelect/SuperBoxSelect.js"></script>-->
	<!--<script type="text/javascript" src="./js/WebPage.js"></script>-->
	<!-- <script type="text/javascript" src="js/Ext.ux.StatusBar.js"></script>-->
    <script type="text/javascript" src="js/FileUploadField.js"></script>
    
	 <style>
        .upload-icon {
            background: url('images/image_add.png') no-repeat 0 0 !important;
        }
        #fi-button-msg {
            border: 2px solid #ccc;
            padding: 5px 10px;
            background: #eee;
            margin: 5px;
            float: left;
        }
    </style>
	<script>

	
	/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
  var msg = function(title, msg){
        Ext.Msg.show({
            title: title,
            msg: msg,
            minWidth: 200,
            modal: true,
            icon: Ext.Msg.INFO,
            buttons: Ext.Msg.OK
        });
    };
 function showForm()
 {
 	var fp = new Ext.FormPanel({
        renderTo: 'fi-form',
        fileUpload: true,
        width: 500,
        frame: true,
        title: 'File Upload ',
        autoHeight: true,
        bodyStyle: 'padding: 10px 10px 10px 10px;',
        labelWidth: 50,
        defaults: {
            anchor: '95%',
            allowBlank: false,
            msgTarget: 'side'
        },
        items: [{
            xtype: 'textfield',
            fieldLabel: 'Title',
			id: 'title',
			name: 'title'
        },{
            xtype: 'fileuploadfield',
            id: 'photo-path',
            emptyText: 'Select an image',
            fieldLabel: 'Photo',
            name: 'photo-path',
            buttonText: '',
            buttonCfg: {
                iconCls: 'upload-icon'
            }
        }],
        buttons: [{
            text: 'Save',
            handler: function(){
                if(fp.getForm().isValid()){
	                fp.getForm().submit({
	                    url: 'file-upload.php',
	                    waitMsg: 'Uploading your photo...',
	                    success: function(fp, o){
	                         msg('Success', 'Processed file');
							 ShowGrid();
	                    }
	                });
                }
            }
        },{
            text: 'Reset',
            handler: function(){
                fp.getForm().reset();
            }
        }]
    });
 }
	function refreshAll()
	{
		//window.location.href ="index.php";
		store.reload();
		grid.getstore();
		grid.getView().refresh();
	}
	function DeleteRow(ids, name)
	{
		var sm=grid.getSelectionModel();
        var sel=sm.getSelections();
		Ext.Msg.show({
							title: 'Remove File', 
							buttons: Ext.MessageBox.YESNOCANCEL,
							msg: 'Remove '+ name + ' ?',
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
											Ext.Msg.alert('Error','Unable to delete File '); 
										}
									});
								}
							}
						});
	}
  function showGrid()
  {
  	var ds_model = Ext.data.Record.create([
			{ name: 'id', sortType: Ext.data.SortTypes.asInt },
		    { name: 'name'},			
			{ name: 'title' },
			{ name: 'url' }
			
		]);
  	store = new Ext.data.Store({
			url: 'datasource.php',
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				id:'id'
			}, ds_model)
	    });
		var string_edit = new Ext.form.TextField({
			allowBlank: false
		});
  	grid = new Ext.grid.EditorGridPanel({
		 	viewConfig: {
				emptyText: 'No records found'
			},
			renderTo: 'fi-grid',
			frame:true,
			title: 'Photos',
	        /*height:500,*/
	        width:720,
			mode:'local',
			/*layout:'fit',*/
			autoHeight: true,
			enableColumnMove: false,
	        store: store,
			clicksToEdit: 2,
	        columns: [
				{header: "ID", dataIndex: 'id', width:100,sortable:true,align:'right'},
				{header: "File Name", dataIndex: 'name', width:150,sortable:true},
				{header: "Description", dataIndex: 'title', width:150,sortable:true,editor:string_edit},
				{header: "File Path", dataIndex: 'url', width:250,sortable:true},
				
				{
					xtype: 'actioncolumn',
					width: 30,
					items: [{
						icon   : 'images/database_delete.png',  // Use a URL in the icon config
						tooltip: 'Remove File ',
						handler: function(grid, rowIndex, colIndex) {
							var rec = store.getAt(rowIndex);
							DeleteRow(rec.get('id'),rec.get('name'));
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
								title: 'Remove File', 
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
				text: 'Refresh',
				icon: 'images/arrow_refresh.png',
				cls: 'x-btn-text-icon',
				handler: refreshAll
			}]
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
  }


    Ext.onReady(function(){

    Ext.QuickTips.init();
	
	 showForm();
	 showGrid();
   

    

});
	</script>
</head>
<body >

<div id="fi-form"></div>
<div style="clear:both; height:20px;"></div>
<div id="fi-grid"></div>
</body>
</html>