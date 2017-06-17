<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
</style>
<div id="main">
<div region="west" border="false" id="west_panel">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <input id="ss" placeholder="搜索物资" class="material_selector" style="width: 120px;"/>
            <span class="tb_label">库存状态: </span>
            <input id="filter_status" />
            <span class="tb_label">启用状态: </span>
            <input id="filter_enable" />
            <a href="#" class='easyui-linkbutton' iconCls="icon-add" plain="true" onclick="add_content();return false;">新增</a>
            <div class="right">
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </div>
        </div>
    </div>
</div>
<div region="center" border="false">
<div class="easyui-layout detail_layout">
<div data-options="region:'center'" class="detail_center">
<div class="detail_main">
<!-- start content_form -->
<form id="content_form" method="post">
<ul>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>ID: </span>
        </div>
        <div class="box_flex f_content" onclick="getId();">
            <input type="hidden" name="id" id="material_id" value='' />
            <span id="id_str"></span>
        </div>
    </div>
</li>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>名字: </span>
        </div>
        <div class="box_flex f_content">
            <input type="text" name="name" style="width: 250px;"/>
        </div>
    </div>
</li>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>单价: </span>
        </div>
        <div class="box_flex f_content">
            <input type="text" name="price" style="width: 250px;"/>
        </div>
    </div>
</li>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>当前库存: </span>
        </div>
        <div class="box_flex f_content">
            <input type="text" name="stock" class="easyui-numberspinner" data-options="editable:true" style="width: 250px;" />
            <!-- <input type="text" name="stock" style="width: 250px;" disabled="true" /> -->
        </div>
    </div>
</li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>库存警戒线: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" name="stockWarnLine" class="easyui-numberspinner" data-options="editable:true" style="width: 250px;"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>库存状态: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" name="status_str" style="width: 250px;" disabled="true"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>单位:</span>
            </div>
            <div class="box_flex f_content">
                <input class="editType" name="unit"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>是否启用:</span>
            </div>
            <div class="box_flex f_content">
                <input id="setEnable" name="enable" />
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>备注:</span>
            </div>
            <div class="box_flex f_content">
                <textarea name="material_remarks" style="width: 250px; height: 120px;"></textarea>
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
<!-- end content_form -->
</div>

<div data-options="region:'south'" class="detail_south">
    <div class="detail_toolbar">
        <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="save_content();return false;">保存</a>
        <a href="#" class='easyui-linkbutton' iconCls="icon-add" onclick="set_stock();return false;">入库</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" onclick="receive();return false;">出库</a>
    </div>
</div>

</div>
</div>
</div>
</div>
<div style="display: none;">
    <div id="add_dialog" style="padding: 15px 0;">
        <form id="add_form" method="post">
            <ul>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>名字: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="name" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>单位: </span>
                    </div>
                    <div class="box_flex f_content" id="unit_type_add">
                        <input class="editType" name="unit" />
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>单价: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="price" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>初始库存: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="stock" class="easyui-numberspinner" data-options="editable:true" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>库存警戒线: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="stockWarnLine" class="easyui-numberspinner" data-options="editable:true" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>备注: </span>
                    </div>
                    <div class="box_flex f_content">
                        <textarea name="remarks" style="width: 250px; height: 80px;"></textarea>
                    </div>
                </div>
            </li>
            </ul>
        </form>
    </div>
</div>
<!-- 入库表单 -->
<div style="display: none;">
    <div id="stock_dialog" style="padding: 15px 0;">
        <form id="stock_form" method="post">
            <ul>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>ID: </span>
                        </div>
                        <div class="box_flex f_content">
                            <span type="text" class="m_id   "></span>
                            <input type="hidden" name="mid" class="mid  " />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>名字: </span>
                        </div>
                        <div class="box_flex f_content">
                            <span ></span>
                            <input type="text" name="name" id="mname" style="width: 250px;" disabled="true"/>
                        </div>
                    </div>
                </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>单位: </span>
                    </div>
                    <div class="box_flex f_content" id="unit_type_add">
                        <input class="editType" name="unit" disabled="true"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>单价: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="price" style="width: 250px;" disabled="true"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>初始库存: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="stock" style="width: 250px;" disabled="true"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>数量: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="num" class="easyui-numberspinner" data-options="editable:true" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>成本: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="tot_price" style="width: 250px;" placeholder="留空则系统自动计算" />
                    </div>
                </div>
            </li>
            <div id="receive_content"></div>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>备注: </span>
                    </div>
                    <div class="box_flex f_content">
                        <textarea name="remarks" style="width: 250px; height: 80px;"></textarea>
                    </div>
                </div>
            </li>
            </ul>
        </form>
    </div>
