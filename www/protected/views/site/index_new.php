<?php
/**
 * summary: 新版首页
 * author: justin
 * date: 2014.03.13
 */
?>
<style>
.index_top {
	height: 42px;
}
.index_logo {
	height: 32px;
	margin: 3px 20px;
	display: inline-block;
}
.index_welcome {
	display: inline-block;
	margin-left: 10px;
	font-size: 15px;
	vertical-align: top;
	line-height: 40px;
	color: #0188C3;
}
#site_info {
    font-style: italic;
}
.user_name {
	margin-left: 5px;
	font-size: 13px;
	color: green;
}
#menu_tool {
	padding: 4px 5px;
	height: 28px;
}
#logout {
	vertical-align: middle;
	margin-left: 5px;
}
.tabs li a.tabs-inner {
	padding-right: 16px;
}
.tabs-p-tool {
	right: 22px;
}
</style>
<div region="north" class="index_top" data-options="border: false">
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" class="index_logo" />
	<div class="index_welcome">
		<span class="user_name"><?=$user_name?></span>
	</div>
    <div class="index_welcome">
        <a style="display:inline-block;text-decoration:none;" href="#" onclick="logout();return false;"  id="logout">退出</a>
    </div>
</div>
<div region="west" style="width:230px;overflow:hidden;" title="管理菜单">
	<div id="menu-layout" class="easyui-layout" fit="true">
		<div region="north" id="menu_tool" data-options="border: false" style="border-bottom-width: 1px;">
			<span>主题: </span>
    		<input id="theme" />
			<a href="#" onclick="$('#menu_tree').tree('expandAll');return false;" iconCls="icon-add" class="easyui-linkbutton" plain="true" style="margin-left: 10px;"></a>
			<a href="#" onclick="$('#menu_tree').tree('collapseAll');return false;" iconCls="icon-remove" class="easyui-linkbutton" plain="true" style="margin-left: 10px;"></a>
		</div>
    	<div region="center" data-options="border: false">
    		<ul id="menu_tree"></ul>
		</div>
	</div>
</div>
<div region="center" style="padding-left:8px;overflow:hidden;" data-options="border: false">
	<div id="content_tab" fit="true" tools="#tab-tools">
	</div>
	<div id="tab-tools" style="border-right-width: 0px;">
		<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-cancel" onclick="closeAllTabs();return false;" title="关闭所有窗口"></a>
	</div>
</div>
<script type="text/javascript">
var jq_menu_tree = $('#menu_tree');
var menu_data = <?php echo json_encode($menu); ?>;
var menu_index = <?php echo json_encode($menu_index); ?>;
var jq_content_tab = $('#content_tab');
var title_timer ;
var request_num = 1;
var site = <?php echo json_encode($site); ?>;

$(function(){

	$('body').layout();
	jq_content_tab.tabs({
		scrollIncrement: 300
	});
	var now_theme = $.cookie('easyui_theme') ? $.cookie('easyui_theme') : 'default';
	$('#theme').combobox({
		width: 83,
		data: [
			{text: '默认', value: 'default'},
			{text: 'gray', value: 'gray'},
			{text: 'metro', value: 'metro'},
			{text: 'black', value: 'black'},
			{text: 'bootstrap', value: 'bootstrap'},
			{text: 'metro-blue', value: 'metro-blue'},
		],
		editable: false,
		value: now_theme,
		onSelect: function(r){
			change_theme(r.value);
		}
	});

    if (site == 'test') {
        $('#theme').combobox('setValue', 'black');
        change_theme('black');
    }

	jq_menu_tree.tree({
		data : menu_data,
		onSelect: function(node){
			if (node.attributes.url != ''){
				loadContent(menu_index[node.id]);
			}
		}
	});
    var show_message_status = <?php echo  $has_fake_user ? "1" : "0"; ?>;
    if(show_message_status){
    	console.log(1);
        getUnReadMsg();
    }
})
//载入iframe
function loadContent(data){
	data.url = get_debug_url(data.url);
	if (jq_content_tab.tabs('exists', data.name)){
		jq_content_tab.tabs('select', data.name);
		var tab = jq_content_tab.tabs('getSelected');
		jq_content_tab.tabs('update', {
			tab: tab,
    		options: {title:data.name, content:createIframeHtml(data), closable:'true', selected:true}
     	});
	} else {
		jq_content_tab.tabs('add', {
			title: data.name,
			id: 'tab-' + data.id,
			content: createIframeHtml(data),
			closable: 'true',
			tools:[{iconCls:'icon-mini-refresh', handler:function(){
				var paramPrefix = '?';
			  	if (data.url.search(/\?/)!==-1){
			  		paramPrefix = '&';
			  	}
			  	$('#content-' + data.id).attr('src', data.url + paramPrefix + '_t=' + new Date().getTime());
			}}]
		});
		var all_tabs = jq_content_tab.tabs('tabs');
		var tab_index = all_tabs.length - 1;
		all_tabs[tab_index].panel('options').tab.unbind().bind('mouseenter',{index:tab_index},function(e){
            //jq_content_tab.tabs('select', e.data.index);
        });
	}
}


