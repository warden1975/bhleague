/*!

 * Ext JS Library 3.4.0

 * Copyright(c) 2006-2011 Sencha Inc.

 * licensing@sencha.com

 * http://www.sencha.com/license

 */

Ext.namespace('Ext.bhlcommondata');



Ext.bhlcommondata.gamedays = [

	[1, 'Tuesday League'], 

	[5, 'Saturday League'], 

	[6, 'Sunday League']

];



//Ext.bhlcommondata.getsched = function(){
//
//		Ext.Ajax.request({
//
//			url: 'next_game.php',
//
//			params: {action: "new"},
//
//			success: function(response){
//
//				var result = response.responseText;
//
//				//Ext.setValues('grid-next_game') = result;
//
//				Ext.fly('grid-next_game').dom.innerHTML = result;
//
//			}
//
//		});
//
//}

Ext.bhlcommondata.format_underline = function (value, metadata, record) {

	metadata.attr = ' style="text-decoration:underline;" ';

	return value;

}



Ext.bhlcommondata.app = function() {



	var store_gamedays = new Ext.data.ArrayStore({

		fields: ['did', 'gameday'],

		data: Ext.bhlcommondata.gamedays

	});

	

	return {

		init: function() {

			var combo_gamedays = new Ext.form.ComboBox({

				store: store_gamedays,

				displayField:'gameday',

				valueField:'did',

				typeAhead: true,

				mode: 'local',

				forceSelection: true,

				triggerAction: 'all',

				emptyText:'Select game league...',

				selectOnFocus:true,

				applyTo: 'local-gamedays',

				listeners: {

					select: function(combo, record, index) {

						//console.log('check: '+combo.getValue()+', '+record.data.gameday+', '+index);

						var bhlOGL = new Object();

						bhlOGL['id'] = combo.getValue();

						bhlOGL['value'] = record.data.gameday;

						var newdate = new Date(new Date().getTime()+(1000*60*60*24*365));

						Ext.util.Cookies.set('bhlOGL',Ext.encode(bhlOGL),newdate);

						window.location.reload();

					}

				}

			});

			

			try {

				var bhlOGLval = (Ext.decode(Ext.util.Cookies.get('bhlOGL'))).value;

				//console.log('Cookie bhlOGL.value: '+bhlOGLval);

				combo_gamedays.setValue(bhlOGLval);

			} catch (e) {}

		}

	};

} ();



Ext.onReady(Ext.bhlcommondata.app.init, Ext.bhlcommondata.app);



/**

 * Simple class for horizontal scrolling of text in a box written for Ext Core 3

 * 

 * @author Ivan Novakov <ivan.novakov@debug.cz>

 * @link http://www.debug.cz/examples/js/marquee/

 */



Ext.namespace('Ext.ux');



/**

 * @class Ext.ux.Marquee

 * @param {} config

 */

Ext.ux.Marquee = function(config) {

    Ext.apply(this, {

        // the scroll step in pixels

        step : 5,

        // the update interval

        interval : 100,

        // the CSS class of the container

        containerCls : 'undefined-container-class',

        // the CSS class of the text element

        textCls : 'undefined-text-class',

        // the element ID of the text element

        textElmId : 'mtext',

        text : [

            'undefined text'

        ]

    });



    Ext.apply(this, config);



    this.currentTextIndex = -1;

    this.textElm = null;

    this.currentTask = null;

    this.taskRunner = null;



    this.addEvents({

        beforetextupdate : true

    });

};



/**

 * @class Ext.ux.Marquee

 * @extends Ext.util.Observable

 */

Ext.extend(Ext.ux.Marquee, Ext.util.Observable, {



    _initDom : function() {

        var elm = Ext.DomHelper.append(document.body, {

            tag : 'div',

            cls : this.containerCls,

            children : [

                {

                    tag : 'span',

                    id : this.textElmId,

                    cls : this.textCls,

                    html : '&nbsp;'

                }

            ]

        });

    },



    init : function() {

        this._initDom();

        this.textElm = this._getTextElm();

        var text = this._getCurrentText();



        this._resetTextElm();



        this.currentTask = {

            run : this.move,

            interval : this.interval,

            scope : this

        };



        this.taskRunner = new Ext.util.TaskRunner();

        this.taskRunner.start(this.currentTask);

    },



    move : function() {

        if (this.textElm.getRight() <= 0) {

            this.fireEvent('beforetextupdate');

            this._resetTextElm();

        }



        var left = this.textElm.getX() - this.step;

        this.textElm.setX(left);

    },



    _resetTextElm : function() {

        this.textElm.setX(this.textElm.parent().getRight());

        this.textElm.update(this._getNextText());

    },



    _getNextText : function() {

        this._incrementTextIndex();

        return this._getCurrentText();

    },



    _getCurrentText : function() {

        return this.text[this.currentTextIndex];

    },



    _incrementTextIndex : function() {

        if (this.currentTextIndex >= this.text.length - 1) {

            this.currentTextIndex = 0;

        } else {

            this.currentTextIndex++;

        }

    },



    _getTextElm : function() {

        return Ext.get(this.textElmId);

    }



});



Ext.onReady(function() {

	var marquee = new Ext.ux.Marquee({

		step: 2,

		interval: 30,

		containerCls: 'marquee-container',

		textCls: 'marquee-text',

		text: Ext.bhlcommondata.month_games

	});

	//Ext.bhlcommondata.getsched();

	//marquee.init();

});