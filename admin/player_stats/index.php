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

$s_arr = array();

$sql = "SELECT `id`, `name` FROM `bhleague`.`seasons`";
if ($rsx = $db->query($sql)) {
	while ($rowx = $rsx->fetch_object()) $s_arr[] = "['{$rowx->id}','{$rowx->name}']";
	$rsx->close();
}
$zx = '['.implode(', ', $s_arr).']';
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
	<link rel="stylesheet" type="text/css" href="./css/icons.css">
  <link rel="stylesheet" type="text/css" href="./css/Ext.ux.grid.RowActions.css">
  <link rel="stylesheet" type="text/css" href="./css/empty.css" id="theme">
  <link rel="stylesheet" type="text/css" href="./css/webpage.css">
  <link rel="stylesheet" type="text/css" href="./css/gridsearch.css">
 	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../extjs/ext-all.js"></script>
	<script src="Exporter-all.js" type="text/javascript"></script>
	<script type="text/javascript" src="SuperBoxSelect/SuperBoxSelect.js"></script>
	 <script type="text/javascript" src="./js/WebPage.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.ThemeCombo.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.IconMenu.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.Toast.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.grid.Search.js"></script>
	  <script type="text/javascript" src="./js/Ext.ux.grid.RowActions.js"></script>
	  <script type="text/javascript" src="./jsjs/swfupload.js"></script>
    <script type="text/javascript" src="./jsjs/swfupload.queue.js"></script>
    <script type="text/javascript" src="js/swfupload.cookies.js"></script>

	<style>
		.cTextAlign{
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
	var playerx;
	var teamidx;
	var playeridx;
	var seasonx;
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
		var game_datex = Ext.getCmp('game_date').value;
		var player_idx = Ext.getCmp('player_id').value;
		var team_idx = Ext.getCmp('team_id').value;
		var game_points_1x = document.getElementById('game_points_1').value;
		var game_attempts_1x = document.getElementById('game_attempts_1').value;
		var game_points_2x = document.getElementById('game_points_2').value;
		var game_attempts_2x = document.getElementById('game_attempts_2').value;
		var game_points_3x = document.getElementById('game_points_3').value;
		var game_attempts_3x = document.getElementById('game_attempts_3').value;
		var game_assistsx = document.getElementById('game_assists').value;
		var game_reboundsx = document.getElementById('game_rebounds').value;
		var seasonxx = Ext.getCmp('season').value;
		
		//var myquery = formLogin.getForm().getValues(true);
		//Ext.getCmp('employee').getValue()
		
		Ext.Ajax.request({
		params: {action:'insert',game_date:game_datex,player_id:player_idx,team_id:team_idx,game_points_1:game_points_1x,game_attempts_1:game_attempts_1x,game_points_2:game_points_2x,game_attempts_2:game_attempts_2x,game_points_3:game_points_3x,game_attempts_3:game_attempts_3x,game_assists:game_assistsx,game_rebounds:game_reboundsx,season:seasonxx},
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
		//var releasedx = Ext.getCmp('released').value;
//		var genrex = Ext.getCmp('genre').value;
//		var taglinex = document.getElementById('tagline').value;
		//$date     = date('h:i:s', strtotime($today . " +9 hours"));;

		 //alert(Ext.getCmp('employee').getValue());
	//	
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
	
	function DeleteRow(ids, name)
	{
		var sm=grid.getSelectionModel();
        var sel=sm.getSelections();
		Ext.Msg.show({
							title: 'Remove Player', 
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
											Ext.Msg.alert('Error','Unable to delete Player Stats'); 
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
		
		// Ext.form.VTypes.nameVal  = /^([A-Z]{1})[A-Za-z\-]+ ([A-Z]{1})[A-Za-z\-]+/;
		// Ext.form.VTypes.nameMask = /[A-Za-z\- ]/;
		// Ext.form.VTypes.nameText = 'In-valid Director Name.';
		// Ext.form.VTypes.name 	= function(v){
			// return Ext.form.VTypes.nameVal.test(v);
		// };
		
	   // var genres = new Ext.data.SimpleStore({
//	        fields: ['id', 'genre'],
//	        //data : [['0','New Genre'],['1','Comedy'],['2','Drama'],['3','Action'],['4','Mystery']]
//		    data : <?php //echo $z; ?>
//	    });
//		
//		function genre_name(val){
//			return genres.queryBy(function(rec){
//				return rec.data.id == val;
//			}).itemAt(0).data.genre;
//		}
	
		var ds_model = Ext.data.Record.create([
			{ name: 'id', sortType: Ext.data.SortTypes.asInt },
		    { name: 'game_date', sortType: Ext.data.SortTypes.asDate},			
			{ name: 'player_id', sortType: Ext.data.SortTypes.asInt },
			{ name: 'team_id', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_points_1', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_attempts_1', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_points_2', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_attempts_2', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_points_3', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_attempts_3', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_assists', sortType: Ext.data.SortTypes.asInt },
			{ name: 'game_rebounds', sortType: Ext.data.SortTypes.asInt },
			{ name: 'season', sortType: Ext.data.SortTypes.asInt }
			
			
		]);
		 teamx = new Ext.data.SimpleStore({
	        fields: ['id', 'team_name'],
		    data : <?php echo $z; ?>
	    });
		playerx = new Ext.data.SimpleStore({
	        fields: ['idx', 'player'],
		    data : <?php echo $iz; ?>
	    });
		
		seasonx = new Ext.data.SimpleStore({
	        fields: ['id', 'name'],
		    data : <?php echo $zx; ?>
	    });
		
		var string_edit = new Ext.form.TextField({
			allowBlank: false
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
		var player_edit = new Ext.form.ComboBox({
			typeAhead: true,
			triggerAction: 'all',
			editable:false,
			//readonly:true,
			mode: 'local',
			store: playerx,
			displayField:'player',
			valueField: 'idx',
			listeners: {
    select: function(combo, record, index) {
      //alert(combo.getValue()); // Return Unitad States and no USA
    }
	}
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
			title: 'BH League Players Statistics',
	        height:300,
	        width:1400,
			mode:'local',
			layout:'fit',
			autoHeight: true,
			enableColumnMove: false,
	        store: store,
			clicksToEdit: 2,
	        columns: [
				{header: "ID", dataIndex: 'id', width:100,sortable:true,align:'right'},
				{header: "Game Date", dataIndex: 'game_date', width:150,sortable:true,editor:new Ext.form.DateField({format :'Y-m-d'})},
				{header: "Player", dataIndex: 'player_id', width:150,sortable:true,editor:player_edit},
				{header: "Team ", dataIndex: 'team_id', width:150,sortable:true},
				{header: "FT Point", dataIndex: 'game_points_1', width:90,sortable:true,editor:number_edit,align:'right' },
				{header: "FT Attempt", dataIndex: 'game_attempts_1',width:90,sortable:true,editor:number_edit,align:'right' },
				{header: "2-Pt Point",  dataIndex: 'game_points_2', width:90,sortable:true,editor:number_edit,align:'right'},
				{header: "2-Pt Attempt", dataIndex: 'game_attempts_2',width:90,sortable:true,editor:number_edit,align:'right'},
				{header: "3-Pt Point", dataIndex: 'game_points_3', width:90,sortable:true,editor:number_edit,align:'right'},
				{header: "3-Pt Attempt", dataIndex: 'game_attempts_3',width:90,sortable:true,editor:number_edit,align:'right'},
				{header: "Assists", dataIndex: 'game_assists',width:90,sortable:true,editor:number_edit,align:'right'},
				{header: "Rebounds", dataIndex: 'game_rebounds',width:90,sortable:true,editor:number_edit,align:'right'},
				{header: "Season", dataIndex: 'season', width:90,sortable:true,editor:season_edit},
				
				{
					xtype: 'actioncolumn',
					width: 30,
					items: [{
						icon   : 'images/database_delete.png',  // Use a URL in the icon config
						tooltip: 'Remove Player Stats ',
						handler: function(grid, rowIndex, colIndex) {
							var rec = store.getAt(rowIndex);
							DeleteRow(rec.get('id'),rec.get('player_id'));
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
//							refreshPlayer();
//							store.reload();
//							grid.getstore();
//							grid.getView().refresh();
							if (resp.responseText == '{success:true}') {
				//mask.hide();
							Ext.MessageBox.alert('Message', 'Record ' + e.record.id + ' successfully updated');
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
								title: 'Remove Player Stats', 
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
												Ext.Msg.alert('Error','Unable to delete Player Stats'); 
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
				text: 'Add Player Stats',
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
//				,menuStyle:'radio'
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
	function getteamid(idx)
	{
		Ext.Ajax.request({
		params: {action: 'getteamid', player_id: idx},
		url: 'callback.php',
		success: function (resp,form,action) {
		
		Ext.getCmp('team_id').setValue(resp.responseText) ;
		teamidx = resp.responseText;
		//team_id.setValue(team_id.getStore().getAt(resp.responseText).get(cb.valueField));
			 
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
	}
	function addNew()
	{
		var mystore = new Ext.data.SimpleStore({
	        fields: ['id', 'team_name'],
		    data : <?php echo $z; ?>
	    });
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
					{ xtype: 'combo',
					  name: 'player_id',
					  id: 'player_id',
					  fieldLabel: 'Player',
					  mode: 'local',
					  store: playerx,
					  displayField:'player',
					  valueField:'idx',
					  triggerAction: 'all',
					  width:160,
					 listeners: {
						select: function(combo, record, index) {
							//alert(combo.getValue());
						//Ext.getCmp('player_id').setValue(combo.getvalue()) ; 
						 getteamid(combo.getValue()); 
						 // Return Unitad States and no USA
						}
					  
					 }
					 },
					 { 
					 xtype: 'combo',
					 name: 'team_id',
					 id: 'team_id',
					 fieldLabel: 'Team Id',
					 mode: 'local',
					 store: teamx,
					 displayField:'team_name',
					 valueField:'id',
					 disabled:false,
					 triggerAction: 'all',
					 //readonly:true,
//					 editable:false,
					 typeAhead: false,
					 //hideTrigger:true,
					 width:160
					 },
					 new Ext.form.NumberField({  
					 fieldLabel: 'Free Throw Point', 
					 allowBlank: false, 
					 name:'game_points_1', 
					 id:'game_points_1', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:true,
					 allowNegative: false,
					 minValue: 0}),	
					 				 
					 new Ext.form.NumberField({  
					 fieldLabel: 'Free Throw Attempt', 
					 allowBlank: false, 
					 name:'game_attempts_1', 
					 id:'game_attempts_1', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0}),
					 
					 new Ext.form.NumberField({  
					 fieldLabel: '2 Point Shot', 
					 allowBlank: false, 
					 name:'game_points_2', 
					 id:'game_points_2', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0}),
					 
					 new Ext.form.NumberField({  
					 fieldLabel: '2 Point Attempt', 
					 allowBlank: false, 
					 name:'game_attempts_2', 
					 id:'game_attempts_2', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0}),
					 
					 new Ext.form.NumberField({  
					 fieldLabel: '3 Point Shot', 
					 allowBlank: false, 
					 name:'game_points_3', 
					 id:'game_points_3', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0
					 }),
					 
					 new Ext.form.NumberField({  
					 fieldLabel: '3 Point Attempt', 
					 allowBlank: false, 
					 name:'game_attempts_3', 
					 id:'game_attempts_3', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:false,
					 allowNegative: false,
					 minValue: 0
					 }),
					 
					 new Ext.form.NumberField({  
					 fieldLabel: 'Assists', 
					 allowBlank: false, 
					 name:'game_assists', 
					 id:'game_assists', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:true,
					 allowNegative: false,
					 minValue: 0
					 }),
					 
					 new Ext.form.NumberField({  
					 fieldLabel: 'Rebounds', 
					 allowBlank: false, 
					 name:'game_rebounds', 
					 id:'game_rebounds', 
					 fieldClass: 'cTextAlign',
					 allowDecimals:true,
					 allowNegative: false,
					 minValue: 0})
					 ,
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
				width: 530,
				height: 400,
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