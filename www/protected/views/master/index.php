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
        <div style="margin: 3px 2px;padding:5px;border: 1px solid #95B8E7;">
            <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">添加</a>
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
                        <input type="hidden" name="id" id="master_id" value='' />
                        <span id="id_str"></span>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>类型: </span>
                    </div>
                    <div class="box_flex f_content">
                        <span id="type_str"></span>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>名字: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="name" readonly  style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>介绍: </span>
                    </div>
                    <div class="box_flex f_content">
                        <textarea type="text" name="desc" style="width: 250px;min-height: 40px"></textarea>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>省/城市: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="city_info" id="city_info_edit"  placeholder="如：湖北省"  style="width: 250px;"/>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>地址: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" id="edit_latitude" style="width: 80px;" readonly/>
                        <input type="text" id="edit_longitude" style="width: 80px;" readonly/>
                        <input type="hidden" id="edit_address" name="address"  readonly/>
                        <input type="hidden" id="edit_position" name="position"   readonly/>
                        <a href="javascript:void();" id="edit_select_position">修改地址</a>
                    </div>
                </div>
            </li>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>电话: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="mobile"  style="width: 250px;"/>
                    </div>
                </div>
            </li>

            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>性别: </span>
                    </div>
                    <div class="box_flex f_content">
                        <span id="sex_str"></span>
                    </div>
                </div>
            </li>

            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>服务区域: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="hidden" id="edit_coverage" name="coverage" />
                        <div></div><br />

                        <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="edit_add_coverage();return false;">添加</a>
                    </div>
                </div>
            </li>

            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>图片: </span>
                    </div>
                    <div class="box_flex f_content">

                        <a href="#" iconCls="icon-add" id="qiniu_uploader_edit" class="easyui-linkbutton" plain="true">
                            上传图片
                        </a>
                    </div>
                </div>
            </li>

            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                    </div>
                    <div class="box_flex f_content">
                        <ul id="image_list">
                        </ul>
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
<div style="display: none;">
    <div id="add_dialog" style="padding: 15px 0;">
        <form id="add_form" method="post">
            <ul>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>类型: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input  type="radio" name="type" value="exchange" class="type_add"/>
                            <label for="type_a">兑换</label>
                            <input  type="radio" name="type" value="lottery" class="type_add"/>
                            <label for="real_b">抽奖</label>
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
                            <span>排序权重: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="order" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>介绍: </span>
                        </div>
                        <div class="box_flex f_content">
                            <textarea type="text" name="desc" style="width: 250px;min-height: 40px"></textarea>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>库存数量: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="count" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>市场价: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="market_price" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>爪币: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="score" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>最低参与等级: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="min_level" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>是否是实物: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="radio" name="is_real" value="0" />
                            <label for="real_a">否</label>
                            <input type="radio" name="is_real" value="1" />
                            <label for="real_b">是</label>
                        </div>
                    </div>
                </li>
                <li class="f_item" id="">
                    <div class="box">
                        <div class="f_label">
                            <span>上线日期:</span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="start_time_add" type="text" >
                            <input type="hidden" name="start_time" id="start_time_str_add"  />
                        </div>
                    </div>
                </li>
                <li class="f_item" id="">
                    <div class="box">
                        <div class="f_label">
                            <span>下架日期:</span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="end_time_add" type="text" >
                            <input type="hidden" name="end_time" id="end_time_str_add"  />
                        </div>
                    </div>
                </li>
                <li class="f_item probability_add">
                    <div class="box">
                        <div class="f_label">
                            <span> 抽奖概率:</span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" id="probability_add" name="probability" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item max_times_per_day_add">
                    <div class="box">
                        <div class="f_label">
                            <span>每人每天最多抽奖次数:</span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" id="max_times_per_day_add" name="max_times_per_day" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>图片: </span>
                        </div>
                        <div class="box_flex f_content">

                            <a href="#" iconCls="icon-add" id="qiniu_uploader_add" class="easyui-linkbutton" plain="true">
                                上传图片
                            </a>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                        </div>
                        <div class="box_flex f_content">
                            <ul id="pics_list_add">
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </form>
    </div>

</div>
<div style="display: none;">
    <div id="edit_coverage_dialog" style="margin:5px 0 0 0;">
        <div id="edit_coverage_container" style="padding-bottom: 10px;"></div>
        <div style="width:520px;height:340px;border:1px solid gray;" id="edit_coverage_map_container"></div>
    </div>
