/* Fixed Internet Explorer 10 和 Windows Phone 8 媒体查询功能 */
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
  	var msViewportStyle = document.createElement("style")
  	msViewportStyle.appendChild(
    	document.createTextNode(
      		"@-ms-viewport{width:auto!important}"
    	)
  	)
  	document.getElementsByTagName("head")[0].appendChild(msViewportStyle)
}
//客户端存储
var storage_prefix = ''; //存储key的前缀
var storage = {
    hname:location.hostname?location.hostname:'localStatus',
    isLocalStorage:window.localStorage?true:false,
    dataDom:null,

    initDom:function(){ //初始化userData
        if(!this.dataDom){
            try{
                this.dataDom = document.createElement('input');//这里使用hidden的input元素
                this.dataDom.type = 'hidden';
                this.dataDom.style.display = "none";
                this.dataDom.addBehavior('#default#userData');//这是userData的语法
                document.body.appendChild(this.dataDom);
                var exDate = new Date();
                exDate = exDate.getDate()+30;
                this.dataDom.expires = exDate.toUTCString();//设定过期时间
            }catch(ex){
                return false;
            }
        }
        return true;
    },
    setItem:function(key,value,noPrefix){
		if (noPrefix == null || !noPrefix){
			key = storage_prefix + key;
		}
        if(this.isLocalStorage){
            window.localStorage.setItem(key, JSON.stringify(value));
        }else{
            if(this.initDom()){
                this.dataDom.load(this.hname);
                this.dataDom.setAttribute(key, JSON.stringify(value));
                this.dataDom.save(this.hname)
            }
        }
    },
    getItem:function(key,noPrefix){
    	if (noPrefix == null || !noPrefix){
			key = storage_prefix + key;
		}
        if(this.isLocalStorage){
        	var t = window.localStorage.getItem(key);
        	if (t === null){
        		return t;
        	} else {
        		return JSON.parse(t);
        	}            
        }else{
            if(this.initDom()){
                this.dataDom.load(this.hname);
                var t = this.dataDom.getAttribute(key);
                if (t === null){
                	return t;
                } else {
                	return JSON.parse(t);
                }
            }
        }
    },
    removeItem:function(key,noPrefix){
    	if (noPrefix == null || !noPrefix){
			key = storage_prefix + key;
		}
        if(this.isLocalStorage){
            localStorage.removeItem(key);
        }else{
            if(this.initDom()){
                this.dataDom.load(this.hname);
                this.dataDom.removeAttribute(key);
                this.dataDom.save(this.hname)
            }
        }
    }
};
/**
 * summary: 用户操作确认组件，默认为删除理由
 * author: justin
 * date: 2014.03.07
 */
$.messager.confirm_action = function(options){
	var defaults = {
		title: '确认操作吗？',
    	module: '',
    	value: '',
    	editable: false,
    	push_switch: false,
    	callback: null, 
   	}
   	var option = $.extend({}, defaults, options);
   	var dialog_id = 'easyui_confirm_action'; 
   	var select_id = dialog_id + '_select';
   	if ($('#' + dialog_id).length == 0){
   		var html = '<div style="margin: 30px 50px;"><span>理由: </span><input id="' + select_id + '" /></div>';
   		$('body').append('<div id="' + dialog_id + '">' + html + '</div>');
   	}
   	if (typeof(site_root) == 'undefined'){
		site_root = 'http://admin.yiguanjia.me';
	}
   	var jq_cd = $('#' + dialog_id); 
   	var jq_cds = $('#' + select_id);
   	jq_cds.combobox({
   		width: 220,
   		editable: option.editable,
	   	url: site_root + '/index.php?r=api/getActionReason&module=' + option.module,
	   	value: option.value,
	   	onLoadSuccess: function(){
	   		if ($(this).combobox('getData').length > 0){
	   			$(this).combobox('showPanel');
	   		}
	   	}
   	})
   	jq_cd.dialog({
   		title: option.title,
		width: 400,
	    height: 160,
	    cache: false,
	    modal: true,
   		buttons:[{
			text: '确认',
			iconCls: 'icon-ok',
			handler: function(){
				if (option.callback && typeof(option.callback) == 'function'){
					var t = option.callback(jq_cds.combobox('getText'));
					if (typeof(t) == 'undefined' || t){
						jq_cd.dialog('close');
					}
				}
			}
		},{
			text: '取消',
			iconCls: 'icon-cancel',
			handler: function(){
				jq_cd.dialog('close');
			}
		}]
   	});
}
/**
 * summary: 获取字符串的参数对象
 * author: justin
 * date: 2014.04.24
 */
function get_param_from_str(str){
	var param = {};
	if (str.indexOf('?') != -1){
		var t = str.slice(str.indexOf('?') + 1);
		var t1 = t.split('&');
		for (var i = 0;i < t1.length;i ++){
			var t2 = t1[i].split('=');
			param[t2[0]] = decodeURI(t2[1]);
		}
    }
    return param;
}
/**
 * summary: 获取链接中的参数对象
 * author: justin
 * date: 2014.03.21
 */
function get_param_obj(){
  var refresh = sessionStorage.getItem('refresh');
  if (refresh) {
    var param = {};
  } else {
    var param = get_param_from_str(window.location.href);
  }
  return param;
}
/**
 * summary: 默认刷新页面，由各模块重写实现不跳转刷新结果
 * author: justin
 * date: 2014.04.24
 */
function refresh_page(url){
	if (typeof jq_dg_content != 'undefined'){
		// var old_param = jq_dg_content.datagrid('options').queryParams;
    var old_param = {};
		old_param['search'] = '';
		var new_param = $.extend(true, old_param, get_param_from_str(url));
		jq_dg_content.datagrid({
			queryParams: new_param,
			pageNumber: 1
		});
	} else {
		location.href = url;
	}	
}
/**
 * summary: 根据combobox data获取datagrid filed的显示名称
 * author: justin
 * date: 2014.05.13
 */
function get_filed_text(value, data){
	var val = '';
	for (var i = 0;i < data.length;i ++){
		var t = data[i];	
		if (t['value'] == value){
			var style = '';
			if (!!t.attributes && !!t.attributes.color){
				style = ' style="color:' + t['attributes']['color'] + '"';
				val = '<span' + style + '>' + t['text'] + '</span>';
			} else {
				val = t['text'];
			}
			break;
		}
	}
	return val;
}
/**
 * summary: 获取debug的链接
 * author: justin
 * date: 2014.05.15
 */
function get_debug_url(url){
	var debug_url = url;
	if (!!get_param_obj().debug){
		debug_url = '';
		var t = url.split('?');
		if (t.length == 1){
			debug_url = t[0] + '?debug=1';
		} else {
			debug_url = url + '&debug=1';
		}
	}
	return debug_url;
}

function format_time_stamp(time,full)
{
    var d = new Date(time*1000);
    if(full){
        return(d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds());
    }else{
        return(d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate());
    }
}

$(function(){
	/**
	 * 统一对所有的ajax请求进行debug预处理
	 */
	var ajax_default_data = {};
	if (parent != window){

	}
	if (!!get_param_obj().debug){
		ajax_default_data['debug'] = 1;
	}
	$.ajaxSetup({
		data: ajax_default_data
	});
		
});