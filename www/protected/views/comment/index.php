<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=201401161314" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=201401161314"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/source/helpers/jquery.fancybox-textarea.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/fancybox/source/helpers/jquery.fancybox-textarea.js?v=1.0.5"></script>
<!--<script type="text/javascript" src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/js/www/type_selector.js?v=201905243259"></script>-->
<div id="main">
    <div region="west" border="false" style="width: 550px;">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="ss"/>
                <div id="mm">
                  <!--   <div data-options="name:'0'">用户名</div> -->
                    <div data-options="name:'1'">模糊搜</div>
                    <div data-options="name:'2'">精确搜索</div>
                </div>
                
                <span class="tb_label">服务类型: </span>
                <input id="filter_type" />
                <div class="right" style="display: none;">
                    <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
                </div>
           
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
                <div class="right" style="display: none;">
                    <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
                </div>
            </div>
             <div class="tb_line">
                <div class="tb_box">
                    <span class="tb_label">批量操作: </span>
                    <select id="multi_options" style="width:80px;">
                        <option value=100 selected="selected">批量操作</option>
                        <option value=1>正常</option>
                        <option value=0>删除</option>
                        
                    </select>
                </div>
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
                                        <input type="hidden" name="id" id="post_id" value='' />
                                        <span id="id_str"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>服务种类: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span id="jq_type" name="type"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item" id="content_area">
                                <div class="box">
                                    <div class="f_label">
                                        <span>时间: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <span id="time_str"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item" id="content_area">
                                <div class="box">
                                    <div class="f_label">
                                        <span>内容: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <textarea name="content" disabled="disabled" style="width: 250px;min-height: 200px"></textarea>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item" id="content_area">
                                <div class="box">
                                    <div class="f_label">
                                        <span>图片: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <ul id="image_list"></ul>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>权重:</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input id="setWeight" name="weight" />
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
                                        <span>保洁师:</span>
                                    </div>
                                    <div class="box_flex f_content" id="technician_name">
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>回复:</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <textarea name="reply" style="width: 250px; height: 120px;"></textarea>
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
</div>
<style type="text/css">
    #image_list .img_list{
        float:left;
        margin:5px;
    }

    .clear {
        clear: both;
    }
</style>
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_content_id = $('#post_id');
var jq_set_button = $('.set_button');
var jq_multi_options = $('#multi_options');
var jq_filter_status = $('#filter_status');
var jq_filter_type = $('#filter_type');

var jq_setStatus = $('#setStatus');
var jq_setWeight = $('#setWeight');

var jq_topic = $('#jq_topic');
var jq_type = $('#jq_type');
var jq_image_list = $('#image_list');
var jq_ss = $('#ss');
var jq_action_info = $('#action_info');
var status_data = <?php echo json_encode($status); ?>;
var type_data = <?php echo json_encode($type); ?>;

