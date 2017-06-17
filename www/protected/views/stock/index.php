<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
</style>
<div id="main">
<div region="west" border="false" id="west_panel">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <p>
                <input class="material_selector" id="material" name="material" placeholder="物资名" style="width: 100px;"/>
                <input class="user_selector" id="user" placeholder="目标用户" value="<?php echo $fromStockView ? $objectName : ''; ?>" style="width: 100px;" />
                <!-- <input class="station_selector" id="station" placeholder="服务点" value="<?php echo $fromStockView ? $stationName : ''; ?>" style="width: 100px;" /> -->
                <input type="text" name="station" id="station" style="width: 100px;">
                <span class="tb_label">操作: </span>
                <input id="filter_operate" />
            </p>
            <div class="right">
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </div>
            <p>
                <span class="tb_label">开始时间</span>
                <input type="text" id="date_start" />
                <span class="tb_label">结束时间</span>
                <input type="text" id="date_end" />
            </p>
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
            <input type="hidden" name="id" id="sid" value='' />
            <span id="id_str"></span>
        </div>
    </div>
</li>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>物资: </span>
        </div>
        <div class="box_flex f_content">
            <input type="text" name="mname" style="width: 250px;" disabled="true"/>
        </div>
    </div>
</li>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>用户: </span>
        </div>
        <div class="box_flex f_content">
            <input type="text" name="username" style="width: 250px;" disabled="true"/>
        </div>
    </div>
</li>
<li class="f_item">
    <div class="box">
        <div class="f_label">
            <span>时间: </span>
        </div>
        <div class="box_flex f_content">
            <input type="text" name="time" style="width: 250px;" disabled="true" />
        </div>
    </div>
</li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>操作: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" name="operate_str" style="width: 250px;" disabled="true"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>对象: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" class="user_selector" name="objectName" style="width: 250px;"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>服务点: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" id="setStation" name="station" style="width: 250px;"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>数量: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" id="num_content" name="num" style="width: 250px;" disabled="true"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>前库存: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" id="lastStock_content" name="lastStock" style="width: 250px;" disabled="true"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>新库存:</span>
            </div>
            <div class="box_flex f_content">
                <input type="text" id="newStock_content" name="newStock" style="width: 250px;" disabled="true" />
            </div>
        </div>
    </li>
    <!-- <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>其他操作:</span>
            </div>
            <div class="box_flex f_content">
                <a href="#" onclick="showChangeStock();return false;">修改出入库数量及库存</a>
            </div>
        </div>
    </li> -->
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>备注:</span>
            </div>
            <div class="box_flex f_content">
                <textarea name="remarks" style="width: 250px;height: 120px;"></textarea>
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

    </div>
</div>

</div>
</div>
</div>
</div>
<!-- 引入自动填充选择插件 -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/selector.js"></script>
<!-- 用户选择插件引入结束 -->
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_filter_operate = $('#filter_operate');

var operate_data = <?php echo json_encode($operate); ?>;
var station_data = <?php echo json_encode($station); ?>;

var jq_setType = $('.editType');
var jq_setEnable =$("#setEnable");
var module_router = site_root + '/index.php?r=stock';
var jq_action_info = $('#action_info');
var jq_station = $('#station');
var jq_setStation = $('#setStation');

var jq_date_start = $('#date_start');
var jq_date_end = $('#date_end');

// var showChangeNum = 0;

$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_filter_operate.combobox({
        width: 100,
        data: operate_data,
        editable: false,
        onSelect: function(){
            search_content();
        }
    });

    jq_station.combobox({
        width: 100,
        data: station_data,
        editable: false,
        onSelect : function () {
            search_content();
        }
    });

    jq_date_start.datebox({});

    jq_date_end.datebox({});

    jq_setStation.combobox({
        width    : 250,
        editable : false,
        data     : (function () {
            var station_data_temp = new Array();
            $.extend(station_data_temp, station_data);
            station_data_temp.shift();
            station_data_temp.pop();

            return station_data_temp;
        })()
    });

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '库存操作记录',
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
        sortName: 'time',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[[]],
        columns:[[
            {field:'id', title:'id', hidden:true},
            {field:'mname', title:'名称',width:30},
            {field:'username', title:'用户', width:30},
            {field:'operate_str', title:'操作', width:20},
            {field:'objectName', title:'对象', width:30},
            {field:'stationName', title:'服务点', width:30},
            {field:'num', title:'数量', width:20, sortable: true},
            {field:'tot_price', title:'总价', width:30, sortable: true},
            {field:'lastStock', title:'前库存', width:20},
            {field:'newStock', title:'现库存', width:20},
            {field:'time', title:'操作时间', width:30, sortable: true},
        ]],

        queryParams: {
            date_start : jq_date_start.datebox('getValue'),
            date_end : jq_date_end.datebox('getValue'),
            s_user : $('#user').val(),
            station : $('#station').combobox('getValue')
        },

        onSelect: function(index, row){
            var data = $.extend({}, row);
            jq_content_form.form('load', data);

            // showChangeNum = 0;
            // $('#num_content').attr('disabled', 'true');

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
            $('#id_str').html('');
            jq_action_info.html('');

            jq_dg_content.datagrid('clearSelections');
        }
    });

    // 修改库存操作记录
    jq_content_form.form({
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

});

function save_content(){
    if ($('#sid').val() == ""){
        alert('ID is empty');
        return false;
    }

    $.messager.progress();
    jq_content_form.submit();
}

function search_content () {
    var filter_operate = jq_filter_operate.combobox('getValue');
    var s_mname = $('#material').val();
    var s_user = $('#user').val();
    var station = jq_station.combobox('getValue');
    var date_start = jq_date_start.datebox('getValue');
    var date_end = jq_date_end.datebox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            s_mname: s_mname,
            s_user: s_user,
            station: station,
            operate: filter_operate,
            date_start: date_start,
            date_end: date_end
        }
    });
}

// function showChangeStock () {
//     $('#num_content').removeAttr('disabled');
//     showChangeNum = 1;
// }
</script>