/**
 *  summary: 多重城市选择
 *  author: justin
 *  date: 2013.12.19
 */
(function($){
  	$.extend($.fn, {
      	multiple_city_selector: function(options, param){
      		if (typeof options == 'string'){
				return $.fn.multiple_city_selector.methods[options](this, param);
			}
           	if(!filter(options)){
              	return this;
            }
         	return this.each(function(){
         		var opts = $.extend({},$.fn.multiple_city_selector.defaults, options);
         		if (opts.show_village){
         			opts.level = 5;
         		}
         		var random_num = Math.round(Math.random()*1000000); //生成随机id
         		opts.random_num = random_num;
         		$(this).wrap('<div id="mcs_' + random_num + '"></div>');	
				$(this).hide();
				var html = '<div id="mcs_con_' + random_num + '" style="padding:5px;border:1px solid #B8ADAD;display: inline-block;">';
				html += '<span id="mcs_list_' + random_num + '">';
				var data = opts.data;
				for (var i = 0;i < data.length;i ++){
					html += '<a href="#" class="easyui-linkbutton remove_cs" id="mcs_value_' + data[i]['value'] + '" plain="true" data-options="iconCls:\'icon-no\'" style="margin: 2px;">' + data[i]['name'] + '</a>';
				}
				html += '</span>';
				html += '<span id="mcs_control_' + random_num + '">';
				html += '<a id="add_city_' + random_num + '" href="#" class="easyui-linkbutton" data-options="iconCls:\'icon-add\'"></a>';
				html += '</span>';
				html += '</div>';
				html += '<div id="select_con_' + random_num + '" style="display: none;margin-top: 10px;">';
				html += '<div id="single_select_' + random_num + '"></div>';
				html += '<div style="margin-top: 5px;padding:5px;">';
				html += '<a id="cancel_city_' + random_num + '" href="#" class="easyui-linkbutton" data-options="iconCls:\'icon-cancel\'"></a></li>';
				html += '<a id="save_city_' + random_num + '" href="#" class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" style="margin-left: 10px;"></a></li>';
				html += '</div>';
				html += '</div>';
				$(this).after(html);
				$.parser.parse('#mcs_' + random_num);
				var jq_single_select = $('#single_select_' + random_num);
				var jq_select_con = $('#select_con_' + random_num);
				jq_single_select.city_selector({
			    	need_last: opts.need_last,
			    	show_village: opts.show_village,
			    	editable: opts.editable,
			    	level: opts.level
				});
				$('#add_city_' + random_num).on('click', function(){
					jq_select_con.show();
				});
				$('#cancel_city_' + random_num).on('click', function(){
					jq_select_con.hide();
					jq_single_select.city_selector('clear');
				});
				var that = this;
				$('#save_city_' + random_num).on('click', function(){
					var cs = jq_single_select.city_selector('getSelection');
					if (opts.need_last){
						var validate = true;
						var message = '';
						switch (opts.level){
							case 5:
								if (cs.village == ''){
									validate = false;
									message = '请选择一个小区';
								}
								break;
							case 4:
								if (cs.circle == ''){
									validate = false;
									message = '请选择一个商圈';
								}
								break;
							case 3:
								if (cs.area == ''){
									validate = false;
									message = '请选择一个区';
								}
								break;
							case 2:
								if (cs.city == ''){
									validate = false;
									message = '请选择一个城市';
								}
								break;
							case 1:
								if (cs.province == '00'){
									validate = false;
									message = '请选择一个省';
								}
								break;
							default:
								break;
						}
						if (!validate){
							$.messager.show({
								title: '提示',
								msg: message,
								timeout: 3500,
								showType: 'slide'
							});
							jq_single_select.city_selector('focus');
							return false;
						}
					}
					if (opts.validate && typeof opts.validate == 'function'){
						if (!opts.validate(cs)){
							jq_single_select.city_selector('focus');
							return false;
						}
					}
					if (cs.choice != ''){
						var data = opts.data;
						data.push({value : cs.choice, name : cs.choice_name});
					}		
					$(that).multiple_city_selector('refresh');
				});
				$.data(this, 'multiple_city_selector', {options: opts});
         	});
       	}
  	})
   	$.fn.multiple_city_selector.methods = {
   		options: function(jq){
   			return $.data(jq[0], 'multiple_city_selector').options;
   		},
	    refresh: function(jq, data){ //返回用户的选择
    		return jq.each(function(){
    			var opts = $.data(this, 'multiple_city_selector').options;
    			if (data == undefined){
    				data = opts.data;
    			} else {
    				opts.data = data;
    			}
    			var random_num = opts.random_num;
    			var html = '';
				for (var i = 0;i < data.length;i ++){
					html += '<a href="#" class="easyui-linkbutton remove_cs" id="mcs_value_' + data[i]['value'] + '" plain="true" data-options="iconCls:\'icon-no\'" style="margin: 2px;">' + data[i]['name'] + '</a>';
				}			
				$('#mcs_list_' + random_num).html(html);
				$.parser.parse('#mcs_list_' + random_num);
				$('#single_select_' + random_num).city_selector('clear');
				$('#select_con_' + random_num).hide();
				$('.remove_cs').on('click', function(){
					var that = this;
					$.messager.confirm('确认', '确认要移除该区域?', function(r){
						if (r){
							var id = $(that).attr('id').replace('mcs_value_', '');
							var data = opts.data;
							for (var i = 0;i < data.length;i ++){
								if (data[i]['value'] == id){
									data.splice(i, 1);
									break;
								}
							}
							$(that).remove();
						}
					});
				})
	    	});
	    },
	    getValues: function(jq){
	    	var opts = $.data(jq[0], 'multiple_city_selector').options;
	    	var data = opts.data;
	    	var values = [];
	    	for (var x in data){
	    		if ($.inArray(data[x]['value'], values) == -1){
	    			values.push(data[x]['value']);
	    		}
	    	}
	    	return values;
	    },
	    destory: function(jq){
	    	return jq.each(function(){
	    		var opts = $.data(this, 'multiple_city_selector').options;
	    		$('#mcs_' + opts.random_num).remove();
	    	});
	    }
   	}
   	
    function filter(options){ 
        return !options || (options && typeof options === 'object') ? true : false;
    }
	
    $.fn.multiple_city_selector.defaults = {
    	data: [],
    	need_last: true, //是否必选到最后一级
    	show_village: false, //是否是选择小区
    	level: 4,
    	editable: true,
    	validate: null
   	}
   	
	if (typeof(site_root) == 'undefined'){
		site_root = 'http://admin.ddxq.mobi';
	}
	
	$.ajax({
		type: 'GET',
		url: site_root + '/js/city_selector.js?v=201406161314',
		async: false,
		dataType: 'script'
	});
})(jQuery);