</div>
<!-- 出库表单 -->
<div style="display: none;">
    <div id="receive_dialog" style="padding: 15px 0;">
        <form id="receive_form" method="post">
            <ul>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>ID: </span>
                        </div>
                        <div class="box_flex f_content">
                            <span type="text" class="m_id"></span>
                            <input type="hidden" name="mid" class="mid" />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>名字: </span>
                        </div>
                        <div class="box_flex f_content">
                            <span ></span>
                            <input type="text" name="name" id="mname" style="width: 250px;" disabled="true"/>
                        </div>
                    </div>
                </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>单位: </span>
                    </div>
                    <div class="box_flex f_content" id="unit_type_add">
                        <input class="editType" name="unit" disabled="true"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>单价: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="price" style="width: 250px;" disabled="true"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>初始库存: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="stock" style="width: 250px;" disabled="true"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>数量: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="num" class="easyui-numberspinner" data-options="editable:true" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>选择对象: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="object" class="user_selector" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>选择服务点: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="station" id="station" style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <div id="receive_content"></div>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>备注: </span>
                    </div>
                    <div class="box_flex f_content">
                        <textarea name="remarks" style="width: 250px; height: 80px;"></textarea>
                    </div>
                </div>
            </li>
            </ul>
            <input type="hidden" name="operate" value="0" />
        </form>
    </div>
</div>
<!-- 引入用户选择插件 -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/selector.js"></script>
<!-- 用户选择插件引入结束 -->
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_add_form = $('#add_form');
var jq_filter_status = $('#filter_status');
var jq_filter_enable = $('#filter_enable');
var jq_add_dialog = $('#add_dialog');
var jq_stock_dialog = $('#stock_dialog');
var jq_stock_form = $('#stock_form');
var jq_ss = $('#ss');
var jq_receive_form = $('#receive_form');
var jq_receive_dialog = $('#receive_dialog')

var status_data = <?php echo json_encode($status); ?>;
var type_data = <?php echo json_encode($type); ?>;
var enable_data = <?php echo json_encode($enable); ?>;
var station_data = <?php echo json_encode($station); ?>;

var jq_setType = $('.editType');
var jq_setEnable =$("#setEnable");
var jq_setStation = $('#station');
var module_router = site_root + '/index.php?r=material';
var jq_action_info = $('#action_info');

