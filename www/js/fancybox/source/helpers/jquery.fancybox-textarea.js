 /*!
 * Buttons helper for fancyBox
 * version: 1.0.5 (Mon, 15 Oct 2012)
 * @requires fancyBox v2.0 or later
 *
 * Usage:
 *     $(".fancybox").fancybox({
 *         helpers : {
 *             textarea: {
 *                 position : 'bottom',
 *                 collect_input : function(){},
 *                 init_input: function(){}
 *             }
 *         }
 *     });
 *
 */
(function ($) {
	//Shortcut for fancyBox object
	var F = $.fancybox;

	//Add helper object
	F.helpers.textarea = {
		defaults : {
			position   : 'bottom', // 'top' or 'bottom'
			tpl        : '<div id="fancybox-textarea"><div class="textarea-frame"><div id="fancybox-textarea-title">编辑公告内容</div><textarea id="fancybox-textarea-box"></textarea></div></div>',
			init_input: function (){
				return '';
			},		
			collect_input: function (val){
				console.log(val);
			}
		},
		
		area : null,
		
		beforeLoad: function (opts, obj) {
			//Increase top margin to give space for buttons
			obj.margin[ opts.position === 'bottom' ? 2 : 0 ] += 30;
		},
		
		afterShow: function (opts, obj) {
			if (!this.area) {
				this.area = $(opts.tpl).addClass(opts.position).appendTo('body');
				var w_width = $(window).width();
				var ml = parseInt((w_width - 330) / 2);
				$('#fancybox-textarea').css('left', ml + 'px');
				$('#fancybox-textarea-box').val(opts.init_input());
				//引入easyui的draggable
				$('#fancybox-textarea').draggable({
					handle: '#fancybox-textarea-title'
				});
			}		
		},
		
		beforeClose: function (opts, obj) {
			var value = $('#fancybox-textarea-box').val();			
			opts.collect_input(value);
			if (this.area) {
				this.area.remove();
			}
			this.area = null;
		}
	};
	
}(jQuery));