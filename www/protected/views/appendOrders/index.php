<div id="main">
    <div region="west" border="false" style="width: 550px;">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="search" />
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
            </div>
        </div>
    </div>
    <div region="center" title="信息">
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
                                        <input type="hidden" name="id" id="append_id" value='' />
                                        <span id="id_str"></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div data-options="region:'south'" class="detail_south">
                <div class="detail_toolbar">

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
var jq_content_id = $('#append_id');

var jq_filter_status = $('#filter_status');
var jq_setStatus = $('#setStatus');

var jq_action_info = $('#action_info');
var status_data = <?php echo json_encode($status); ?>;
var jq_search = $('#search');

var module_router = site_root + '/index.php?r=appendOrders';
$(function(){

    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();
    
    // 搜索功能
    // 2015-11-19
    jq_search.searchbox({
        width:150,
        searcher:function(value) {
            search_content();
        },
        prompt: '订单id'
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
        sortName: 'append_time',
        sortOrder: 'desc',
        queryParams: $.extend(get_param_obj(),{status : 1}),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'order', title:'原订单', width:50,sortable: false,
                formatter:function(value,row){
                    return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rOrder&id='+value+'\');">'+ '查看订单' +'</a>';
                }
            },
            {field:'products_str', title:'追加项目', width:160},
            {field:'append_time', title:'追加时间', width:100, sortable: true,
                formatter: function(value, row) {
                    return row.append_time_str;
                }
            },
            {field:'status_str', title:'状态', width:50}
        ]],
        onSelect: function(index, row){
            var data = $.extend({}, row);
            jq_content_form.form('load', data);
            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }
            $('#order_id').html(data.order);
            $('#order').html(data.order);
        },
        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');
            jq_content_form.form('clear');
            jq_setStatus.combobox('setValue', 100);
            $('#append_id').html('');
            jq_action_info.html('');
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

</script>