var module_router = site_root + '/index.php?r=comment';
$(function(){
//    $.fn.group_selector('init',{
//        container:"group_selector",
//        onselected:function(value){
//            search_content(value);
//        }
//    });
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();
    jq_ss.searchbox({
        width: 200,
        menu: '#mm',
        searcher:function(value){
            search_content();
        },
        prompt: '请输入关键字'
    });
   
    jq_filter_type.combobox({
        width: 90,
        editable: false,
        data: type_data,
        onSelect: function(){
            search_content();
        }
    });

    jq_filter_status.combobox({
        width: 90,
        editable: false,
        data: status_data,
        onSelect: function(){
            search_content();
        }
    });

    jq_setStatus.combobox({
        editable: false,
        data: status_data
    });

    jq_multi_options.combobox({
        editable: false,
        onSelect: function(){
            var ids = [];
            var rows = jq_dg_content.datagrid('getChecked');
            for(var i=0;i<rows.length;i++){
                ids.push(rows[i].id);
            }
            var multi_options = jq_multi_options.combobox('getValue');
            if(ids.length){
                $.ajax({
                    url: site_root + '/index.php?r=comment/SetStatus',
                    type: 'POST',
                    data: {status: multi_options, ids: ids.join(',')},

                    success: function(res) {
                        if (res.success) {
                            jq_dg_content.datagrid('reload');
                            jq_dg_content.datagrid('clearChecked');
                            jq_dg_content.datagrid('clearSelections');
                            $('#multi_options').combobox('setValue',100);
                            $.messager.show({
                                title: '提示',
                                msg: '批量修改成功',
                                timeout: 3500,
                                showType: 'slide'
                            });
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
            }
        }
    });

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '评价列表',
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
        sortName: 'time',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],

        columns:[[
            {field:'user', title:'用户', width:40,
                formatter: function(value, row){
                    var username = value.user_name;
                    if(value.is_fake_user){
                        username = '<span style="color:red;">'+value.user_name+'</span>';
                    }
                    return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rUser&id='+value.id+'\');">'+ username +'</a>';
                }
            },
            {field:'type', title:'服务种类', width:50, sortable: true,
                formatter: function(value, row){
                        return get_filed_text(value, type_data);
                    }
            },
            {field:'time', title:'时间', width:50, sortable: true,
                formatter: function(value, row){
                    return row.time_str;
                    
                }
            },
            {field:'status', title:'状态', width:25, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);      
                }
            },
            {field:'weight', title:'权重', width:25, sortable: true,
               },

            {field:'score', title:'评分', width:25, sortable: true,
            },
            
            {field:'content', title:'内容', width:70,
                formatter: function(value, row){
                    if(value){
                        return value.substring(0,20);
                    }
                    
                }
            },
            {field:'order', title:'订单', width:25,
                formatter: function(value, row){
                    if(value){
                        return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rOrder&id='+value+'\');">'+ '订单' +'</a>';
                    }

                }
            }

        ]],
        onSelect: function(index, row){
            var data = $.extend({}, row);
            jq_content_form.form('load', data);
            jq_set_button.linkbutton('enable');

            
            $('#id_str').html(data.id);
            $('#time_str').html(format_time_stamp(data.time,true));
            $('#jq_type').html(data.type);
            $('#weight').html(data.weight);
            if(data.content){
            
                $('#content_area').show();
               
                
                $('#voice_li').hide();
            }
            

                if (data['action_user'] != ''){
                    jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }

            if (data.pics) {
                if (data.pics.length > 0 && data.pics[0] != '') {
                    var pic_count = 0;
                    $('#image_list').empty();
                    $.each(data.pics, function(key, row){
                        pic_count++;
                        var obj = '{"url":'+row.url+',"width":'+row.width+',"height":'+row.height+'}';
                        $('#image_list').append('<div class="img_list"><img src="'+row.url+'" width="150" height="150"><input type="hidden" name="pics[]" value="'+obj+'" /></div>');
                        if (pic_count % 3 == 0) {
                            $('#image_list').append('<div class="clear"></div>');
                        }
                    });
                } else {
                    $('#image_list').empty();
                    $('#image_list').append('<span>无图片</span>');
                }
            }
            var $output = '';
            for(var j in data.technicians) {
                $output += '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=o2o/tech&id=' + data.technicians[j].technician_id + '\');">' + data.technicians[j].technician_name + '</a>&nbsp';
            }
            $('#technician_name').html($output);
        },
        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');
            jq_content_form.form('clear');
           // console.log(data);
            jq_set_button.linkbutton('disable');
            $('#id_str').html('');
            /*$('#pics').val();*/
            $('#voice_li').hide();
            $('#time_str').html('');
            $('#video_li').hide();
            $('#image_list').empty();
          /*  $('#pics_li').hide();*/
            jq_setStatus.combobox('setValue', 100);
            jq_image_list.empty();
            jq_action_info.html('');

            $('#technician_name').empty();
        }
    });
    jq_content_form.form({
        url: module_router + '/update',
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
                jq_topic.html('');
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
    $('.fancybox').fancybox({
        prevEffect: 'none',
        nextEffect: 'none',
        type: 'image',
        helpers : {
            buttons : {}
        },
        closeBtn: false
    });
})
function search_content(){
    var filter_type = jq_filter_type.combobox('getValue');
    var filter_status = jq_filter_status.combobox('getValue');
    var search = jq_ss.searchbox('getValue');
    var search_type = jq_ss.searchbox('getName');
    var param = {type : filter_type, status : filter_status};

 
    if (search != ''){
        param['search'] = search;
        param['search_type'] = search_type;
    }
    jq_dg_content.datagrid({
        queryParams: param,
        pageNum: 1
    });
}

function save_content(){
    var a_id = jq_content_id.val();
    if (!a_id){
        return false;
    }
    if (jq_setStatus.combobox('getValue') != 1){
        $.messager.confirm('注意', '确认删除该条回复吗？', function(r){
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
</script>