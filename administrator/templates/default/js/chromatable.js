/**
 * @version		$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Template
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
  
/**
 * MooTools port of chromatable.js. Make a "sticky" header at the top of the table, 
 * so it stays put while the table scrolls. Enhanced to support table footers as well.
 *
 * Inspiration: chromatable.js by Zachary Siswick
 *   
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Template
 */

var ChromaTable = new Class({

	Implements: [Options, Events],

	options: {
		width: '100%',
		height: '300px',
		scrolling: 'yes'
	},

	initialize: function(table, options){

		this.setOptions(options);
		this.table = table;

		var $uniqueID = this.table.getProperty('id') + 'wrapper', outer = new Element('div', {'class': 'scrolling_outer'}), inner = new Element('div', {id: $uniqueID, 'class': 'scrolling_inner'});

		//Add dimentsions from user or default parameters to the DOM elements
		this.table.setStyles({'width': this.options.width}).addClass("_scrolling");

		this.table.getParent().adopt(
			outer.adopt(
				inner.adopt(this.table)
			)
		);

		//@TODO position relative makes the table overlap the toolbar
		outer.setStyle('position', 'relative');
		inner.setStyles({
			paddingRight:	'17px',
			overflowX:		'hidden',
			overflowY:		'auto',
			height:			this.options.height,
			width:			this.options.width
		});
				
		inner.getElements('tr').each(function(tr){
			var checkbox = tr.getElement('input[type=checkbox]');
			if(!checkbox) return;
			checkbox.addEvent('change', function(tr){
				this.getProperty('checked') ? tr.addClass('selected') : tr.removeClass('selected');
			}.pass(tr, checkbox));
			tr.addEvents({
				dblclick: function(event){
					window.location.href = this.getElement('a').get('href');
				},
				contextmenu: function(event){
					var modal = this.getElement('a.modal');
					if(modal) {
						event.preventDefault();	
						modal.fireEvent('click');
					}
				}
			});
		});
		
        this.thead = inner.getElement('thead');
        this.tfoot = inner.getElement('tfoot');
        
        var styles = {
        		position: 'absolute',
        		//border: 'none 0px transparent'
        	},
        	elements = new Elements,
        	tfoot,
        	thead;
        
        if(this.thead) {
			var thead = this.table.clone()
										.setStyles(styles)
										.empty()
										.addClass('_thead')
										.injectBefore(inner)
										.adopt(
											this.thead.setStyle('position', 'absolute')
										);
			elements.include(this.thead.clone());
		}
		if(this.tfoot) {	
			var tfoot = this.table.clone()
										.setStyle('position', 'absolute')
										.empty()
										.addClass('_tfoot')
										.injectAfter(inner)
										.setStyle(
											'bottom',
											this.tfoot.getSize().y
										).adopt(
											this.tfoot.setStyle('position', 'absolute')
										);
			elements.include(this.tfoot.clone());
		}

		if(elements.length) {
			var styles = {
				position: 'static',
				opacity:  0
			};
			this.table.adopt(elements.setStyles(styles));

			$$(thead, tfoot).setStyle('height', '');
		}
		
		// if the width is auto, we need to remove padding-right on scrolling container	
		
		if (this.options.width == "100%" || this.options.width == "auto") {
			
			inner.setStyle('padding-right','0px');
		}
		
	
		if (this.options.scrolling == "no") {
									
			inner.before('<a href="#" class="expander" style="width:100%;">Expand table</a>');
			
			inner.setStyle('padding-right','0px');
			
			$(".expander").each(

				
				function(int){
					
					this.table.attr("ID", int);
					
					$( this ).bind ("click",function(){
																					 
							$("#"+$uniqueID).css({'height':'auto'});
							
							$("#"+$uniqueID+" ._thead").remove();
							
							this.table.remove();
			
						});
					});


			//this is dependant on the jQuery resizable UI plugin
			$("#"+$uniqueID).resizable({ handles: 's' }).css("overflow-y", "hidden");

		}
		
	//check to see if the width is set to auto, if not, we don't need to call the resizer function
	if (this.options.width == "100%" || "auto") window.addEvent('resize', this.resizer.bind(this, [thead, tfoot]));
	
	//Fire resize twice to make the thead width right
	window.fireEvent('resize').fireEvent('resize');
	},
	
	resizer: function(thead, tfoot) {

		//Fix for chrome, and in some cases webkit
		if(thead.length > 1) {
			tfoot = thead[1];
			thead = thead[0];
		}
		var height = window.getHeight(), top = this.table.getParent().getTop();//, debug = $('debug');
		
		//this.table.getParent().setStyle('height', height-top);
		var parent = this.table.getParent().getParent().getParent().getParent(), 
			height = parent.getSize().y, 
			offset = this.table.getParent().getTop() - parent.getTop();
		this.table.getParent().setStyle('height', height-offset);
//		console.log(window.getWidth(), this.table.getCoordinates().right, window.getWidth() - this.table.getCoordinates().right, thead.getCoordinates().right, window.getWidth() - thead.getCoordinates().right);
		
//		$$(thead, tfoot).setStyle('right', window.getWidth() - this.table.getCoordinates().right);
		
		if(!this.table.getElement('tr')) return;
		
		this.table.getElement('tr').getChildren().each(function(td, i){
			if(!thead.getElement('thead') || !thead.getElement('thead').getElement('tr')) return;
			var th = thead.getElement('thead').getElement('tr').getChildren()[i];
			$$(th, td).setStyle('width', '');
			var size = {th: th.getSize().x - th.getStyle('padding-left').toInt() - th.getStyle('padding-right').toInt(), td: td.getSize().x - td.getStyle('padding-left').toInt() - td.getStyle('padding-right').toInt()};
			size.th > size.td ? td.setStyle('width', size.th) : th.setStyle('width', size.td);
		});
		
		if(tfoot) {
			this.table.getElement('tfoot').getElements('td').each(function(td, i){
				tfoot.getElement('tfoot').getElements('td')[i].setStyle('width', td.getSize().x - td.getStyle('padding-left').toInt() - td.getStyle('padding-right').toInt());
			});
		}
	},

});

Element.implement({

	chromatable: function(options){
		new ChromaTable(this, options);
		return this;
	}

});