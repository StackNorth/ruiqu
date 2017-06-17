<div id="main">
    <div region="west" border="false" style="width: 550px;">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="search" />
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
            </div>
            <div style="margin: 3px 2px;padding:5px;border: 1px solid #95B8E7;">
                <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">新增</a>
            </div>
        </div>
    </div>
    <div id="acc_container" class="accordion" region="center" >
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
                                            <input type="hidden" name="id" id="coupon_id" value='' />
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
                                            <span>别名: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input type="text" name="alias_name" style="width: 250px;"/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>面额: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input type="text" name="value" style="width: 250px;"/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>最低消费(不限制可以设置0): </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input type="text" name="min_price" style="width: 250px;"/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>备注: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <textarea  name="memo" rows="6" style="width: 250px;"></textarea>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>服务项目:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="editType" name="type" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>工作日限制:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="workday_limit" value="0" type="radio"/>无限制
                                            <input name="workday_limit" value="1" type="radio"/>限工作日
                                            <input name="workday_limit" value="2" type="radio"/>限周末
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>时间限制:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="time_limit_start" name="time_limit_start" style="width: 50px;"/>
                                            -
                                            <input id="time_limit_end" name="time_limit_end" style="width: 50px;"/>
                                            <br>
                                            <span style="color: green;font-size: 10px;">例如输入9、14，则限制为9点至下午2点</span>
                                            <br>
                                            <span style="color: green;font-size: 10px;">留空不作限制</span>
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
        <div region="center" title="查询兑换码状态">
            <div class="detail_layout">
                <div data-options="region:'center'" class="detail_center">
                    <div class="detail_main">
                        <form id="code_form" method="post">
                            <ul>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>兑换码: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="exchange_code" id="exchange_code" class="easyui-textbox" required="true" style="width: 300px">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
                <div data-options="region:'south'" class="detail_south">
                    <div class="detail_toolbar">
                        <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="send_code();return false;">发送</a>
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
                                <span>别名: </span>
                            </div>
                            <div class="box_flex f_content">
                                <input type="text" name="alias_name" style="width: 250px;"/>
                            </div>
                        </div>
                    </li>
                    <li class="f_item">
                        <div class="box">
                            <div class="f_label">
                                <span>面额: </span>
                            </div>
                            <div class="box_flex f_content">
                                <input type="text" name="value" style="width: 250px;"/>
                            </div>
                        </div>
                    </li>
                    <li class="f_item">
                        <div class="box">
                            <div class="f_label">
                                <span>最低消费: </span>
                            </div>
                            <div class="box_flex f_content">
                                <input type="text" name="min_price" style="width: 250px;"/>
                            </div>
                        </div>
                    </li>
                    <li class="f_item">
                        <div class="box">
                            <div class="f_label">
                                <span>服务项目:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input id="addType" name="type" />
                            </div>
                        </div>
                    </li>
                    <li class="f_item">
                        <div class="box">
                            <div class="f_label">
                                <span>工作日限制:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input name="workday_limit" value="0" type="radio" id="workday_limit_none_add"/>无限制
                                <input name="workday_limit" value="1" type="radio"/>限工作日
                                <input name="workday_limit" value="2" type="radio"/>限周末
                            </div>
                        </div>
                    </li>
                    <li class="f_item">
                        <div class="box">
                            <div class="f_label">
                                <span>时间限制:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input name="time_limit_start" id="time_limit_start_add" style="width: 50px;"/>
                                -
                                <input name="time_limit_end" id="time_limit_end_add" style="width: 50px;"/>
                                <br>
                                <span style="color: green;">例如输入9、14，则限制为9点至下午2点</span>
                                <br>
                                <span style="color: green;">留空不作限制</span>
                            </div>
                        </div>
                    </li>
                    <li class="f_item">
                        <div class="box">
                            <div class="f_label">
                                <span>备注: </span>
                            </div>
                            <div class="box_flex f_content">
                                <textarea  name="memo" rows="6" style="width: 250px;"></textarea>
                            </div>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_add_form = $('#add_form');
var jq_content_id = $('#coupon_id');
var jq_code_form = $('#code_form');
var jq_acc = $('#acc_container');
var jq_filter_status = $('#filter_status');
var jq_setStatus = $('#setStatus');
var jq_add_dialog = $('#add_dialog');

var jq_action_info = $('#action_info');
var status_data = <?php echo json_encode($status); ?>;

var type_data = <?php echo json_encode($type); ?>;

var jq_search = $('#search');

var module_router = site_root + '/index.php?r=coupon';
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

    $('#editType').combobox({
        editable: false,
        data: type_data
    });

    jq_filter_status.combobox({
        width: 70,
        editable: false,
        data: status_data,
        onSelect: function(){
            search_content();
        }
    });

    jq_filter_status.combobox('setValue', 1);

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '列表',
        width: 530,
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
        queryParams: $.extend(get_param_obj(),{status : 1}),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'name', title:'名字', width:180},
            {field:'value', title:'面额', width:50},
            {field:'min_price', title:'最低消费', width:50},
            {field:'type_str', title:'服务项目', width:150},
            {field:'status', title:'状态', width:60, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            }
        ]],
        onSelect: function(index, row){
            var data = $.extend({}, row);
            jq_content_form.form('load', data);

            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }
            $('#coupon_id').html(data.id);
            $('#id_str').html(data.id);
        },
        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');
            jq_content_form.form('clear');
            $('#editType').combobox('setValue', 100);
            jq_setStatus.combobox('setValue', 100);
            $('#coupon_id').html('');
            jq_action_info.html('');
        }
    });

    //查询兑换码状态
    jq_code_form.form({
        url: site_root + '/index.php?r=coupon/couponCodeStates',
        onSubmit: function(param){
            if (!$('#exchange_code').val()){
                return false;
            }
            return true;
        },
        success: function(res){
            $.messager.progress('close');
            var res = JSON.parse(res);
            if (res.success){
                $.messager.show({
                    title: '提示',
                    msg: res.message,
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

//发送私信
function send_code(){
    var code = $('#exchange_code').val();
    if (!code){
        alert("请先填写兑换码");
        return false;
    }
    $.messager.progress();
    jq_code_form.submit();
}

function save_content(){
    var a_id = jq_content_id.val();
    // 限制时间检查
    var start = $('#time_limit_start').val();
    var end = $('#time_limit_end').val();
    if (!checkTimelimit(start, end)) {
        return false;
    }

    if (!a_id){
        return false;
    }
    if (jq_setStatus.combobox('getValue') != 1){
        $.messager.confirm('注意', '确认删除该条吗？', function(r){
            if (r){
                $.messager.progress();
                jq_content_form.submit();
            }
        });
    } else {
        $.messager.progress();
        jq_content_form.submit();
    }
}

function add_content(){
    $('#addType').combobox({
        editable: false,
        data: type_data
    });
    jq_add_dialog.dialog('open');
}

function checkTimelimit(start, end) {
    var arr = ['9', '10', '11', '12', '13', '14', '15', '16', '17', '18'];

    if (start != '' && $.inArray(start, arr) == -1) {
        $.messager.alert('提示', '请检查开始时间是否为9-18的数字');
        return false;
    }

    if (start != '' && $.inArray(end, arr) == -1) {
        $.messager.alert('提示', '请检查结束时间是否为9-18的数字');
        return false;
    }

    if (start != '' && end != '' && parseInt(start) >= parseInt(end)) {
        $.messager.alert('提示', '开始时间必须小于结束时间');
        return false;
    }

    return true;
}
</script>