$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_setType.combobox({
        editable: false,
        data: type_data
    });

    jq_setEnable.combobox({
        editable: false,
        data: enable_data
    });

    jq_setStation.combobox({
        editable: false,
        data: station_data
    });

    // jq_ss.searchbox({
    //     width: 150,
    //     searcher:function(value){
    //         search_content();
    //     },
    //     prompt: '请输入关键字'
    // });

    jq_filter_status.combobox({
        width: 100,
        data: status_data,
        editable: false,
        onSelect: function(){
            search_content();
        }
    });

    jq_filter_enable.combobox({
        width: 100,
        data: enable_data,
        editable: false,
        onSelect: function(){
            search_content();
        }
    });

    // 设置默认值
    jq_filter_enable.combobox('setValue', 1);

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
            jq_add_form.form('load', {});
        }
    });

    jq_stock_dialog.dialog({
        title: '入库操作',
        width: 500,
        height: 500,
        closed: true,
        modal: true,
        buttons:[{
            text: '确认',
            iconCls: 'icon-ok',
            handler: function(){
                $.messager.progress();
                jq_stock_form.submit();
            }
        },{
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function(){
                jq_stock_dialog.dialog('close');
            }
        }],
        onOpen:function(){
            // jq_stock_form.form('clear');
            jq_stock_form.form('load', {});
        }
    });

    jq_receive_dialog.dialog({
        title: '出库操作',
        width: 500,
        height: 500,
        closed: true,
        modal: true,
        buttons:[{
            text: '确认',
            iconCls: 'icon-ok',
            handler: function(){
                $.messager.progress();
                jq_receive_form.submit();
            }
        },{
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function(){
                jq_receive_dialog.dialog('close');
            }
        }],
        onOpen:function(){
            jq_receive_form.form('load', {});
        }
    });

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '物资列表',
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
        sortName: 'status',
        sortOrder: 'asc',
        queryParams: get_param_obj(),
        frozenColumns:[],
        columns:[[
            {field:'id', title:'id', hidden:true},
            {field:'name', title:'物资名称', width:35},
            {field:'unit_str', title:'单位', width:20},
            {field:'price', title:'单价（元）', width:20, sortable: true},
            {field:'stock', title:'库存', width:20, sortable: true},
            {field:'stockWarnLine', title:'警戒线', width:20},
            {field:'addTime', title:'添加时间', width:40, hidden:true},
            {field:'status_str', title:'库存状态', width:20},
            {field:'status', title:'status', hidden:true},
            {field:'enable_str', title:'启用', width:20},
            {field:'enable', title:'enable', hidden: true},
            {field:'material_remarks', title:'备注', hidden: true}
        ]],

        queryParams: {enable: 1},

        rowStyler: function (index, row) {
            if (row.status == 0) {
                return 'background-color: red';
            } else if (row.status == 1) {
                return 'background-color: orange';
            }
        },

        onSelect: function(index, row){

            var data = $.extend({}, row);
            jq_content_form.form('load', data);
            jq_stock_form.form('load', data);
            jq_receive_form.form('load', data);

            $('#admins_edit_info').html('');

            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }

            $("#on_loading").show();
            $('#id_str').html(data.id);
        },

        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');

            jq_content_form.form('clear');
            jq_add_form.form('clear');
            jq_receive_form.form('clear');
            jq_stock_form.form('clear');

            $('#id_str').html('');
            jq_action_info.html('');

            jq_dg_content.datagrid('clearSelections');
        }
    });

    // 修改物资
    jq_content_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){
            if ($('#material_id').val() == ""){
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
                jq_dg_content.datagrid('reload');
            }
            if(res.success){
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

    // 添加物资
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
            } else {
                $.messager.show({
                    title: '提示',
                    msg: res.message,
                    timeout: 3500,
                    showType: 'slide'
                });
            }

        }
    });

    // 出入库操作
    jq_stock_form.form({
        url: module_router + '/stock',
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
                    msg: '出入库操作成功',
                    timeout: 3500,
                    showType: 'slide'
                });
                jq_stock_dialog.dialog('close');
                jq_stock_form.form('clear');
                jq_dg_content.datagrid('reload');
            } else {
                $.messager.show({
                    title: '提示',
                    msg: res.message,
                    timeout: 3500,
                    showType: 'slide'
                });
            }

        }
    });

    // 出库操作
    jq_receive_form.form({
        url: module_router + '/stock',
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
                    msg: '出入库操作成功',
                    timeout: 3500,
                    showType: 'slide'
                });
                jq_receive_dialog.dialog('close');
                jq_receive_form.form('clear');
                jq_dg_content.datagrid('reload');
            } else {
                $.messager.show({
                    title: '提示',
                    msg: res.message,
                    timeout: 3500,
                    showType: 'slide'
                });
            }

        }
    });

});

function save_content(){
    if ($('#material_id').val() == ""){
        return false;
    }

    $.messager.progress();
    jq_content_form.submit();
}

function add_content(){
    jq_add_dialog.dialog('open');
}

function set_stock () {
    var mid = $('#id_str').html();

    $('.mid').val(mid);
    $('.m_id').html(mid);

    jq_stock_dialog.dialog('open');
}

function receive () {
    var mid = $('#id_str').html();

    $('.mid').val(mid);
    $('.m_id').html(mid);

    jq_receive_dialog.dialog('open');
}

function search_content () {
    var filter_status = jq_filter_status.combobox('getValue');
    var filter_enable = jq_filter_enable.combobox('getValue');
    var search = jq_ss.val();

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {search: search, status: filter_status, enable: filter_enable}
    });
}

function calPrice () {
    var num = $('#num').val();
    var price = $('price').val();

    //alert(num);
    //alert(price);
    // alert(num * price);
}

</script>