<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
</style>

<div id="main">
    <div region="west" border="false" id="west_panel">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="ss" />
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </div>
        </div>
    </div>
    <div region="center" border="false">
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
                                        <input type="hidden" name="id" id="order_id" value='' />
                                        <span id="id_str"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>快递公司: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="express_company" style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>发货单号: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="express_number" style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>地址/联系方式: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span  id="address_contact" style="width: 250px;"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>备注: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="memo" style="width: 250px;"/>
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
                                        <span>取消订单原因: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="reason" style="width: 250px;"/>
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


<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var jq_content_form = $('#content_form');
var jq_filter_status = $('#filter_status');
var module_router = site_root + '/index.php?r=order';
var status_data = <?php echo json_encode($status); ?>;
var jq_action_info = $('#action_info');
var jq_setStatus = $('#setStatus');
var w_width = $(window).width();
var w_height = $(window).height();
var jq_ss = $('#ss');
$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_ss.searchbox({
        width: 150,
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
        title: '订单列表',
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
        sortName: 'order',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'id', title:'id', hidden:true},
            {field:'user', title:'用户', width:30,
                formatter: function(value, row){
                    var username = value.user_name;
                    return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rUser&id='+value.id+'\');">'+ username +'</a>';
                }
            },
            {field:'time_str', title:'订单生成时间', width:40,sortable:false},
            {field:'order_num', title:'订单号', width:40,sortable:false},
            {field:'admin_goods_view', title:'订单商品', width:40,sortable:false},
            {field:'admin_address_view', title:'收货信息', width:60,sortable:false},
            {field:'price', title:'金额', width:20,sortable:false},
            {field:'status', title:'状态', width:20, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            },
            {field:'express_company', title:'快递公司', width:30, sortable: true},
            {field:'express_number', title:'发货单号', width:30, sortable: true},
            {field:'memo', title:'备注', width:30, sortable: true},

        ]],
        onSelect: function(index, row){
            var data = $.extend({}, row);
            jq_content_form.form('load', data);

            $('#admins_edit_info').html('');
            if(data['address']['address']['poi']){
                var poiname = data['address']['address']['poi']['name'];
            }else{
                var poiname = '';
            }
            if (data['address']['position']) {
                var lat = data['address']['position'][1];
                var lng = data['address']['position'][0];
            }else{
                var lat = 120;
                var lng = 31;
            }
            var address_contact = '省:'+data['address']['address']['province']+'<br />'+'市:'+data['address']['address']['city']+'<br />'+'区:'+data['address']['address']['area']+'<br />'+'地址:'+poiname+' '+data['address']['address']['detail']+'<br />'+'姓名:'+data['address']['name']+'<br />'+'手机号:'+data['address']['mobile']+'<br />'+'<a href="javascript:void();" lat='+lat+' lng='+lng+' id="view_position">查看坐标</a>';
                $('#address_contact').html(address_contact);
                $('#view_position').on('click',function(){
                    $.fn.position_selector('init',{
                        width:$(window).width()-300,//弹框显示宽度
                        height:$(window).height()-100,//弹框显示高度
                        zoom:18,  //缩放级别
                        locat:'上海',//默认城市
                        can_edit:true,
                        lat:$(this).attr('lat'),
                        lng:$(this).attr('lng'),
                        func_callback:function(){return false;},//选择成功之后的回调函数
                        element_id:'map_container'//弹窗ID
                    });return false;
                });
                
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
            $('#address_contact').html('');
            jq_content_form.form('clear');
            $('#id_str').html('');

            jq_dg_content.datagrid('clearSelections');
            jq_setStatus.combobox('setValue', 100);
        }
    });
    function search_content(){
        var filter_status = jq_filter_status.combobox('getValue');

        var search = jq_ss.searchbox('getValue');
        jq_dg_content.datagrid({
            pageNum: 1,
            queryParams: {search: search, status : filter_status}
        });
    }
    jq_ss.searchbox({
        width: 150,
        searcher:function(value){
            search_content();
        },
        prompt: '请输入关键字'
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
    if ($('#order_id').val() == ""){
        return false;
    }

    if (jq_setStatus.combobox('getValue') == -2){
        $.messager.confirm('注意', '确认取消该订单吗？', function(r){
            $.messager.progress();
            jq_content_form.submit();
        });
    } else {
        $.messager.progress();
        jq_content_form.submit();
    }
}

</script>