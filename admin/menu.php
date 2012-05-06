<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Application Menubar with Menus</title>

<!-- Ext Files -->
<link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css" />
 	<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../extjs/ext-all.js"></script>
	
<!-- Global Demo Files -->
<link rel="stylesheet" type="text/css" href="global.css" />
<style type="text/css">
.x-menu.x-menu-horizontal .x-menu-list {
    overflow: hidden;
}
.x-menu.x-menu-horizontal .x-menu-list .x-menu-list-item {
    float: left;
}
.x-menu.x-menu-horizontal .x-menu-list .x-menu-list-item .x-menu-item-arrow {
    background: url(horizontal-menu-parent.gif) no-repeat right 9px;
}

		.cTextAlign{
    text-align: right;
	width:165px;

   }
.sports { background-image: url(images/sport_basketball.png); }
	.stats { background-image: url(images/statistics.png); }
	.group { background-image: url(images/groups.png); }
	.players { background-image: url(images/groups.png); }
	.player { background-image: url(images/player.png); }
	.list { background-image: url(images/application_view_list.png); }
	.logout { background-image: url(images/logout.png); }
</style>
<script type="text/javascript">
    Ext.onReady(function() {
        var menu = new Ext.menu.Menu({
            id: 'basicMenu',
            items: [{
                text: 'An item',
				iconCls: 'players',
                handler: clickHandler
            },
                new Ext.menu.Item({
                    text: 'Another item',
                    handler: clickHandler
                }),
                '-',
                new Ext.menu.CheckItem({
                    text: 'A check item',
                    checkHandler: checkHandler
                }),
                new Ext.menu.CheckItem({
                    text: 'Another check item',
                    checkHandler: checkHandler
                })
            ]
        });

        var menu2 = new Ext.menu.Menu({
            items: [{
                text: 'Menu 1, option 1'
            }]
        });

        var tb = new Ext.menu.Menu({
        	region: 'north',
        	height: 28,
            floating: false,
            hidden: false,
            enableScrolling: false,
            cls: 'x-menu-horizontal',
            subMenuAlign: 'tl-bl?',
            items: [{
                text: 'Our first Menu',
				iconCls: 'players',
                menu: menu  // assign our menu to this button
            }, {
                text: 'Our second Menu',
                menu: menu2  // assign our menu to this button
            }]
        });

        function clickHandler() {
            alert('Clicked on a menu item');
        }

        function checkHandler() {
            alert('Checked a menu item');
        }
        
        new Ext.Viewport({
        	layout: 'border',
        	items: [ tb, {
        		region: 'west',
        		title: 'West',
        		width: 200
        	}, {
        			region: 'center',
        			title: 'Center'
        	}]
        });
        	
    });
</script>
</head>
<body></body>
</html>