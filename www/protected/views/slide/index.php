<div id="main">
    <div region="west" border="false" style="width: 550px;">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
                <div class="right" style="display: none;">
                    <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
                </div>
            </div>
            <div style="margin: 3px 2px;padding:5px;border: 1px solid #95B8E7;">
                <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">新增</a>
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
                                        <span>标题: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="hidden" name="id" id="slide_id" value='' />
                                        <input type="text" name="title" style="width: 250px;"/>
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
                                        <span>链接类型: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input id="type_topic" type="radio" name="type" value="topic" />
                                        <label for="type_topic">帖子</label>
                                        <input id="type_group" type="radio" name="type" value="group" />
                                        <label for="type_group">圈子</label>
                                        <!-- 暂时不上线 2015-12-24     -->
                                        <input id="type_url" type="radio" name="type" value="url" />
                                        <label for="type_url">url</label>
                                        <input id="type_subject" type="radio" name="type" value="subject" />
                                        <label for="type_subject">话题</label>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>目标: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="obj" id="obj_edit" style="width: 250px;"/><br />
                                        <span id="jq_info"></span>
                                    </div>
                                </div>
                            </li>

                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>图片: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="hidden" name="pic" id="pic_edit"/>
                                        <a href="#" iconCls="icon-add" id="qiniu_uploader_edit" class="easyui-linkbutton" plain="true">
                                            上传图片
                                        </a>
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

                            <li class="f_item" id="">
                                <div class="box">
                                    <div class="f_label">
                                        <span>上线日期:</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input id="start_time" type="text" >
                                        <input type="hidden" name="start_time" id="start_time_str"  />
                                    </div>
                                </div>
                            </li>
                            <li class="f_item" id="">
                                <div class="box">
                                    <div class="f_label">
                                        <span>下架日期:</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input id="end_time" type="text" >
                                        <input type="hidden" name="end_time" id="end_time_str"  />
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
<div style="display: none;">
    <div id="add_dialog" style="padding: 15px 0;">
        <form id="add_form" method="post">
            <ul>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>标题: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="hidden" name="id" id="slide_id" value='' />
                            <input type="text" name="title" style="width: 250px;"/>
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
                            <span>链接类型: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="type_topic" type="radio" name="type" value="topic" />
                            <label for="type_topic">帖子</label>
                            <input id="type_group" type="radio" name="type" value="group" />
                            <label for="type_group">圈子</label>
                            <!-- 暂时不上线 2015-12-24     -->
                            <input id="type_url" type="radio" name="type" value="url" />
                            <label for="type_url">URL</label>
                            <input id="type_subject" type="radio" name="type" value="subject" />
                            <label for="type_subject">话题</label>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>目标: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="obj" style="width: 250px;"/>
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>图片: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="hidden" name="pic" id="pic_add"/>
                            <a href="#" iconCls="icon-add" id="qiniu_uploader_add" class="easyui-linkbutton" plain="true">
                                上传图片
                            </a>
                        </div>
                    </div>
                </li>
                    <li class="f_item" >
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
                    <li class="f_item" >
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

                <li class="f_item">
                      <div class="box">
                       <div class="f_label">
                           <span>省/城市: </span>
                       </div>
                       <div class="box_flex f_content">
                           <input type="text" name="city_info" id="city_info"  placeholder="如：湖北省"  style="width: 250px;"/>
                         </div>
                    </div>
                </li>
            </ul>
        </form>
    </div>