//创建新的iframe窗口
function createIframeHtml(data){
	var iframe = '<iframe name="content-' + data.id + '" id="content-' + data.id + '" style="width:100%;height:100%;" frameborder="0" src="' + data.url + '"></iframe>';
	return iframe;
}
//关闭所有窗口
function closeAllTabs(){
	$.messager.confirm('关闭所有面板', '是否关闭所有面板?', function(r){
		if (r){
			var allTabs = jq_content_tab.tabs('tabs');
			var nums = allTabs.length;
			while (allTabs.length > 0){
				var i = allTabs.length - 1;
				if (allTabs[i].panel('options').closable!==false){
					jq_content_tab.tabs('close', allTabs[i].panel('options').title);
				}
			}
		}
	});
}
//退出
function logout(){
	location.href = site_root + '/index.php?r=site/logout';
}
//载入url
function load_url(url){
    console.log(url);
    sessionStorage.removeItem('refresh');
    //window.open(url);return;
    // alert(url);
	var module_info = null;
	for (var id in menu_index){
		var t = $.extend({}, menu_index[id]);
		var re = new RegExp('r=[^&]*', 'gi');
		var match = t['url'].match(re);
		var route_a = route_b = '';
		if (match){
			route_a = match[0];
		}
		match = url.match(re);
		if (match){
			route_b = match[0];
		}
		if (route_a == route_b != ''){	
			t['real_url'] = url;
			module_info = t;	
			break;
		}
	}
	if (!module_info){
		$.messager.show({
            title: '提示',
            msg: '你没有权限访问对应的页面，请联系管理员',
            timeout: 3500,
            showType: 'slide'
        });
	} else {
		module_info['real_url'] = get_debug_url(module_info['real_url']);
        if (jq_content_tab.tabs('exists', module_info.name)){
            jq_content_tab.tabs('select', module_info.name);
			window.frames['content-' + module_info.id].refresh_page(module_info.real_url);
		} else {
			module_info['url'] = module_info['real_url'];
			console.log(module_info)
			loadContent(module_info);
		}
	}
}

//清除缓存
function clear_storage(){
	if (window.localStorage){
		window.localStorage.clear();
		location.reload();
	}
}


function checkEnv(){
    //浏览器支持本地存储，则进行时间检测. 过滤无效请求。
    if(checkLocalStorage()){
        var flag = checkTime();
        if(checkTime()){
            getMsgCount();
        }
    }else{
        getMsgCount();
    }
}

function getMsgCount(){
    $.ajax({
        url:'<?php echo $this->createUrl('message/UnReadCount')?>',
        type:'POST',
        dataType:'json',
        success:function(data){
            if(data.count > 0 || data.post_unread_count > 0){
                request_num = 1;
                setLocalTime(120000);
                tinyconShow(data.count,data.post_unread_count);
            }else{
                request_num++;
                window.clearInterval(title_timer);
                Tinycon.setBubble(0);
                title_timer = null;
                $(document).attr('title','');//壹橙管家 - 管理后台
                if(request_num > 10) request_num = 1;
                setLocalTime(120000*request_num);
            }
        }
    });
}
function tinyconShow(message_count,post_count){
    Tinycon.setOptions({
        width: 7,
        height: 9,
        font: '12px arial',
        colour: 'red',
        background: '#fff',
        fallback: true
    });
    Tinycon.setBubble(message_count + post_count);
    var title = '';
    if(message_count > 0){
    	var title = message_count + ' 条未读私信';
    }
    if(post_count > 0){
    	title = title + ' ' + post_count + '条未读回复';
    }
    title_timer = window.setInterval("flashTitle('"+ title +"')",500);

    $.messager.show({
        title: '提示',
        msg: title,
        timeout: 5000,
        showType: 'slide'
    });
}

function unreadMsgClick(){
    loadContent(menu_index['5466f4d90eb9fb32018b45dc']);
}

function getUnReadMsg(){
    setInterval("checkEnv()",1000 * 10);
}

var i = 0;
var new_title = "";
function flashTitle(title){
    $(document).attr("title",new_title);
    new_title = title.substring(0,i);
    i++;
    if(i > title.length){
        i = 1;
    }
}

function checkLocalStorage(){
    return window.localStorage ? true : false ;
}
/**
 * @param offset 时间间隔，毫秒级
 */
function setLocalTime(offset){
    var storage = window.localStorage;
    var date = new Date();
    storage.setItem('get_msg_time',date.getTime() + offset );
}

function getLocalTime(){
    return window.localStorage.getItem('get_msg_time');
}

function checkTime(){
    var current = new Date();
    var current_time = current.getTime();
    var time = getLocalTime();
    if(current_time < time){
        return false;
    }else{
        return true;
    }
}
</script>

