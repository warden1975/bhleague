<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View IO</title>

<script type="text/javascript" src="/yahoo/2.7.1/build/yahoo/yahoo.js"></script>
<script type="text/javascript" src="/yahoo/2.7.1/build/event/event.js"></script>
<script type="text/javascript" src="/yahoo/2.7.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yahoo/2.7.1/build/yuiloader/yuiloader.js"></script>
<script type="text/javascript" src="/yahoo/2.7.1/build/golive/golive.js"></script>
<link rel="stylesheet" type="text/css" href="/yahoo/2.7.1/build/golive/golive.css" />

<script type="text/javascript" src="/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui.min.js"></script>
<script language="javascript" src="/js/jquery/jquery.form.js"></script> 
<link rel="stylesheet" type="text/css" href="/js/jquery/jquery-ui.css" />
<script type="text/javascript" src="/js/validatenum.js"></script>

<!--<script type="text/javascript" src="/js/prototype.js"></script>-->

<script type="text/javascript">
	YAHOO.namespace("intranet");
	
	YAHOO.util.Event.onDOMReady(function() {
		YAHOO.intranet.loader = new YAHOO.util.YUILoader({
			require: ['datatable','container','button', 'json', 'fonts', 'dragdrop', 'tabview', 'menu', 'element','selector','treeview'], 
			base: '/yahoo/2.7.1/build/',
			loadOptional: true, 
			onSuccess: function() {
				//alert("check");	
				/*$('menuContainer').style.display = "none"
				$('headUrl').innerHTML = "Please Wait While loading the menu...  <img src=\"/images/rt/ajax-loader.gif\" border=\"0\" />";
				var timestamp = Number(new Date());		
				var ajx = null;
				ajx = new GLMAjax("/index-callback.php?operation=getMenu&timestamp=" + timestamp.toString(), "POST");
				ajx.request("", "getMenuResponse(o.responseText);");
				  
				
				$('frameMO').style.width = YAHOO.util.Dom.getViewportWidth().toString() + "px";
				$('frameMO').style.height = (YAHOO.util.Dom.getViewportHeight() - 185 ).toString() + "px";

				YAHOO.util.Event.addListener(window, "resize", function() {
						//$('test').innerHTML = "Width => " + YAHOO.util.Dom.getViewportWidth().toString();
						//$('test').innerHTML += " : Height =>" + YAHOO.util.Dom.getViewportHeight().toString();	
						$('frameMO').style.width = YAHOO.util.Dom.getViewportWidth().toString() + "px";
						$('frameMO').style.height = (YAHOO.util.Dom.getViewportHeight() - 185 ).toString() + "px";
						
						
				});	*/										
				$.ui.dialog.prototype._makeDraggable = function() { 
					this.uiDialog.draggable({
						containment: false
					});
				};
				YAHOO.intranet.getupload();
			 },
			onFailure: function(o) {
				alert("error: " + YAHOO.lang.dump(o));
			}
     	});		
		YAHOO.intranet.loader.insert();
	});	
	YAHOO.intranet.opendiag = function(){
	
		//alert($('#action').val());
		YAHOO.intranet.initEditor(250);
	}

	YAHOO.intranet.initEditor = function(dialogheight) {		
		$('#diveditor').css("text-align","center");
		$('#diveditor').dialog({height: dialogheight, width:400, resizable:false, modal: false,closeOnEscape: false, hide: "explode", position: 'center'});
	};
	YAHOO.intranet.getupload = function(){
		var connectionCallbackz = 
		{						
			cache:false ,
			success: function(o) 
			{
				//alert(o.responseText);
				//return;
				json = YAHOO.lang.JSON.parse(o.responseText);
				var myColumnDefs = 
				[
					{key:"id", label:"ID"}
					,{key:"upload_by", label:"Uploaded by"}
					,{key:"upload_date", label:"Date uploaded", sortable: true}
					,{key:"remarks", label:"Remarks", sortable: true}
					,{key:"file_path", label:"File", sortable: true}			
					,{key:'download',label:' ',formatter:function(elCell, oRecord, oColumn, oData) 
						{
							var idx = oRecord.getData("id");
							var file_path = oRecord.getData("file_path");
							//var domx = oRecord.getData("domain");
							//DelRecord
							//elCell.innerHTML = '<a href="callback.php?action=downloadme&f='+idx+'">Download</a>';
							elCell.innerHTML = "<a href='./uploads/"+file_path+"' target='_blank'>download</a>";
							elCell.style.cursor = 'pointer';
						}
					}					
					/*,{key:'delete',label:' ',formatter:function(elCell, oRecord, oColumn, oData) 
						{
							var idx = oRecord.getData("id");
							//var domx = oRecord.getData("domain");
							//DelRecord
							elCell.innerHTML = '<img src="images/delete.png"  onclick="alert(\'' +idx +'\')" alt="Delete" title="Delete " />';
							elCell.style.cursor = 'pointer';
						}
					}*/
				];				
				var oConfigs = {paginator: new YAHOO.widget.Paginator({rowsPerPage: 50})};	
				var myDataSource = new YAHOO.util.DataSource(json);
				myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
				myDataSource.responseSchema =
				{				
					resultsList: "ResultSet.datatable",
					fields:["id","remarks","file_path","upload_date","upload_by"]

				};
				
				YAHOO.intranet.cscTable = new YAHOO.widget.DataTable('dt_search_domain', myColumnDefs,myDataSource,oConfigs);	
				YAHOO.intranet.cscTable.subscribe("rowMouseoverEvent", YAHOO.intranet.cscTable.onEventHighlightRow);
				YAHOO.intranet.cscTable.subscribe("rowMouseoutEvent", YAHOO.intranet.cscTable.onEventUnhighlightRow);
				
			
				YAHOO.intranet.cscTable.render();							
			}
		};
		var getJSON = YAHOO.util.Connect.asyncRequest("GET", "callback.php?action=getupload" ,connectionCallbackz);		
	}	
	YAHOO.intranet.saveOffer = function() {
		//if(YAHOO.intranet.FormValidate("saveoffer")){
			$('#action').val('saveoffer');
			$('#editor').submit();
		//}
	}	
	function showSearchDomain()
	{
		document.getElementById('dt_search_domain').innerHTML ="";
		xdomain = document.getElementById('searchall').value	
		showWaitingDialog();
		$('content').style.display = "inline";
		var connectionCallbackz = 
		{						
			cache:false ,
			success: function(o) 
			{
						json = YAHOO.lang.JSON.parse(o.responseText);
						var myColumnDefs = 
						[
							{key:"id", label:"ID",hidden:false}
							,{key:"domain", label:"Domain"}
							,{key:"username", label:"Username", sortable: true}
							,{key:"password", label:"Password", sortable: true}
							,{key:"last_update", label:"Last Update", sortable: true}
							,{key:"enabled", label:"Enabled", sortable: true}
							,{key:"host_description", label:"Host Description", sortable: true}			
							,{key:'delete',label:' ',formatter:function(elCell, oRecord, oColumn, oData) 
								{
									var idx = oRecord.getData("id");
									var domx = oRecord.getData("domain");
									elCell.innerHTML = '<img src="images/delete.png"  onclick="DelRecord(\'' +idx +'\',\'' + domx + '\')" alt="Delete Domain Credential" title="Delete Domain Credential" />';
									elCell.style.cursor = 'pointer';
								}
							}
							,{key:'edit',label:' ',formatter:function(elCell, oRecord, oColumn, oData) 
								{
									Dom.get("id").value = oRecord.getData("id");
									Dom.get("domainx").value = oRecord.getData("domain");
									Dom.get("userx").value = oRecord.getData("username");
									Dom.get("passwdx").value = oRecord.getData("password");
									//Dom.get("last_updatex").value = oRecord.getData("last_update");
									Dom.get("enablex").value = (oRecord.getData("enabled")=='yes')? 1 : 0;
									elCell.innerHTML = '<img src="images/application_edit.png"  onclick="EditDomainCredential(\'' + oRecord.getData("id") + '|' 
																																 + oRecord.getData("domain") + '|' 
																																 + oRecord.getData("username") + '|' 
																																 + oRecord.getData("password") + '|'																														
																																 + oRecord.getData("enabled") + '|'
																																 + '\')" alt="Copy" title="Edit Domain Credential" alt="Edit Domain Credential" />'; 
									elCell.style.cursor = 'pointer';
								}
							}
							
						];
						var oConfigs = {
								paginator: new YAHOO.widget.Paginator({
									rowsPerPage: 50
								
								})
	
						};		
						var myDataSource = new YAHOO.util.DataSource(json);
						myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
						myDataSource.responseSchema =
						{				
							resultsList: "ResultSet.datatable",
							fields:
							[
								"id",
								"domain",
								"username",
								"password",
								"last_update",
								"enabled",
								"host_description",
								"delete",
								"edit"
							]
	
						};
						
						YAHOO.intranet.cscTable = new YAHOO.widget.DataTable('dt_search_domain', myColumnDefs,myDataSource,oConfigs);	
						YAHOO.intranet.cscTable.subscribe("rowMouseoverEvent", YAHOO.intranet.cscTable.onEventHighlightRow);
						YAHOO.intranet.cscTable.subscribe("rowMouseoutEvent", YAHOO.intranet.cscTable.onEventUnhighlightRow);
						
					
						YAHOO.intranet.cscTable.render();
						YAHOO.example.container.wait.hide();
					}
		};
		var getJSON = YAHOO.util.Connect.asyncRequest("GET", "backscript.php?action=search_domain_details&domain=" + xdomain,connectionCallbackz);
	
	}	
	
	YAHOO.intranet.closeEditor = function() {
		$('#diveditor').dialog("close");	
	};		
	YAHOO.intranet.saveOffer = function() {
		//if(YAHOO.intranet.FormValidate("saveoffer")){
			//$('#action').val('saveoffer');
			$('#editor').submit();
		//}
	}	
	YAHOO.intranet.resetform = function() {	
		$('#txt_remarks').val('');
		document.editor.txt_file.value = '';	
	}
	 $('#editor').ajaxForm({
		beforeSubmit: function(a,f,o) {
			o.dataType = 'html';//$('#uploadResponseType')[0].value;
			//$('#uploadOutput').html('Submitting...');
		},
		success: function(data) {
			if (typeof data == 'object' && data.nodeType)
				data = elementToString(data.documentElement, true);
			else if (typeof data == 'object')
				data = objToString(data);
			var jsonx = YAHOO.lang.JSON.parse(data);	
			if( $('#action').val() == 'updateoffer'){
				
				if(jsonx.error == 0){
					if($.trim(jsonx.newname) != '')
						$('#tempiconpath').val(jsonx.newname);
					YAHOO.intranet.viewImgPrev('');
				}else{
					alert("Update error ["+jsonx.error+"]: "+jsonx.status);
				}
			}else{
				if(jsonx.error != 0){
					alert("Save error ["+jsonx.error+"]: "+jsonx.status);				
				}			
				YAHOO.intranet.resetform();
				YAHOO.intranet.closeEditor();
			}
			YAHOO.intranet.getupload();
		}
	});	
