<div id="main">
    <div region="west" border="false" style="width: 600px;">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="search" />
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
            </div>

        </div>
    </div>
    <div id="acc_container" class="accordion" region="center">
        <div region="center" title="信息" data-options="iconCls:'icon-save',selected:true">
            <div class="easyui-layout detail_layout">
                <div data-options="region:'center'" class="detail_center">
                    <div class="detail_main">
                        <form id="content_form" method="post">
                            <ul>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>ID: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input type="hidden" name="id" id="id" value='' />
                                            <span id="id_str"></span>

                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>兑换码:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="code"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>领取的代金券:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <div id="coupons"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>批次号:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <div id="channel"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>描述:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                           <span id="desc"></span>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>状态:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="setStatus" name="status" />
                                        </div>
                                    </div>
                                </li>


                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="action_info" style="color:green;"></span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
                <div data-options="region:'south'" class="detail_south">
                    <div class="detail_toolbar">
                        <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="save_content();return false;">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_add_form = $('#add_form');
var jq_content_id = $('#id');
var jq_code_form = $('#code_form');
var jq_acc = $('#acc_container');
var jq_filter_status = $('#filter_status');
var jq_setStatus = $('#setStatus');
var jq_add_dialog = $('#add_dialog');
var jq_action_info = $('#action_info');
var status_data = <?php echo json_encode($status); ?>;


var jq_search = $('#search');

var module_router = site_root + '/index.php?r=CouponCode';
$(function(){

    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    // 搜索功能
    // 2015-11-19
    jq_search.searchbox({
        width:150,
        searcher:function(value) {
            search_content();
        },
        prompt: '模糊搜索'
    });

    jq_setStatus.combobox({
        editable: false,
        data: status_data,
    });



    jq_filter_status.combobox({
        width: 70,
        editable: false,
        data: status_data,
        onSelect: function(){
            search_content();
        }
    });

    jq_filter_status.combobox('setValue', 100);

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '列表',
        width: 580,
        height: w_height - 18,
        fitColumns: true,
        autoRowHeight: true,
        striped: true,
        toolbar: '#tb_content',
        singleSelect: true,
        selectOnCheck: false,
        checkOnSelect: false,
        pagination: true,
        pageList: [20, 30, 50],
        pageSize: 20,
        nowrap: false,
        idField: 'id',
        sortName: '_id',
        sortOrder: 'desc',
        queryParams: $.extend(get_param_obj(),{status : 100}),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'code', title:'兑换码', width:80},
            {field:'user', title:'使用者', width:70,formatter: function(value, row){
                if (row['user'].user_name) {
                    return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rUser&id=' + row['user'].id + '\');">' + row['user'].user_name + '</a>';
                } else {
                    return "";
                }
            }},

            {field:'channel', title:'批次号', width:200},
            {field:'use_time', title:'使用时间', width:80,formatter: function(value, row){
                if (value) {
                    return new Date(parseInt(value) * 1000).toLocaleString().substr(0, 10);
                } else {
                    return "";
                }
            }},
            {field:'stop_time', title:'结束时间', width:80,formatter: function(value, row){
                if (value) {
                    return new Date(parseInt(value) * 1000).toLocaleString().substr(0, 10);
                } else {
                    return "";
                }
            }},

            {field:'status', title:'状态', width:70, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            }
        ]],
        onSelect: function(index, row){
            var data = $.extend({}, row);
            jq_content_form.form('load', data);
            var output = '';
            for(var i in data.coupons){
                output += '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=coupon&id='+data.coupons[i].id+'\')">'+data.coupons[i].name+'</a><br/>';

            }
            $('#coupons').html(output);
            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }
            $('#code').html(data.code);
            $('#desc').html(data.desc);
            $('#id_str').html(data.id);
            $('#channel').html(data.channel);
        }
    });

    jq_content_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){

            if (jq_content_id.val() <= 0){
                return false;
            }
            var isValid = $(this).form('validate');
            if (!isValid){
                $.messager.progress('close');
            }
            return isValid;
        },
        success: function(res){
            $.messager.progress('close');
            var res = JSON.parse(res);
            if (res.success){
                jq_dg_content.datagrid('clearSelections');
                jq_dg_content.datagrid('reload');
                $.messager.show({
                    title: '提示',
                    msg: '保存成功',
                    timeout: 3500,
                    showType: 'slide'
                });
            }else{
                $.messager.show({
                    title: '提示',
                    msg: res.message,
                    timeout: 3500,
                    showType: 'slide'
                });
            }

        }
    });

    jq_add_dialog.dialog({
        title: '新建',
        width: 500,
        height: 500,
        closed: true,
        modal: true,
        buttons:[{
            text: '确认',
            iconCls: 'icon-ok',
            handler: function(){
                $.messager.progress();
                jq_add_form.submit();
            }
        },{
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function(){
                jq_add_dialog.dialog('close');
            }
        }],
        onOpen:function(){
            jq_add_form.form('clear');
            $('#workday_limit_none_add').prop('checked', true);
        }
    });

    jq_acc.accordion({
        height: w_height - 18,
        onSelect:function(title){
//            alert("fdafdsa");
        }
    });

    jq_add_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){
            var isValid = $(this).form('validate');
            if (!isValid){
                $.messager.progress('close');
            }
            return isValid;
        },
        success: function(res){

            $.messager.progress('close');
            var res = JSON.parse(res);
            if (res.success){
                $.messager.show({
                    title: '提示',
                    msg: '添加成功',
                    timeout: 3500,
                    showType: 'slide'
                });

                jq_add_dialog.dialog('close');
                jq_dg_content.datagrid('reload');
            }else{
                $.messager.show({
                    title: '提示',
                    msg: res.message,
                    timeout: 3500,
                    showType: 'slide'
                });
            }

        }
    });
})
function search_content(){
    var filter_status = jq_filter_status.combobox('getValue');
    var search = jq_search.searchbox('getValue');
    var param = {
        status : filter_status,
        search : search
    };

    jq_dg_content.datagrid({
        queryParams: param,
        pageNum: 1
    });
}
function save_content(){
    var a_id = jq_content_id.val();

    // 限制时间检查
    /*var start = $('#time_limit_start').val();
    var end = $('#time_limit_end').val();
    if (!checkTimelimit(start, end)) {
        return false;
    }
*/
    if (!a_id){
        return false;
    }
    $.messager.progress();
    jq_content_form.submit();

}

</script>