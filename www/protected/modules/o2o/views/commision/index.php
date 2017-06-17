<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
</style>
<div id="main">
<div region="west" border="false" id="west_panel">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <input id="search" style="120px" />
            <span class="tb_label">类型: </span>
            <input id="filter_type" />
            <span class="tb_label">预约时间: </span>
            <input id="filter_start" style="100px"/>
            <span class="tb_label">至</span>
            <input id="filter_end" style="100px"/>
            <div class="right">
                <a href="#" class='easyui-linkbutton' iconCls="icon-add" plain="true" onclick="add();return false;">新增</a>
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="searchContent();return false;">查询</a>
            </div>
        </div>
    </div>
</div>
<div region="center" border="false">
<div class="easyui-layout detail_layout">
<div data-options="region:'center'" class="detail_center">
<div class="detail_main">
    <form id="content_form">
        <ul id="content_ul"></ul>
    </form>
</div>
</div>
</div>
</div>
</div>
<div style="display:none;">
<div id="add_dialog" style="padding: 15px 0;">
    <form id="add_form" method="post">
        <ul>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>时间:</span>
                    </div>
                    <div class="box_flex f_content">
                        <input id="add_datetime" name="datetime" />
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>选择保洁师:</span>
                    </div>
                    <div class="box_flex f_content">
                        <input id="add_username" name="user_name" />
                        <input id="add_userid" name="user" type="hidden" />
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>订单ID:</span>
                    </div>
                    <div class="box_flex f_content">
                        <input id="add_order" name="order" />
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>提成数(元):</span>
                    </div>
                    <div class="box_flex f_content">
                        <input id="add_commision" name="commision" />
                    </div>
                </div>
            </li>
        </ul>
    </form>
</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript">
var jq_dg_content   = $('#dg_content');
var w_width         = $(window).width();
var w_height        = $(window).height();
var jq_content_form = $('#content_form');
var jq_filter_type  = $('#filter_type');
var jq_search       = $('#search');
var jq_filter_start = $('#filter_start');
var jq_filter_end   = $('#filter_end');
var jq_add_dialog   = $('#add_dialog');
var jq_add_form     = $('#add_form');
var jq_add_datetime = $('#add_datetime');
var jq_add_username = $('#add_username');
var type_option = <?php echo json_encode($type_option); ?>

var module_router = site_root + '/index.php?r=o2o/commision';