</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qiniu_upload_single.js?v=20200901"></script>
<script type="text/javascript">
    var jq_dg_content = $('#dg_content');
    var w_width = $(window).width();
    var w_height = $(window).height();
    var jq_content_form = $('#content_form');
    var jq_add_form = $('#add_form');
    var jq_content_id = $('#slide_id');
    // var jq_city_info_edit = $('#city_info_edit');
    var jq_filter_status = $('#filter_status');
    var jq_setStatus = $('#setStatus');
    var jq_add_dialog = $('#add_dialog');

    var jq_action_info = $('#action_info');
    var status_data = <?php echo json_encode($status); ?>;

    var module_router = site_root + '/index.php?r=slide';
    $(function(){

        $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

        jq_setStatus.combobox({
            editable: false,
            data: status_data
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
        $('#start_time').datetimebox({
        required: false,
        onSelect: function(date){
            console.log(date);
            var currentDate = new Date();
            // if(currentDate>date){
            //     $.messager.show({
            //         title: '提示',
            //         msg: '必须选择今天之后的日期',
            //         timeout: 3500,
            //         showType: 'slide'
            //     });
            // }else{
                $('#start_time_str').val(date.getTime()/1000);
            // }
            }
        });
        $('#end_time').datetimebox({
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

        jq_dg_content.datagrid({
            url: module_router + '/list',
            title: '轮播列表',
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
            sortName: 'order',
            sortOrder: 'desc',
            // queryParams: get_param_obj(),
            frozenColumns:[[
                {field:'ck',checkbox:true}
            ]],
            queryParams: $.extend(get_param_obj(),{status : 1}),
            columns:[[
                {field:'title', title:'标题', width:240},
                {field:'type', title:'类型', width:50},
                {field:'status', title:'状态', width:30, sortable: true,
                    formatter: function(value, row){
                        return get_filed_text(value, status_data);
                    }
                },
                {field:'order', title:'排序', width:50},
               {field:'city_info', title:'所属城市', width:50, sortable: false,
                formatter: function(value, row){
                    return formatCity(value);
                }
            }
            ]],
            onSelect: function(index, row){
                var data = $.extend({}, row);console.log(data);
                jq_content_form.form('load', data);

                $('#start_time').datetimebox('setValue', parse_time(data.start_time));
                $('#end_time').datetimebox('setValue', parse_time(data.end_time));

                if(data.type=='topic'){
                    var topic_module = site_root + '/index.php?r=topic&id=' + data.obj.id;
                    $('#jq_info').html('<a href="javascript:void(0);" onclick="parent.load_url(\'' + topic_module + '\');">' + data.obj.content + '</a>');
                }else if(data.type=='group'){
                    var group_module = site_root + '/index.php?r=group&id=' + data.obj.id;
                    $('#jq_info').html('<a href="javascript:void(0);" onclick="parent.load_url(\'' + group_module + '\');">' + data.obj.name + '</a>');
                // 暂时不上线 2015-12-24    
                }else if(data.type=='url'){
                    $('#jq_info').html('<a href="'+data.obj.url+'" >' + data.obj.url + '</a>');
                }else if(data.type=='subject'){
                    var subject_module = site_root + '/index.php?r=subject&id=' + data.obj.id;
                    $('#jq_info').html('<a href="javascript:void(0);" onclick="parent.load_url(\'' + subject_module + '\');">' + data.obj.name + '</a>');
                }
                $('#city_info_edit').val(formatCity(data.city_info));
                $('#obj_edit').val(data.obj.id);
                $('#pic_edit').parent().children('img').remove();
                if(data.pic){
                    $('#pic_edit').val(data.pic).after('<img src="'+data.pic+'" style="max-width: 200px;" />');
                }else{
                    $('#pic_edit').val('');
                }

                $.fn.qiniu_upload_single('init',{
                    bucket:'icons',
                    button:'#qiniu_uploader_edit',
                    success_callback:function(result){
                        var img_url = result.url;
                        $('#pic_edit').parent().children('img').remove();
                        $('#pic_edit').val(img_url).after('<img src="'+img_url+'" style="max-width: 200px;" />');
                    },
                    fail_callback:function(){
                        $.messager.alert('提示', '上传失败，请稍后再试', 'warning');
                        return false;
                    }
                });
            },
            onLoadSuccess: function(){
                $(this).datagrid('clearChecked');
                jq_content_form.form('clear');
                jq_setStatus.combobox('setValue', 100);
                $('#obj_edit').val('');
                $('#pic_edit').val('');
                $('#jq_info').html('');
                $('#start_time_str').val('');
                $('#end_time_str').val('');
                $('#city_info_edit').val('');
                $('#pic_edit').val('').parent().children('img').remove();
                jq_action_info.html('');
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
                $('#pic_add').parent().children('img').remove();
                jq_add_form.form('clear');

                $.fn.qiniu_upload_single('init',{
                    bucket:'icons',
                    button:"#qiniu_uploader_add",
                    success_callback:function(result){
                        var img_url = result.url;
                        $('#pic_add').parent().children('img').remove();
                        $('#pic_add').val(img_url).after('<img src="'+img_url+'" style="max-width: 200px;" />');
                    },
                    fail_callback:function(){
                        $.messager.alert('提示', '上传失败，请稍后再试', 'warning');
                        return false;
                    }
                });

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

    function search_content(){
        var filter_status = jq_filter_status.combobox('getValue');

        var param = {status : filter_status};

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
        jq_add_dialog.dialog('open');
        $('#start_time_add').datetimebox({
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
        $('#end_time_add').datetimebox({
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
    var   hours=now.getHours();
    var   min=now.getMinutes();
    var   sec=now.getSeconds();
    return   year+"-"+month+"-"+date+" "+hours+":"+min+":"+sec;
}
</script>