<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
    #view_select_position {
        display:inline-block;
        padding:1px 4px 1px 4px;
        border:1px solid #999999;
        text-decoration:none;
        color:#333333;
    }
</style>

<div id="main">
    <div region="west" border="false" id="west_panel">
        <table id="dg_content"></table>
        <div id="tb_content">

            <div class="tb_line">
                <span class="tb_label">状态: </span>
                <input id="filter_status" />

            </div>

            <div style="margin: 3px 2px;padding:5px;border: 1px solid #95B8E7;">
                <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">新增充值卡</a>
            </div>
        </div>
    </div>
    <div id="acc_container" class="accordion" region="center">
        <div region="center" title="充值卡" data-options="iconCls:'icon-save',selected:true">
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
                                            <input type="hidden" name="id" id="recharge_id" value='' />
                                            <span id="id_str"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>充值面额: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input type="text" name="denomination" id="edit_denomination" value='' />
                                            <span id="id_str"></span>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>选择方式: </span>
                                        </div>

                                        <div class="box_flex f_content">
                                        <input type="radio" name="select_fun" id="edit_cash_back"  value='cash_back' />返现金额
                                            <span id="id_str"></span>
                                        <input type="radio" name="select_fun" id="edit_coupon"  value="coupon" />使用代金券
                                        </div>

                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span></span>
                                        </div>
                                        <div class="box_flex f_content" id="cash">
                                            <input type="text" name="cash_back" id="edit_cash_back" value='' />
                                        </div>
                                        <div class="box_flex f_content" id="coupons" style="display: none;">
                                            <div id="coupons_items"></div>
                                            <textarea id="text_coupons" name="text_coupons" style="width: 250px;min-height: 50px" placeholder="若使用代金券,填写代金券id,多个代金券时用换行分割"></textarea>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>介绍: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <textarea name="desc" id="edit_desc" style="width: 250px;min-height: 100px"></textarea>
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
                                            <span>排序权重: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="edit_order" name="order" />
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
                    <div data-options="region:'south'" class="detail_south">
                        <div class="detail_toolbar">
                            <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="save_content();return false;">保存</a>
                        </div>
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
                            <span>充值面额: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="denomination" name="denomination" style="width: 250px;"  />
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>选择方式: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="radio" name="add_select_fun" id="add_cash_back" checked="true" value="cash_back" />返现金额
                            <span id="id_str"></span>
                            <input type="radio" name="add_select_fun" id="add_coupon"  value="coupon" />使用代金券
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span></span>
                        </div>
                        <div class="box_flex f_content" id="add_cash">
                            <input type="text" name="cash_back" id="cash_back_add" value='' />
                        </div>
                        <div class="box_flex f_content" id="add_coupons" style="display: none;">
                            <div id="coupons_items"></div>
                            <textarea id="add_text_coupons" name="coupons" style="width: 250px;min-height: 50px" placeholder="若使用代金券,填写代金券id,多个代金券时用换行分割"></textarea>
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>状态: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="setStatus_add" name="status" />
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>介 绍: </span>
                        </div>
                        <div class="box_flex f_content">
                            <textarea name="desc" style="width: 250px;min-height: 100px"></textarea>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>排序权重: </span>
                        </div>
                        <div class="box_flex f_content">
                           <input id="add_order" name="order"/>
                        </div>
                    </div>
                </li>


            </ul>
        </form>
    </div>

</div>

