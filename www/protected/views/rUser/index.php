<style>
    #more_module {
        width: 300px;
    }
    .module_link {
        float: left;
        width: 140px;
        padding: 3px 5px;
    }
    .module_total {
        color: green;
    }
    .easyui-textbox {

    }
</style>
<div id="main">
    <div region="west" id="west_panel" border="false">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="ss" />
            </div>
            <div style="margin: 3px 2px;padding:5px;border: 1px solid #95B8E7;display:none;">
               <!-- <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">新增用户</a>-->
            </div>
        </div>
    </div>
<!---->
<div id="acc_container" class="accordion" region="center">
    <div region="center" title="用户信息" data-options="iconCls:'icon-save',selected:true">
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
                                        <input type="hidden" name="id" id="user_id" value='' />
                                        <span id="id_str"></span>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="f_item" id="content_area">
                                <div class="box">
                                    <div class="f_label">
                                        <span>注册时间: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span id="register_time_str"></span>
                                    </div>
                                </div>
                            </li>

                            
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>城市信息: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="city_info" id="city_info_add"  placeholder="如：湖北省,武汉市"  style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>

                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>头像: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <div id="avatar_info"></div>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>openid: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span id="openid"></span>
                                    </div>
                                </div>
                            </li>

                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>经纬度: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" id="view_latitude" style="width: 80px;" readonly/>
                                        <input type="text" id="view_longitude" style="width: 80px;" readonly/><a href="javascript:void();" id="view_select_position">查看坐标</a>
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
    
    <div region="center" title="用户订单" data-options="iconCls:'icon-save'" style="overflow:auto;padding:10px;">
        <div class="detail_layout">
            <div data-options="region:'center'" class="detail_center">
                <table id="order_dg"></table>
            </div>
        </div>
    </div>

    <div region="center" title="用户可用代金券" data-options="iconCls:'icon-save'" style="overflow:auto;padding:10px;">
        <div class="detail_layout">
            <div data-options="region:'center'" class="detail_center">
                <div class="detail_main"> 
                    <table id="coupon_dg"></table> 
                    <!-- <div style="margin-top: 30px;">
                        <span>用户订单统计</span>
                        <span id="content_order_count"></span>
                    </div> -->
                </div>
            </div>
            <div data-options="region:'south'" class="detail_south">
            </div>
        </div>
    </div>

    <div region="center" title="后台发送优惠券" data-options="iconCls:'icon-save'" style="overflow:auto;padding:10px;">
        <div class="detail_layout">
            <div data-options="region:'center'" class="detail_center">
                <div class="detail_main">
                    <form id="sendCoupon_form" method="post">
                        <ul>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>ID: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span id="coupon_user_str"></span>
                                        <input type="hidden" name="user_id" id="coupon_user_id" />
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>优惠券ID: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="coupon_id" id="coupon_id" style="width:250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>开始时间: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="start_time" id="coupon_start_time" class="easyui-datebox" style="width:250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>结束时间: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="end_time" id="coupon_end_time" class="easyui-datebox" style="width:250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>发送短信: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="need_sms" id="need_sms" type="checkbox" value="1" onclick="needMessage();"/>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="f_item" id="coupon_copy_item" >
                                <div class="box">
                                    <div class="f_label">
                                        <span>默认文案: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <textarea name="copy" id="coupon_copy" style="width:250px;height:120px;"></textarea>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div data-options="region:'south'" class="detail_south">
                <div class="detail_toolbar">
                    <a href="javascript:void(0);" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="sendCoupon();return false;">发送</a>
                </div>
            </div>
            <div data-options="region:'south'" class="detail_south">
            </div>
        </div>
    </div>

    <div region="center" title="修改余额" data-options="iconCls:'icon-save'" style="overflow:auto;padding:10px;">
        <div class="detail_layout">
            <div data-options="region:'center'" class="detail_center">
                <div class="detail_main">
                    <form id="sendBalance_form" method="post">
                        <ul>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>ID: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span id="balance_id_str"></span>
                                        <input type="hidden" name="id" id="balance_user_id" />
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>金额: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="amount" id="amount" placeholder="如100或者-100" style="width:250px;"/>
                                    </div>
                                </div>
                            </li>

                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>类型: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="radio" name="type" value="admin_recharge">后台充值
                                        <input type="radio" name="type" value="order">下订单
                                        <input type="radio" name="type" value="send">赠送
                                        <input type="radio" name="type" value="other">其他
                                    </div>
                                </div>
                            </li>

                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>备注: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <textarea name="memo" id="memo" style="width:250px;height:120px;"></textarea>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div data-options="region:'south'" class="detail_south">
                <div class="detail_toolbar">
                    <a href="javascript:void(0);" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="saveBalance();return false;">保存</a>
                </div>
            </div>
            <div data-options="region:'south'" class="detail_south">
            </div>
        </div>
    </div>

    <div region="center" title="余额记录" data-options="iconCls:'icon-save'" style="overflow:auto;padding:10px;">
        <div class="detail_layout">
            <div data-options="region:'center'" class="detail_center">
                <table id="balance_dg"></table>
            </div>
        </div>
    </div>