$(function() {
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width: p_width});
    $('#main').css({width: w_width-25, height: w_height-18}).layout();

    jq_filter_type.combobox({
        editable: false,
        width: 100,
        data: type_option,
        onSelect: function() {
            searchContent();
        }
    });

    jq_search.searchbox({
        width: 150,
        prompt: '订单ID、姓名、拼音',
        searcher: function() {
            searchContent();
        }
    });

    jq_filter_start.datebox({
        width: 100,
        editable: false
    });

    jq_filter_end.datebox({
        width: 100,
        editable: false
    });

    jq_add_datetime.datetimebox({
        showSeconds: false,
        width: 150,
        editable: true
    });

    // 内容表格
    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '保洁师提成',
        width: d_width,
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
        sortName: 'booking_time',
        sortOrder: 'desc',
        queryParams: $.extend(get_param_obj(), {}),
        frozenColumns:[],
        columns: [
            [
                {field: 'id', titie:'id', hidden: true},
                {field: 'time_str', title: '完成时间', width: 30},
                {field: 'booking_time', title: '预约时间', width: 30, sortable: true,
                    formatter: function(value, row, index) {
                        return row['booking_time_str'];
                    }
                },
                {field: 'user_str', title: '保洁师姓名', width: 25},
                {field: 'commision', title: '提成数量(元)', width: 25},
                {field: 'type_str', title: '类型', width: 25},
                {field: 'order', title: '操作', width: 25,
                    formatter: function(value, row, index) {
                        if (row.type == 0) {
                            var url = '\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rOrder&id='+value+'\'';
                            return '<a href="javascript:;" onclick="parent.load_url('+url+')">'+'查看订单'+'</a>';
                        } else if (row.type == 1) {
                            var url = '\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=appendOrders&id='+value+'\'';
                            return '<a href="javascript:;" onclick="parent.load_url('+url+')">'+'查看订单'+'</a>';
                        } else {
                            return '';
                        }
                    }
                }
            ]
        ],
        onSelect: function(index, row) {

        },
        onLoadSuccess: function() {
            $('#content_ul').empty();
            if (jq_search.searchbox('getValue') && jq_filter_start.datebox('getValue') && jq_filter_end.datebox('getValue')) {
                $.post(
                    module_router + '/commisionCountOne',
                    {
                        search: jq_search.searchbox('getValue'),
                        start: jq_filter_start.datebox('getValue'),
                        end: jq_filter_end.datebox('getValue'),
                        type: jq_filter_type.combobox('getValue')
                    },
                    function (res) {
                        var data = $.parseJSON(res);
                        for (key in data) {
                            var _html  = '<li class="f_item"><div class="box"><div class="f_label">';
                                _html += '<span>' + data[key]['type'] + '</span>';
                                _html += '</div><div class="box_flex f_content">' + data[key]['sum'] + '</div>';
                                _html += '</div></li>';
                            $('#content_ul').append(_html);
                        }
                    }
                );
            }
        }
    });

    // 新增提成表单
    jq_add_form.form({
        url: module_router + '/addCommision',
        onSubmit: function(param) {
            check = checkAddForm();
            if (!check) {
                return false;
            } else {
                $.messager.progress();
            }
        },
        success: function(res) {
            $.messager.progress('close');
            var res = JSON.parse(res);

            if (res.success){
                jq_dg_content.datagrid('reload');
            }
            if(res.success){
                $.messager.show({
                    title: '提示',
                    msg: '保存成功',
                    timeout: 3500,
                    showType: 'slide'
                });
                jq_add_dialog.dialog('close');
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

    // 新增提成弹窗
    jq_add_dialog.dialog({
        title: '新增提成',
        width: 400,
        height: 280,
        closed: true,
        modal: true,
        buttons:[{
            text: '确认',
            iconCls: 'icon-ok',
            handler: function() {
                jq_add_form.submit();
            }
        }, {
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function() {
                jq_add_dialog.dialog('close');
            }
        }],
        onOpen: function() {
            jq_add_form.form('clear');
            $('#suggestions_holder').remove();
            jq_add_username.coolautosuggest({
                url: 'index.php?r=material/selectUser&user=',
                showDescription: true,
                onSelected: function(result) {
                    $('#add_userid').val(result.uid);
                }
            });
        }
    });
});

function add() {
    jq_add_dialog.dialog('open');
}

function searchContent() {
    var type_search    = jq_filter_type.combobox('getValue');
    var search_content = jq_search.searchbox('getValue');
    var filter_start   = jq_filter_start.datebox('getValue');
    var filter_end     = jq_filter_end.datebox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            type: type_search,
            search: search_content,
            start: filter_start,
            end: filter_end
        }
    });
}

function checkAddForm() {
    var username = $('#add_username').val();
    if (username.length == 0) {
        $.messager.alert('提示', '请选择保洁师');
        return false;
    }

    // var order = $('#add_order').val();
    // if (order.length == 0) {
    //     $.messager.alert('提示', '请输入普通订单/追加订单号');
    //     return false;
    // }

    var commision = $('#add_commision').val();
    if (commision.length == 0) {
        $.messager.alert('提示', '请输入提成数');
        return false;
    }

    var num_reg = new RegExp(/^[-\d]*\d*[\.]*\d*$/);
    if (!num_reg.test(commision)) {
        $.messager.alert('提示', '提成只能输入整数或带小数点的数字');
        return false;
    }

    return true;
}
</script>