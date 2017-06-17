//自定义validate规则
$.extend($.fn.validatebox.defaults.rules, {
	//最大长度
    maxLength: {
        validator: function(value, param){
            return value.length <= param[0];
        },
        message: '您输入的文字太多了! '
    },
    picUrl: {
        validator: function(value, param){
            console.log(value)
            return /^http:\/\/.*?\/.*?\.(jpg|png|gif|jpeg)/i.test(value.toLowerCase());
        },
        message: '必须是上传的图片地址哦'
    },
    //最小长度
    minLength: {
        validator: function(value, param){
            return value.length >= param[0];
        },
        message: '您输入的文字太少了! '
    },
    mobile: {
    	validator: function(value){
            return /^(13|15|18)\d{9}$/i.test(value);
        },
        message: '手机号码格式不正确! '
    },
    domainSeo: {
        validator: function(value){
            return /^[\w]+\.([\w]+\.)*((me)|(co)|(am)|(ca)|(com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(mobi)|(name)|(sh)|(ac)|(io)|(tw)|(com\.tw)|(hk)|(com\.hk)|(ws)|(travel)|(us)|(tm)|(la)|(me\.uk)|(org\.uk)|(ltd\.uk)|(plc\.uk)|(in)|(eu)|(it)|(jp))$/.test(value);
        },
        message: '请输入正确的域名! '
    },
    htmlColor: {
    	validator: function(value){
            return /^\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(value);
        },
        message: '请输入正确的颜色名.'
    },
    equals: {
        validator: function(value,param){
            return value == $(param[0]).val();
        },
        message: '两次输入的内容不匹配.'
    }
});
$.extend($.fn.datagrid.methods, {
	//datagrid反选
	reverseSelect: function(jq){
		return jq.each(function(){
			var select_rows = $(this).datagrid('getSelections');
			var select_index;
			var now_index;
			var select_index_arr = [];
			for (var X in select_rows){
				select_index = $(this).datagrid('getRowIndex', select_rows[X]);
				select_index_arr.push(select_index);
			}
			var all_rows = $(this).datagrid('getRows');
			for (var X in all_rows){
				now_index = $(this).datagrid('getRowIndex', all_rows[X]);
				if ($.inArray(now_index, select_index_arr) == -1){
					$(this).datagrid('selectRow', now_index);
				} else {
					$(this).datagrid('unselectRow', now_index);
				}
			}
		})
	},
	//保持当前的选择状态
	keepSelect: function(jq){
		return jq.each(function(){
			var self = $(this);
			var select_rows = self.datagrid('getSelections');
			var select_index;
			var now_index;
			var select_index_arr = [];
			for (var X in select_rows){
				select_index = self.datagrid('getRowIndex', select_rows[X]);
				select_index_arr.push(select_index);
			}
			setTimeout(function(){
				self.datagrid('unselectAll');
				for (var Y in select_index_arr){
					self.datagrid('selectRow', select_index_arr[Y]);
				}
			}, 100);
		})
	},
	//增加编辑器
	addEditor : function(jq, param) {
        if (param instanceof Array) {
            $.each(param, function(index, item) {
                var e = $(jq).datagrid('getColumnOption', item.field);
                e.editor = item.editor;
            });
        } else {
            var e = $(jq).datagrid('getColumnOption', param.field);
            e.editor = param.editor;
        }
    },
    //移除编辑器
    removeEditor : function(jq, param) {
    	if (param == null){
			param = $(jq).datagrid('getColumnFields');
    	}
        if (param instanceof Array) {
            $.each(param, function(index, item) {
                var e = $(jq).datagrid('getColumnOption', item);
                e.editor = {};
            });
        } else {
            var e = $(jq).datagrid('getColumnOption', param);
            e.editor = {};
        }
    }
});
$.extend($.fn.datagrid.defaults.editors, {
   	timespinner: {
        init: function(container, options){
            var input = $('<input class="easyui-timespinner">').appendTo(container);
            options.formatter = function(time){
                return new Date(time).format("hh:mm");
            };
            return input.timespinner(options);
        },
        getValue: function(target){
            return $(target).timespinner('getValue');
      	},
        setValue: function(target, value){
            $(target).timespinner('setValue', value);
        },
        resize: function(target, width){
        	$(target).timespinner('resize', width);
        }
    },
    combotree: {
    	init: function(container, options){
            var input = $('<input class="easyui-combotree">').appendTo(container);
            return input.combotree(options);
        },
        getValue: function(target){
        	var values = $(target).combotree('getValues');
        	var real_values = [];     	
        	for (var x = 0,len = values.length;x < len;x ++){
        		if (values[x] && values[x] != ''){
        			real_values.push(values[x]);
        		}
        	}
            return real_values.join(',');
      	},
        setValue: function(target, value){  	
            setTimeout(function(){
            	var values = value.split(',');
            	$(target).combotree('setValues', values);
            	var tree = $(target).combotree('tree');
	            var nodes = tree.tree('getChecked');
	            var parent;
	            for (var x = 0,len = nodes.length;x < len;x ++){
	            	parent = tree.tree('getParent', nodes[x]['target']);
	            	while(parent != null){
	            		tree.tree('expand', parent.target);
	            		parent = tree.tree('getParent', parent['target']);
	            	}      	
	            }
            }, 100);
        },
        resize: function(target, width){
        	$(target).combotree('resize', width);
        }
    },
    combobox: {
    	init: function(container, options){
            var input = $('<input class="easyui-combobox">').appendTo(container);
            return input.combobox(options);
        },
    	getValue : function(jq) {
	        var opts = $(jq).combobox('options');
	        if(opts.multiple){
	            var values = $(jq).combobox('getValues');
	            if(values.length>0){
	                if(values[0]==''||values[0]==' '){
	                    return values.join(',').substring(1);
	                }
	            }
	            return values.join(',');
	        }
	        else
	            return $(jq).combobox("getValue");
	    },
	    setValue : function(jq, value) {
	        var opts = $(jq).combobox('options');
	        if(opts.multiple&&value.indexOf(opts.separator)!=-1){//多选且不只一个值
	            var values = value.split(opts.separator);
	            $(jq).combobox("setValues", values);
	        }
	        else
	            $(jq).combobox("setValue", value);
	    },
        resize: function(target, width){
        	$(target).combobox('resize', width);
        }
    },
    datetimebox: {
    	init: function(container, options){
            var input = $('<input class="easyui-datetimebox">').appendTo(container);
            return input.datetimebox(options);
        },
    	getValue : function(jq) {
	        return $(jq).datetimebox("getValue");            
	    },
	    setValue : function(jq, value) {
	    	$(jq).datetimebox("setValue", value);
	    },
        resize: function(target, width){
        	$(target).datetimebox('resize', width);
        }
    },
    uploadbox: {
    	init: function(container, options){
            var input = $('<input class="easyui-validatebox" readonly=true>').appendTo(container);
            return input.validatebox(options);
        },
        destroy: function(target){
            $(target).remove();
        },
    	getValue : function(target) {
	        return $(target).val();            
	    },
	    setValue : function(target, value) {
	    	$(target).val(value);
	    },
        resize: function(target, width){
        	$(target)._outerWidth(width);
        }
    }
});

/**
 *  将输入框的foucs和blur效果整合
 *  params: f_color: focus的颜色, b_color: blur的颜色, prompt: 提示的文字
 */
(function($){
	$.fn.extend({
		focus_and_blur: function(options){
			var opts = $.extend({}, $.fn.focus_and_blur.defaults, options);
			return this.each(function(){
				$(this).val(options.prompt);
				$(this).focus(function(){
			    	$(this).css('background-color', opts.f_color);
			    	var text = $(this).val();
			    	if (text == options.prompt){
			    		$(this).val('');
			    	}
			  	});
			  	$(this).blur(function(){
			  		$(this).css('background-color', opts.b_color);
			  		var text = $(this).val();
			  		if (text == ''){
			    		$(this).val(opts.prompt);
			  		}
			  	});
			})
		}
	});
	$.fn.focus_and_blur.defaults = {
		f_color: '#FFFFCC',
		b_color: 'white',
		prompt: ''
	};
})(jQuery);

(function($){
	$.extend({	
		cookie : function (key, value, options) {
		    // key and value given, set cookie...
		    if (arguments.length > 1 && (value === null || typeof value !== "object")) {
		        options = jQuery.extend({}, options);
		        if (value === null) {
		            options.expires = -1;
		        }
		        if (typeof options.expires === 'number') {
		            var days = options.expires, t = options.expires = new Date();
		            t.setDate(t.getDate() + days);
		        }
		        return (document.cookie = [
		            encodeURIComponent(key), '=',
		            options.raw ? String(value) : encodeURIComponent(String(value)),
		            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
		            options.path ? '; path=' + options.path : '',
		            options.domain ? '; domain=' + options.domain : '',
		            options.secure ? '; secure' : ''
		        ].join(''));
		    }
		    // key and possibly options given, get cookie...
		    options = value || {};
		    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
		    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
		}
	})
})(jQuery);