</script>
</head>

<body class="yui-skin-sam" style="font-family:Calibri,Arial, Helvetica, sans-serif; font-size:11px;">
<input type="button" onclick="YAHOO.intranet.opendiag()" value="New Upload" />
<input type="button" onclick="YAHOO.intranet.getupload()" value="refresh" />
<div id='diveditor' title="New upload" style="display:none;">
	<br />
  <form id="editor" name="editor" style="font-family:Calibri,Arial, Helvetica, sans-serif; font-size:11px; text-align:left;" enctype="multipart/form-data" method="POST" encoding="multipart/form-data" action="http://intranet.pocketlead.com/include/insertion_orders/callback.php?">
    <input type="hidden" name="action" id="action" value="saveupload" />  
    <div style="clear:both">    </div>

    <div style="clear:both;">
      <div style="clear:both;">Remarks <span id="wartxt_offerdesc" style="color:#FF0000;font-weight:bold;display:none">-BLANK-</span></div>
       <!-- <textarea name="txt_offerdesc" id="txt_offerdesc" class="txt-search" rows="4" cols="60" ></textarea>-->
        <input type="text" id="txt_remarks" name="txt_remarks"  size="50" maxlength="90"/>
    </div>  

    <div style="clear:both;">
      <div style="clear:both;">Select File</div>
        <input type="file" name="txt_file" id="txt_file" class="txt-search" style="width:351px;" size="50"  /> <!-- onchange="showLocalImage(this.value)" -->
    </div>  
    <div style="clear:both;"><br />
	    <input type="button" value="Save" onclick="YAHOO.intranet.saveOffer();"/>
    </div>
   </form>
</div> 
	<div id="dt_search_domain" style="min-width:1000px; overflow:auto; font-size:10.5px; "   ></div>
</body>
</html>
