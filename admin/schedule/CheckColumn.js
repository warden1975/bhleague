/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.ns('Ext.ux.grid');

/**
 * @class Ext.ux.grid.CheckColumn
 * @extends Ext.grid.Column
 * <p>A Column subclass which renders a checkbox in each column cell which toggles the truthiness of the associated data field on click.</p>
 * <p><b>Note. As of ExtJS 3.3 this no longer has to be configured as a plugin of the GridPanel.</b></p>
 * <p>Example usage:</p>
 * <pre><code>
var cm = new Ext.grid.ColumnModel([{
       header: 'Foo',
       ...
    },{
       xtype: 'checkcolumn',
       header: 'Indoor?',
       dataIndex: 'indoor',
       width: 55
    }
]);

// create the grid
var grid = new Ext.grid.EditorGridPanel({
    ...
    colModel: cm,
    ...
});
 * </code></pre>
 * In addition to toggling a Boolean value within the record data, this
 * class toggles a css class between <tt>'x-grid3-check-col'</tt> and
 * <tt>'x-grid3-check-col-on'</tt> to alter the background image used for
 * a column.
 */
Ext.ux.grid.CheckColumn = Ext.extend(Ext.grid.Column, {

    /**
     * @private
     * Process and refire events routed from the GridView's processEvent method.
     */
    processEvent : function(name, e, grid, rowIndex, colIndex){
        if (name == 'mousedown' || name == 'click') {
			
            var record = grid.store.getAt(rowIndex);
			
            record.set(this.dataIndex, !record.data[this.dataIndex]);
			this.fireEvent(name);
			if(record.data.bye_week==true)
			{
				var retval = 1
			}
			else
			{
				var retval =0;
			}
			//alert(record.data.bye_week);
//			alert(record.data.id);
			Update_ByeWeek(record.data.id, retval)
            return false; // Cancel row selection.
        } else {
            return Ext.grid.ActionColumn.superclass.processEvent.apply(this, arguments);
        }
    },

    renderer : function(v, p, record){
		
		//alert(record.data.team1);
        p.css += ' x-grid3-check-col-td'; 
		//alert(v)
//        return String.format('<div class="x-grid3-check-col{0}">&#160;</div>', v ? '-on' : '');
		if(v==1)
		{
		return String.format('<div class="x-grid3-check-col{0}-on">&#160;</div>','') 
		}
		else
		{
		return String.format('<div class="x-grid3-check-col{0}">&#160;</div>','');	
		}
    },

    // Deprecate use as a plugin. Remove in 4.0
    init: Ext.emptyFn
});

// register ptype. Deprecate. Remove in 4.0
Ext.preg('checkcolumn', Ext.ux.grid.CheckColumn);

// backwards compat. Remove in 4.0
Ext.grid.CheckColumn = Ext.ux.grid.CheckColumn;

// register Column xtype
Ext.grid.Column.types.checkcolumn = Ext.ux.grid.CheckColumn;