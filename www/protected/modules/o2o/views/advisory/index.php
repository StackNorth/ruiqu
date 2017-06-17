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
                                            <input type="hidden" name="id" id="id"/>
                                            <span id="id_str"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>咨询类型: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <!--                                            <input type="text" name="mobile" id="edit_denomination" value='' />-->
                                            <span id="type"></span>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>联系方式: </span>
                                        </div>
                                        <div class="box_flex f_content">
<!--                                            <input type="text" name="mobile" id="edit_denomination" value='' />-->
                                            <span id="mobile"></span>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>区域: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="area"></span>
<!--                                            <textarea name="area" id="area" style="width: 250px;min-height: 100px"></textarea>-->
                                        </div>
                                    </div>
                                </li>


                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>房型: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="homeType"></span>
<!--                                            <input id="edit_order" name="homeType" />-->
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>房源数: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="num"> </span>
                                            <!--<input id="edit_order" name="num" />-->
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>服务: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="tech_content"> </span><!--<id="edit_order" name="tech_content" />-->
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
    var module_router = site_root + '/index.php?r=o2o/Advisory';
    var status_data = <?php echo json_encode($status_option); ?>;
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


        jq_dg_content.datagrid({
            url: module_router + '/list',
            title: '咨询列表',
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
                {field:'user_name', title:'姓名', width:25,sortable:false},
                {field:'mobile', title:'联系方式', width:50, sortable: true},
                {field:'status', title:'状态', width:50, sortable: true,
                    formatter: function(value, row){
                        return get_filed_text(value, status_data);
                    }
                },
                {field:'time', title:'咨询时间', width:70,sortable:true,formatter: function(value, row){
                    var now=new Date(value*1000);

                    var   month=now.getMonth()+1;
                    var   date=now.getDate();
                    var   hour = now.getHours();
                    return   month+"月"+date+"日"+hour+":00";
                }
                },
            ]],

            onSelect: function(index, row){
                var data = $.extend({}, row);
                $('#id_str').html(data.id);
                $('#id').html(data.id);
                $('#user_name').html(data.user_name);
                $('#mobile').html(data.mobile);
                $('#area').html(data.area);
                $('#homeType').html(data.homeType);
                $('#num').html(data.num);
                $('#type').html(data.type);
                $('#user_name').html(data.user_name);
                $('#tech_content').html(data.tech_content);
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


    });

    function save_content(){
        if ($('#recharge_id').val() == ""){
            return false;
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



</script>