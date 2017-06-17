<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qiniu_upload_single.js?v=20200901"></script>
<div id="main">
<div region="west" border="false" id="west_panel">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <span class="tb_label">状态: </span>
            <input id="filter_status" />
            <a href="#" class='easyui-linkbutton' iconCls="icon-add" plain="true" onclick="add_content();return false;">新增</a>
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
            <input type="hidden" name="id" id="product_id" value='' />
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
                <span>价格: </span>
            </div>
            <div class="box_flex f_content">
                <input type="text" name="price" style="width: 250px;"/>
            </div>
        </div>
    </li>
    <li class="f_item">
        <div class="box">
            <div class="f_label">
                <span>分类信息: </span>
            </div>
            <div class="box_flex f_content">
                <div id="extra_add_info"></div>
                <div><a class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_extra();return false;">添加分类信息</a></div>
                <input type="hidden" name="extra" id="add_extra" value='' />
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
                <span>图文介绍: </span>
            </div>
            <div class="box_flex f_content">
                <textarea name="desc" style="width: 250px;min-height: 300px" placeholder='[{ "type": 1,  "content": "http://www.test.com/a.jpg"},  {"type": 2,"content": "描述文案..."} ]'></textarea>
            </div>
        </div>
    </li>
    <li class="f_item" id="pics_li">
        <div class="box">
            <div class="f_label">
                <span>图片: </span>
            </div>
            <div class="box_flex f_content" id="upload_pics">
                <a href="#" iconCls="icon-add" id="qiniu_uploader" class="easyui-linkbutton" plain="true">
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
                        <span>价格: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" name="price" style="width: 250px;"/>
                    </div>
                </div>
            </li>
                <li class="f_item" style="display:none;">
                    <div class="box">
                        <div class="f_label">
                            <span>分类信息: </span>
                        </div>
                        <div class="box_flex f_content">
                            <div id="extra_add_info"></div>
                            <div><a class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_extra();return false;">添加分类信息</a></div>
                            <input type="hidden" name="extra" id="add_extraadd_extra" value='' />
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
                            <span>图文介绍: </span>
                        </div>
                        <div class="box_flex f_content">
                            <textarea name="desc" style="width: 250px;min-height: 300px" placeholder='[{ "type": 1,  "content": "http://www.test1.com/a.jpg"},  {"type": 2,"content": "描述文案..."} ]'>></textarea>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>图片: </span>
                        </div>
                        <div class="box_flex f_content" id="upload_pics_add">
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
            </ul>
        </form>
    </div>

</div>

<div style="display: none;">
    <div id="edit_extra_dialog" style="margin:5px 0 0 0;">
        <ul>
            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>类型: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" id="edit_extra_type" style="width: 250px;"/>
                    </div>
                </div>
            </li>


            <li class="f_item">
                <div class="box">
                    <div class="f_label">
                        <span>价格: </span>
                    </div>
                    <div class="box_flex f_content">
                        <input type="text" id="edit_extra_price" style="width: 250px;"/>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<style>
    #pics_list_add li{
        float: left;
        background: none repeat scroll 0 0 #FFFFFF;
        height: 135px;
        margin: 5px;;
        overflow: visible;
        position: relative;
        width: 180px;
    }
    #loading{
        display:none;
        width:200px;
        height:32px;
        position:relative;
        z-index:9999;
        font-size: 16px;
    }
    #loading img{
        width:16px;
        height:16px;
    }
    #image_list .img_list{
        float:left;
        margin:5px;
    }
</style>
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_add_form = $('#add_form');
var jq_filter_status = $('#filter_status');
var jq_add_dialog = $('#add_dialog');
var jq_ss = $('#ss');
var jq_aa = $('#aa');
var status_data = <?php echo json_encode($status); ?>;
var type_data = <?php echo json_encode($type); ?>;

var jq_setStatus = $('#setStatus');
var jq_setStatus_add = $('#setStatus_add');
var module_router = site_root + '/index.php?r=product';
var jq_action_info = $('#action_info');

var max_pic_count = 9;
var pics_count=0;
var jq_image_list = $('#image_list');

var jq_upload_image_list = $('#upload_image_list');
var jq_edit_extra_dialog = $('#edit_extra_dialog');

$('body').on('click','.del_extra',function(){
        $(this).parent('div').remove();

        var extras = new Array();
        if($('#add_extra').val()){
            extras = JSON.parse($('#add_extra').val());
        }

        extras.splice($(this).parent('div').attr('data'),1);

        $('#extra_add_info').children('div').each(function(index,e){
            console.log(index);
            $(this).attr("data",index);
        })

        $('#add_extra').val(JSON.stringify(extras));
    });