</div>

</div>
<!--新增用户结束 -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qiniu_upload_single.js?v=20200907"></script>
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_action_info = $('#action_info');
var jq_add_dialog = $('#add_dialog');
var jq_add_form = $('#add_form');
var jq_balance_form = $('#balance_form');
var jq_avatar = $('#avatar_info');
var jq_ss = $('#ss');
var jq_acc = $('#acc_container');
var module_router = site_root + '/index.php?r=rUser';
var type = <?php echo json_encode($type); ?>;
var jq_sendCoupon_form = $('#sendCoupon_form');
var jq_sendBalance_form = $('#sendBalance_form');

var price = '';
var endtime = '';

var jq_order_dg = $('#order_dg');
var jq_balance_dg = $('#balance_dg');

console.log(window.location.href);
$(function(){

    $('#view_select_position').click(function(){
        $.fn.position_selector('init',{
            width:$(window).width()-300,//弹框显示宽度
            height:$(window).height()-100,//弹框显示高度
            zoom:18,  //缩放级别
            locat:'上海',//默认城市
            can_edit:true,
            lat:$('#view_latitude').val(),
            lng:$('#view_longitude').val(),
            func_callback:function(){return false;},//选择成功之后的回调函数
            element_id:'map_container'//弹窗ID
        });return false;
    });
    var p_width = parseInt(w_width / 2);
    if (p_width < 550){
        p_width = 550;
    }


    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_acc.accordion({
        height: w_height - 18,
        onSelect:function(title){
//            alert("fdafdsa");
        }
    });
    jq_ss.searchbox({
        width: 120,
        searcher:function(value){
            search_content();
        },
        prompt: '请输入关键字'
    });

    jq_add_dialog.dialog({
        title: '创建用户',
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
            $('#avatar').parent().children('img').remove();
            jq_add_form.form('clear');
            $.fn.qiniu_upload_single('init',{
                bucket:'avatars',
                success_callback:function(result){
                    var img_url = result.url;
                    $('#avatar').parent().children('img').remove();
                    $('#avatar').val(img_url).after('<img src="'+img_url+'" style="max-width: 200px;" />');
                },
                fail_callback:function(){
                    $.messager.alert('提示', '上传失败，请稍后再试', 'warning');
                    return false;
                }
            });
        }
    });
    jq_add_form.form({
        url: module_router + '/add',
        onSubmit: function(param){
            if(checkPass($('#add_password').val())<3){
                $.messager.show({
                    title: '提示',
                    msg: '密码太简单了，必须是大写字母、小写字母、数字、特殊字符中任意三个组合，且长度大于8',
                    timeout: 3500,
                    showType: 'slide'
                });
                $.messager.progress('close');
                return false ;
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


    jq_dg_content.datagrid({
        url: site_root + '/index.php?r=rUser/list',
        title: '用户列表',
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
        pageList: [20],
        pageSize: 20,
        nowrap: false,
        idField: 'id',
        sortName: 'register_time',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'user_name', title:'用户名', width:45,
                formatter:function(value,row){
                    if(row.is_fake_user){
                        value = '<span style="color:red;">'+value+'</span>';
                    }

                    if (row.certify_status) {
                        value = '<span class="l-btn-icon icon-v" style="position:relative;"></span>'+value;
                    }
                    return value.replace(/[\u202e,\u202d]/," ");
                }
            },
            {field:'balance', title:'余额', width:45,sortable:true},
            {field:'order_count', title:'总单数', width:45,sortable:true},
            {field:'city_info', title:'城市', width:50, sortable: false,
                formatter: function(value, row){
                    return formatCity(value);
                }
            },
            
            {field:'register_time', title:'注册时间', width:60, sortable: true,
                formatter: function(value, row){
                    return row.register_time_str;
                }

            }
        ]],
        onSelect: function(index, row){

            endtime = '';
            price = '';

            var data = $.extend({}, row);
            jq_content_form.form('load', data);
            console.log(data);
            $('#id_str').html(data.id);
            $('#balance_id_str').html(data.id);
            $('#register_time_str').html(format_time_stamp(data.register_time,true));


            $('#openid').html(data.openid);

            $('#channel').html(data.channel);

            $('#register_time').html(format_time_stamp(data.register_time,true));

            $('#view_latitude').val(data.latitude);
            $('#view_longitude').val(data.longitude);


            jq_sendCoupon_form.form('clear');
            jq_sendBalance_form.form('clear');
            $('#balance_user_id').val(data.id);
            $('#coupon_user_id').val(data.id);
            $('#coupon_user_str').html(data.id);
            $('#coupon_copy_item').hide();

            $('#city_info_add').val(formatCity(data.city_info));
            if(row.is_fake_user){
                $('#city_info_add').removeAttr("readonly");
            }else{
                $('#city_info_add').attr("readonly","readonly");
            }



            $('#coupon_dg').datagrid({   
                url: site_root + '/index.php?r=rUser/getCoupons&user_id=' + data.id,  
                columns:[[     
                    {field:'name',title:'优惠券名称',width:150},   
                    {field:'end_time_str',title:'过期时间',width:150},   
                    {field:'value',title:'面额',width:100},   
                    {field:'min_price',title:'最低消费',width:100},   
                    {field:'type_str',title:'服务项目',width:120},
                    {field:'unuseable_reason',title:'优惠券状态',width:130},
                ]]   
            });

            jq_balance_dg.datagrid({
                url: site_root + '/index.php?r=rUser/balanceLog',
                fitColumns: true,
                pagination: true,
                pageList: [20, 30, 50],
                pageSize: 20,
                singleSelect: true,
                queryParams: {
                    id: data.id
                },
                columns: [[
                    {field: 'time_str', title: '时间', width: 60},
                    {field: 'type_str', title: '类型', width: 50,},
                    {field: 'action_user', title: '操作者', width: 100,},
                    {field:'amount', title:'数量', width:40},
                    {field:'memo', title:'说明', width:200}
                ]]
            });

            jq_order_dg.datagrid({
                url: site_root + '/index.php?r=rOrder/list',
                fitColumns: true,
                pagination: true,
                pageList: [20, 30, 50],
                pageSize: 20,
                singleSelect: true,
                queryParams: {
                    search: data.id
                },
                columns: [[
                    {field: 'products_str', title: '服务', width: 40,
                        formatter: function(value, row) {
                            return value;
                        }
                    },
                    {field: 'order_time_str', title: '下单时间', width: 100,
                        formatter: function(value, row) {
                            return '<a href="javascript:;" onclick="parent.load_url(\'<?=Yii::app()->request->baseUrl;?>/index.php?r=rOrder&id='+row.id+'\');">'+value+'</a>';
                        }
                    },
                    {field: 'booking_time', title: '预约时间', width: 100,
                        formatter: function(value, row) {
                            var now   = new Date(value * 1000);
                            var month = now.getMonth() + 1;
                            var date  = now.getDate();
                            var hour  = now.getHours();
                            return month+'月'+date+'日 '+hour + ':00';
                        }
                    },

                    {field:'af_sum_price', title:'总额', width:40},
                    {field:'sum_price', title:'折后', width:40},
                    {field: 'status_str', title: '订单状态', width: 40},
                    {field:'counts', title:'数量', width:20,sortable:false},
                    {field:'score', title:'评价',width:20,
                        formatter:function(value, row) {
                            if (value == 100) {
                                return '无';
                            } else {
                                return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=comment&id='+row.commentId+'\');">'+ value +'</a>';
                            }
                        }
                    }
                ]]
            });

            jq_avatar.empty();
            if(data.avatar != '') {
                jq_avatar.append('<img src="' + data.avatar + '" width="64" height="64" />');
            } else {
                jq_avatar.html('无头像');
            }
            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }


        },
        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');
            jq_content_form.form('clear');
            jq_sendCoupon_form.form('clear');
            jq_sendBalance_form.form('clear');
            $('#register_time_str').html('');

            $('#coupon_copy_item').hide();

            $('#id_str').html('');
            $('#visit_str').html('');

            $('#channel').html('');

            $('#register_time').html('');

            $('#view_latitude').val('');
            $('#view_longitude').val('');

            $('#balance_user_id').val('');
            $('#balance_id_str').html('');
            $('#coupon_user_id').val('');
            $('#coupon_user_str').html('');
            $('#coupon_id').val('');
            $('#coupon_name').val('');

            jq_avatar.empty();
            jq_action_info.html('');
            $('#city_info_add').val('');
        }
    });
    jq_content_form.form({
        url: site_root + '/index.php?r=rUser/update',
        onSubmit: function(param){
            if ($('#user_id').val() <= 0){
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

//修改余额
    jq_sendBalance_form.form({
        url: site_root + '/index.php?r=rUser/changeBalance',
        onSubmit: function(param){
            if (!$('#balance_user_id').val()){
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
            console.log(res);
            if (res.success){
                $.messager.show({
                    title: '提示',
                    msg: '保存成功',
                    timeout: 3500,
                    showType: 'slide'
                });
                $('#user_balance').val('');
                $('#user_reason').val('');
                jq_dg_content.datagrid('clearSelections');
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

    // 发送优惠券
    jq_sendCoupon_form.form({
        url: site_root + '/index.php?r=rUser/sendCoupon',
        onSubmit: function(param) {
            if (!$('#coupon_id').val()) {
                $.messager.progress('close');
                $.messager.alert('错误', '请选择优惠券');
                return false;
            }
            if (!$('#coupon_user_id').val()) {
                $.messager.progress('close');
                $.messager.alert('错误', '请选择用户');
                return false;
            }
            var isValid = $(this).form('validate');
            if (!isValid) {
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
                    msg: '保存成功',
                    timeout: 3500,
                    showType: 'slide'
                });
                jq_dg_content.datagrid('clearSelections');
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

    // 选择优惠券
    // $('#select_coupon').coolautosuggest({
    //     url: 'index.php?r=coupon/selectCoupon&coupon=',
    //     showDescription: true,
    //     onSelected: function(result) {
    //         $('#coupon_id').val(result.cid);
    //     }
    // });

    $('#coupon_end_time').datebox({
        onSelect: function(date) {
            if ($('#coupon_copy').val() == '' && $('#coupon_id').val() != '') {
                getCoupon();
            }
            endtime = $(this).datebox('getValue') + '到期，请尽快使用噢！';
            if (price != '') {
                var copy = price + endtime;
                $('#coupon_copy').val(copy);
            }
        }
    });

    $('#coupon_id').keyup(function(event) {
        val = $(this).val();
        if (val.length == 24) {
            getCoupon();
        }
    });

})

function search_content(){
    var search = jq_ss.searchbox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            search: search,
        }
    });
}
function save_content(){
    var a_id = $('#user_id').val();
    if (!a_id){
        return false;
    }
    $.messager.progress();
    jq_content_form.submit();
}

//修改余额
function saveBalance(){
    var a_id = $('#balance_user_id').val();
    if (!a_id){
        $.messager.alert('提示', "请先选择一个用户");
        return false;
    }


    $.messager.progress();
    jq_sendBalance_form.submit();
}


function add_content(){
    jq_add_dialog.dialog('open');
}
function formatCity(value){
    var _city = "";
    if(value.province){
        _city += value.province;
    }
    if(value.city){
        if(value.province){
            _city += ','+value.city;
        }else{
            _city += value.city;
        }
    }
    if(value.area){
        _city += ','+value.area;
    }
    return _city;
}

//密码复杂度验证
//1、长度大于8
//2、密码必须是字母大写，字母大、小写，数字，特殊字符中任意三个组合。
function checkPass(pass){
    if(pass.length < 8){
        return 0;
    }
    var ls = 0;

    if(pass.match(/([a-z])+/)){
        ls++;
    }
    if(pass.match(/([0-9])+/)){
        ls++;
    }
    if(pass.match(/([A-Z])+/)){
        ls++;
    }
    if(pass.match(/[^a-zA-Z0-9]+/)){
        ls++;
    }
    return ls;
}


function sendCoupon() {
    $.messager.progress();
    jq_sendCoupon_form.submit();
}

function getCoupon() {
    $.post(
        '/index.php?r=coupon/getCouponInfo',
        {
            coupon_id : $('#coupon_id').val()
        },
        function (data) {
            couponInfo = $.parseJSON(data);
            console.log(couponInfo);

            if (!couponInfo.success) {
                $.messager.alert('错误', couponInfo.message);
                return false;
            }

            price = '恭喜你获得一张价值'+couponInfo.content['value']+'元的家政上门服务优惠券。';
            var copy = endtime == '' ? price : price + endtime;
            $('#coupon_copy').val(copy);
        }
    );
}

function needMessage() {
    if ($('#need_sms').is(':checked') || $('#need_zpush').is(':checked')) {
        if ($('#coupon_copy').val() == '' && $('#coupon_id').val() != '') {
            getCoupon();
        }
        $('#coupon_copy_item').show();
    } else {
        $('#coupon_copy_item').hide();
    }
}

function formatCity(value){
    var _city = "";
    if(value.province){
        _city += value.province;
    }
    if(value.city){
        _city += ','+value.city;
    }
    if(value.area){
        _city += ','+value.area;
    }
    return _city;
}

</script>