<div style="display:none;">
    <div id="refund_tip_dialog" style="padding: 30px 0;">
        <div style="text-align:center;"><span id="refund_tip"></span></div>
    </div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript">
    var jq_dg_content = $('#dg_content');
    var jq_content_form = $('#content_form');
    var jq_filter_status = $('#filter_status');
    var jq_setStatus_add = $('#setStatus_add');
    var module_router = site_root + '/index.php?r=Recharge';
    var status_data = <?php echo json_encode($status); ?>;
    var jq_setStatus = $('#setStatus');
    var w_width = $(window).width();
    var w_height = $(window).height();
    var jq_ss = $('#ss');
    var jq_action_info = $('#action_info');

    var jq_add_dialog = $('#add_dialog');
    var jq_add_form = $('#add_form');


    var jq_set_precedence = $('#set_precedence');


    var jq_acc = $('#acc_container');

    $(function(){

        jq_acc.accordion({
            height: w_height - 18,
            onSelect: function(title) {

            }
        });



        var buttons = $.extend([], $.fn.datebox.defaults.buttons);
        buttons[0].text = '确定';

        jq_setStatus.combobox({
            editable: false,
            data: status_data
        });


        jq_setStatus_add.combobox({
            editable: false,
            data: (function () {
                var status_data_temp = new Array();
                $.extend(status_data_temp, status_data);
                status_data_temp.shift();

                return status_data_temp;
            })()
        });







    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_ss.searchbox({
        width: 130,
        searcher:function(value){
            search_content();
        },
        prompt: '请输入关键字'
    });

    jq_setStatus.combobox({
        editable: false,
        data: status_data
    });

    jq_add_dialog.dialog({
        title: '新建充值卡',
        width: 500,
        height: 500,
        closed: true,
        modal: true,
        buttons:[{
            text: '确认',
            iconCls: 'icon-ok',
            handler: function(){
                // ------ 数据完整性检查 ------
                var check = checkAddForm();
                if (!check) {
                    return false;
                } else {
                    $.messager.progress();
                    jq_add_form.submit();
                }
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
            jq_set_precedence.combobox('setValue', 0);
            $('#extra_items').html('');

            $('#re_address').html('');
        }
    });

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '充值卡列表',
        width: d_width,
        height: w_height - 18,
        fitColumns: true,
        autoRowHeight: true,
        striped: true,
        toolbar: '#tb_content',
        singleSelect: true,
        selectOnCheck: false,
        checkOnSelect: false,
        rowStyler: function(index,row){
            if (row.status==1){
                //return 'color:red;';
            }else if(row.status==-3){
                return 'color:green;';
            }
        },
        pagination: true,
        pageList: [20, 30, 50],
        pageSize: 20,
        nowrap: false,
        idField: 'id',
        sortName: 'order_time',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'id', title:'id', hidden:true},
            {field:'denomination', title:'充值面额', width:25,sortable:false
            },
            {field:'type', title:'类型', width:8, sortable: true,
                formatter: function(value, row){
                   if(row.cash_back == 0){
                       return "代金券";
                   } else {
                       return "返现";
                   }
                }
                },
            {field:'cash_back', title:'返现金额', width:25,sortable:false},
            {field:'status', title:'状态', width:50, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            },
            {field:'order', title:'排序', width:25,sortable:true}
        ]],

        onSelect: function(index, row){
            var data = $.extend({}, row);
            $('#id_str').html(data.id);
            $('#recharge_id').val(data.id);


            if (data.cash_back <= 0){
                $('#edit_cash_back').prop("checked",false);
                $('#edit_coupon').prop("checked",true);
                $('#cash').hide();
                $('#coupons').show();
            } else {
                $('#edit_coupon').prop("checked",false);
                $('#edit_cash_back').prop("checked",true);
                $('#coupons').hide();
                $('#cash').show();
            }
            coupon = '';
            for(var i in data.coupons){
                coupon += '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=coupon&id='+data['coupons'][i]['id']+'\')">'+data['coupons'][i]['id']+'</a><br/>';
            }

            $('#coupons_items').html(coupon);
            jq_content_form.form('load', data);
            $('#admins_edit_info').html('');
            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }

        }

    });

    jq_filter_status.combobox({
        width: 100,
        data: status_data,
        editable: false,
        onSelect: function(){
            search_content();
        }
    });




    jq_content_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){
            if ($('#order_id').val() == ""){
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

                $('#technician_id').val(0);
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

    jq_add_form.form({
        url : module_router + '/add',
        onSubmit : function (param) {
            var isValid = $(this).form('validate');
            if (!isValid){
                $.messager.progress('close');
            }
            return isValid;
        },
        success : function (res) {
            $.messager.progress('close');
            var res = JSON.parse(res);

            if (res.success) {
                $.messager.show({
                    title : '提示',
                    msg : '保存成功',
                    timeout : 3500,
                    showType : 'slide'
                });
                jq_add_dialog.dialog('close');
                jq_dg_content.datagrid('reload');
            } else {
                $.messager.show({
                    title : '提示',
                    msg : res.message,
                    timeout : 3500,
                    showType : 'slide'
                });
            }
        }
    });
    $('#edit_coupon').change(function(){

        $('#cash').hide();
        $('#coupons').show();
    });
    $('#edit_cash_back').change(function(){

        $('#cash').show();
        $('#coupons').hide();
    });
    $('#add_coupon').change(function(){

        $('#add_cash').hide();
        $('#add_coupons').show();
    });
    $('#add_cash_back').change(function(){

        $('#add_cash').show();
        $('#add_coupons').hide();
    });

});

function save_content(){
    if ($('#recharge_id').val() == ""){
        return false;
    }

    if (jq_setStatus.combobox('getValue') <0 ){
        $.messager.confirm('注意', '确认删除 该充值券吗？', function(r){
            $.messager.progress();
            jq_content_form.submit();
        });
        return true;
    }



    $.messager.progress();
    jq_content_form.submit();
}
function search_content(){

    var filter_status = jq_filter_status.combobox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            status : filter_status
        }
    });

};

function checkAddForm () {
    // 空数据检查

    if ($('#denomination').val() == '') {
        $.messager.alert('提示', '请输入充值面额', 'warning');
        return false;
    }

    if ($('#add_cash_back').attr("checked") == 'undefined'  || $('#add_coupon').attr("checked") == 'undefined') {
        $.messager.alert('提示', '请选择方式', 'warning');
        return false;
    }



    if ($('#setStatus_add').combobox('getValue') == '') {
        $.messager.alert('提示', '请选择充值卡状态', 'warning');
        return false;
    }

    return true;
}

function add_content(){
    jq_add_dialog.dialog('open');
}

</script>