$(function(){

    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    $('#editType').combobox({
        editable: false,
        data: type_data
    });

    jq_filter_status.combobox({
        width: 100,
        data: status_data,
        editable: false,
        onSelect: function(){
            search_content();
        }
    });

    jq_image_list.on('click','div',function(e){
        if(confirm("确定要删除这张图片么？")){
            if(pics_count>0){
                pics_count--;
            }
            $(this).remove();
        }
    })

    $('#pics_list_add').on('click','li',function(e){
        if(confirm("确定要删除这张图片么？")){
            if(pics_count>0){
                pics_count--;
            }
            $(this).remove();
        }
    })

    jq_setStatus.combobox({
        editable: false,
        data: status_data
    });

    jq_edit_extra_dialog.dialog({
        title: '添加分类信息',
        width: 440,
        height: 160,
        closed: true,
        modal: true,
        buttons:[{
            text: '添加',
            iconCls: 'icon-add',
            handler: function(){
                var extras = new Array();
                if($('#add_extra').val()){
                    extras = JSON.parse($('#add_extra').val());
                }

                var extra = {};
                extra.type = $('#edit_extra_type').val();
                extra.price = $('#edit_extra_price').val();
                if(!extra.type || !extra.price){
                    $.messager.show({
                        title: '提示',
                        msg: '填错啦',
                        timeout: 3500,
                        showType: 'slide'
                    });
                    //jq_edit_coverage_dialog.dialog('close');
                }else{
                    extras.push(extra);
                    $('#add_extra').val(JSON.stringify(extras));

                    $('#extra_add_info').html($('#extra_add_info').html()+parse_extra_item(extra,false,extras.length));
                    jq_edit_extra_dialog.dialog('close');
                }
            }
        },{
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function(){
                jq_edit_extra_dialog.dialog('close');
            }
        }],
        onOpen:function(){
            $('#edit_extra_type').val('');
            $('#edit_extra_price').val('');
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
            jq_add_form.form('load', {});
            pics_count = 0;

            $('#pics_list_add').html('');
            $.fn.qiniu_upload_single('init',{
                bucket:'icons',
                button:"#qiniu_uploader_add",
                before_upload:function(){
                    if(pics_count>=max_pic_count){
                        $.messager.alert('提示', '最多上传9张图片哦', 'warning');
                        return false;
                    }else{
                        return true;
                    }
                },
                success_callback:function(result){
                    var img_url = result.url;
                    pics_count++;

                    var img = new Image();
                    img.onload=function(){
                        var width = img.width,
                            height = img.height;
                        var obj = '{"url":"'+img_url+'","width":'+width+',"height":'+height+'}';
                        $('#pics_list_add').append("<li style='float: left;'padding-right: 5px;><img src='"+img_url+"' style='max-width: 160px;' /><input type='hidden' name='pics[]' value=" + obj + " /></li>");
                    };
                    img.src=img_url;
                },
                fail_callback:function(){
                    $.messager.alert('提示', '上传失败1，请稍后再试', 'warning');
                    return false;
                }
            });
        }
    });
    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '列表',
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
            {field:'name', title:'名字',width:120},
            {field:'price', title:'价格', width:30, sortable: true},

            {field:'type_str', title:'服务项目', width:50},
            {field:'status', title:'状态', width:60, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            },
            {field:'order', title:'权重', width:30, sortable: true}

        ]],
        onSelect: function(index, row){

            var data = $.extend({}, row);
            jq_content_form.form('load', data);

            $('#admins_edit_info').html('');

            jq_image_list.empty();
            if(data.pics)
            {
                if(data.pics.length > 0 && data.pics[0] != '') {
                    $.each(data.pics, function(k, v) {
                        pics_count++;

                        var width = v.width,
                            height = v.height;
                        var obj = '{"url":"'+ v.url+'","width":'+width+',"height":'+height+'}';
                        jq_image_list.append("<div class='img_list'><img src='" + v.url + "' width='150' height='150' /><input type='hidden' name='pics[]' value='" + obj + "' /></div>");
                    });
                }
            }
            $.fn.qiniu_upload_single('init',{
                bucket:'icons',
                button:"#qiniu_uploader",
                before_upload:function(){
                    if(pics_count>=max_pic_count){
                        $.messager.alert('提示', '最多上传9张图片哦', 'warning');
                        return false;
                    }else{
                        return true;
                    }
                },
                success_callback:function(result){

                    var img_url = result.url;
                    pics_count++;

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
                    $.messager.alert('提示', '上传失败2，请稍后再试', 'warning');
                    return false;
                }
            });

            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }


            var extra_info = '';
            for(var i in data.extra){
                extra_info += parse_extra_item(data.extra[i],i == data.extra.length-1,i);
            }

            $('#extra_add_info').html(extra_info);
            $('#add_extra').val(JSON.stringify(data.extra));

            $("#on_loading").show();
            $('#id_str').html(data.id);

        },

        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');

            jq_content_form.form('clear');
            $('#id_str').html('');

            jq_action_info.html('');

            jq_dg_content.datagrid('clearSelections');
            pics_count = 0;
            jq_image_list.empty();

            $('#editType').combobox('setValue', 100);
        }
    });

    jq_content_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){
            if ($('#product_id').val() == ""){
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

function save_content(){
    if ($('#product_id').val() == ""){
        return false;
    }

    if (jq_setStatus.combobox('getValue') != 1){
        $.messager.confirm('注意', '确认删除吗？', function(r){
            $.messager.progress();
            jq_content_form.submit();
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

function search_content(){
    var filter_status = jq_filter_status.combobox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {status : filter_status}
    });
}

function add_extra(){
    jq_edit_extra_dialog.dialog('open');
}

function parse_extra_item(extra,is_last,index){
    return '<div data='+index+'>['+extra.type+'-'+extra.price+']'+'&nbsp;&nbsp;<a href="#"  class="del_extra">删除</a></div>';
}
</script>