</div>
<style>
    #image_list .img_list{
        float:left;
        margin:5px;
    }
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qiniu_upload_single.js?v=20200901"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/baidu_map/CityList.js?v=201405243259"></script>
<script type="text/javascript">
var map;
var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_add_form = $('#add_form');
var jq_filter_status = $('#filter_status');
var jq_image_list = $('#image_list');

var jq_add_dialog = $('#add_dialog');
var jq_edit_coverage_dialog = $('#edit_coverage_dialog');
var jq_ss = $('#ss');
var jq_aa = $('#aa');
var status_data = <?php echo json_encode($status); ?>;

var jq_setStatus = $('#setStatus');
var jq_setStatus_add = $('#setStatus_add');
var module_router = site_root + '/index.php?r=goods';
var jq_action_info = $('#action_info');
$(function(){

    $("input[name='type']").change(function() {
        var _do = $(this).attr('class').replace('type_', '');
        //alert(_do)
        $('#probability_'+_do).val('');
        $('#max_times_per_day_'+_do).val('');

        if($('input:radio:checked').val() != "lottery"){
            $('.probability_'+_do).hide();
            $('.max_times_per_day_'+_do).hide();
        }else{

            $('.probability_'+_do).show();
            $('.max_times_per_day_'+_do).show();
        }

    });

    jq_image_list.on('click','div',function(e){
        if(confirm("确定要删除这张图片么？")){
            //if(pics_count>0){
            // pics_count--;
            //}
            $(this).remove();
        }
    })

    $('#pics_list_add').on('click','li',function(e){
        if(confirm("确定要删除这张图片么？")){
            //if(pics_count>0){
            // pics_count--;
            //}
            $(this).remove();
        }
    })

    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    $('#start_time').datebox({
        required: false,
        onSelect: function(date){
            var currentDate = new Date();
            if(currentDate>date){
                $.messager.show({
                    title: '提示',
                    msg: '必须选择今天之后的日期',
                    timeout: 3500,
                    showType: 'slide'
                });
            }else{
                $('#start_time_str').val(date.getTime()/1000);
            }
        }
    });

    $('#end_time').datebox({
        required: false,
        onSelect: function(date){
            var currentDate = new Date();
            if(currentDate>date){
                $.messager.show({
                    title: '提示',
                    msg: '必须选择今天之后的日期',
                    timeout: 3500,
                    showType: 'slide'
                });
            }else{
                $('#end_time_str').val(date.getTime()/1000);
            }
        }
    });

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


    jq_setStatus.combobox({
        editable: false,
        data: status_data
    });

    jq_setStatus_add.combobox({
        editable: false,
        data: status_data
    });

    jq_add_dialog.dialog({
        title: '新建商品',
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


            $('#pics_list_add').html('');
            $.fn.qiniu_upload_single('init',{
                bucket:'pics',
                button:"#qiniu_uploader_add",
                before_upload:function(){
                    //if(pics_count>=max_pic_count){
                    // $.messager.alert('提示', '最多上传9张图片哦', 'warning');
                    //return false;
                    //}else{
                    return true;
                    // }
                },
                success_callback:function(result){
                    var img_url = result.url;
                    //pics_count++;

                    var img = new Image();
                    img.onload=function(){
                        var width = img.width,
                            height = img.height;
                        var obj = '{"url":"'+img_url+'","width":'+width+',"height":'+height+'}';
                        $('#pics_list_add').append("<li style='float: left;padding-right: 5px;'><img src='"+img_url+"' style='max-width: 160px;' /><input type='hidden' name='pics[]' value=" + obj + " /></li>");
                    };
                    img.src=img_url;
                },
                fail_callback:function(){
                    $.messager.alert('提示', '上传失败，请稍后再试', 'warning');
                    return false;
                }
            });

        }
    });

    jq_edit_coverage_dialog.dialog({
        title: '选择区域',
        width: 540,
        height: 460,
        closed: true,
        modal: true,
        buttons:[{
            text: '添加',
            iconCls: 'icon-add',
            handler: function(){

                $.messager.progress();
            }
        },{
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function(){
                jq_edit_coverage_dialog.dialog('close');
            }
        }],
        onOpen:function(){
            map = new BMap.Map("edit_coverage_map_container");
            map.centerAndZoom(new BMap.Point(121.455129,31.229402), 12);

            var cityList = new BMapLib.CityList({
                container: 'edit_coverage_container',
                map: map
            });
        }
    });

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '商品列表',
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
            {field:'name', title:'名字', width:120,sortable:false},
            {field:'status', title:'状态', width:30, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            },
            {field:'type', title:'分类', width:30, sortable: true},
            {field:'is_real', title:'实物', width:20, sortable: true},
            {field:'count', title:'库存', width:20, sortable: true},
            {field:'score', title:'爪币', width:30, sortable: true}

        ]],
        onSelect: function(index, row){

            var data = $.extend({}, row);
            jq_content_form.form('load', data);

            $('#admins_edit_info').html('');


            $('#start_time').datebox('setValue', parse_time(data.start_time));
            $('#end_time').datebox('setValue', parse_time(data.end_time));

            if(data.type != 'lottery'){
                $('#probability_edit').val('');
                $('#max_times_per_day_edit').val('');

                $('.probability_edit').hide();
                $('.max_times_per_day_edit').hide();
            }else{
                $('.probability_edit').show();
                $('.max_times_per_day_edit').show();
            }

            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }

            jq_image_list.empty();
            if(data.pics)
            {
                if(data.pics.length > 0 && data.pics[0] != '') {
                    $.each(data.pics, function(k, v) {
                        //pics_count++;

                        var width = v.width,
                            height = v.height;
                        var obj = '{"url":"'+ v.url+'","width":'+width+',"height":'+height+'}';
                        jq_image_list.append("<div class='img_list'><img src='" + v.url + "' width='150' height='150' /><input type='hidden' name='pics[]' value='" + obj + "' /></div>");
                    });
                }
            }
            $.fn.qiniu_upload_single('init',{
                bucket:'pics',
                button:"#qiniu_uploader_edit",
                before_upload:function(){
                    //if(pics_count>=max_pic_count){
                    // $.messager.alert('提示', '最多上传9张图片哦', 'warning');
                    //return false;
                    //}else{
                    return true;
                    //}
                },
                success_callback:function(result){

                    var img_url = result.url;
                    // pics_count++;

                    var img = new Image();
                    img.onload=function(){
                        var width = img.width,
                            height = img.height;
                        var obj = '{"url":"'+img_url+'","width":'+width+',"height":'+height+'}';
                        jq_image_list.append("<div class='img_list'><img src='" + img_url + "' width='150' height='150' /><input type='hidden' name='pics[]' value='" + obj + "' /></div>");
                    };
                    img.src=img_url;
                },
                fail_callback:function(){
                    $.messager.alert('提示', '上传失败，请稍后再试', 'warning');
                    return false;
                }
            });


            $("#on_loading").show();
            $('#id_str').html(data.id);

        },

        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');

            jq_content_form.form('clear');
            $('#id_str').html('');

            jq_action_info.html('');

            jq_image_list.empty();


            $('#admins_edit_info').html('');
            $('#start_time_str').val('');
            $('#end_time_str').val('');
            $('#admins_edit').val('');
            jq_dg_content.datagrid('clearSelections');

            jq_setStatus.combobox('setValue', 100);
        }
    });

    jq_content_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){
            if ($('#goods_id').val() == ""){
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
});
function search_content(){
    var filter_status = jq_filter_status.combobox('getValue');

    var search = jq_ss.searchbox('getValue');
    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {search: search, status : filter_status}
    });
}
function save_content(){
    if ($('#goods_id').val() == ""){
        return false;
    }

    if (jq_setStatus.combobox('getValue') != 1){
        $.messager.confirm('注意', '确认删除该商品吗？', function(r){
            $.messager.progress();
            jq_content_form.submit();
        });
    } else {
        $.messager.progress();
        jq_content_form.submit();
    }
}

function add_content(){
    jq_add_dialog.dialog('open');
    $('#start_time_add').datebox({
        required: false,
        onSelect: function(date){
            var currentDate = new Date();
            if(currentDate>=date){
                $.messager.show({
                    title: '提示',
                    msg: '必须选择今天之后的日期',
                    timeout: 3500,
                    showType: 'slide'
                });
            }else{
                $('#start_time_str_add').val(date.getTime()/1000);
            }
        }
    });
    $('#end_time_add').datebox({
        required: false,
        onSelect: function(date){
            var currentDate = new Date();
            if(currentDate>=date){
                $.messager.show({
                    title: '提示',
                    msg: '必须选择今天之后的日期',
                    timeout: 3500,
                    showType: 'slide'
                });
            }else{
                $('#end_time_str_add').val(date.getTime()/1000);
            }
        }
    });
}

function edit_add_coverage(){
    jq_edit_coverage_dialog.dialog('open');
}

function parse_time(time){
    if(time){
        var d=new Date(time*1000);
        return formatDate(d);
    }else{
        return '';
    }
}

function   formatDate(now){
    var   year=now.getFullYear();
    var   month=now.getMonth()+1;
    var   date=now.getDate();
    return   year+"-"+month+"-"+date;
}
</script>
