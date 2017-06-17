<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
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
                <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">新增</a>
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
                                        <input type="hidden" name="id" id="station_id" value='' />
                                        <span id="id_str"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>名字: </span>
                                    </div>
                                    <div class="box_flex f_content" id="">
                                        <input type="text" name="name" style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>起始服务时间（如9）: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="start_time" style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>结束服务时间（如19）: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="end_time" style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>保洁师数量: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="text" name="beauticians_count" style="width: 250px;"/>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>地址</span>: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="hidden" name="address" id="address_edit" />
                                        <div id="address_edit_info"></div>
                                        <div><a href="javascript:void();" id="select_address_edit">设置地址</a></div>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>服务范围</span>: </span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="hidden" name="coverage" id="coverage_edit" />
                                        <div id="coverage_edit_info"></div>
                                        <div><a class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="edit_edit_coverage();return false;" id="select_coverage_edit">添加服务范围</a></div>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item" style="display: none;">
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
                        <div class="box_flex f_content" id="">
                            <input type="text" name="name" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>起始服务时间（如9）: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="start_time" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>结束服务时间（如19）: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="end_time" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>保洁师数量: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" name="beauticians_count" style="width: 250px;"/>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>地址</span>: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="hidden" name="address" id="address_add" />
                            <div id="address_add_info"></div>
                            <div><a href="javascript:void();" id="select_address_add">设置地址</a></div>
                        </div>
                    </div>
                </li>
                <li class="f_item" style="display: none;">
                    <div class="box">
                        <div class="f_label">
                            <span>服务范围</span>: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="hidden" name="coverage" id="coverage_add" value="" />
                            <div id="coverage_add_info"></div>
                            <div><a href="javascript:void();" id="select_coverage_add">设置服务范围</a></div>
                        </div>
                    </div>
                </li>
                <li class="f_item" style="display: none;">
                    <div class="box">
                        <div class="f_label">
                            <span>服务项目:</span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="addType" name="type" />
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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/baidu_map/CityList.js?v=201405243209"></script>
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
var jq_add_dialog = $('#add_dialog');
var jq_ss = $('#ss');
var jq_aa = $('#aa');
var status_data = <?php echo json_encode($status); ?>;

var jq_setStatus = $('#setStatus');
var jq_setStatus_add = $('#setStatus_add');
var module_router = site_root + '/index.php?r=station';
var jq_action_info = $('#action_info');
var jq_edit_coverage_dialog = $('#edit_coverage_dialog');
var type_data = <?php echo json_encode($type); ?>;
$(function(){

    $('#select_address_edit').click(function(){
        $.fn.position_selector('init',{
            width:$(window).width()-300,//弹框显示宽度
            height:$(window).height()-100,//弹框显示高度
            zoom:18,  //缩放级别
            locat:'上海',//默认城市
            can_edit:true,
            lat:$('#view_latitude').val(),
            lng:$('#view_longitude').val(),
            func_callback:function(val){
                var myGeo = new BMap.Geocoder();
                myGeo.getLocation(new BMap.Point(val[0], val[1]), function(result){
                    if (result){
                        console.log(result);
                        var address = {};
                        address.province = result.addressComponents.province;
                        address.city = result.addressComponents.city;
                        address.area = result.addressComponents.district;
                        address.business = result.business;
                        address.detail = result.address;
                        address.position = [val[0], val[1]];
                        $('#address_edit').val(JSON.stringify(address));
                        $('#address_edit_info').html(address.detail);
                    }
                });
            },//选择成功之后的回调函数
            element_id:'map_container'//弹窗ID
        });return false;
    });
    $('#select_address_add').click(function(){
        $.fn.position_selector('init',{
            width:$(window).width()-300,//弹框显示宽度
            height:$(window).height()-100,//弹框显示高度
            zoom:18,  //缩放级别
            locat:'上海',//默认城市
            can_edit:true,
            lat:31.229402,
            lng:121.455129,
            func_callback:function(val){
                var myGeo = new BMap.Geocoder();
                myGeo.getLocation(new BMap.Point(val[0], val[1]), function(result){
                    if (result){
                        var address = {};
                        console.log(result)
                        address.province = result.addressComponents.province;
                        address.city = result.addressComponents.city;
                        address.area = result.addressComponents.district;
                        address.business = result.business;
                        address.detail = result.address;
                        address.position = [val[0], val[1]];
                        $('#address_add').val(JSON.stringify(address));
                        $('#address_add_info').html(address.detail);
                    }
                });
            },//选择成功之后的回调函数
            element_id:'map_container'//弹窗ID
        });return false;
    });

    $('body').on('click','.del_coverage',function(){
        //console.log($(this).parent('div').attr('data'));
        $(this).parent('div').remove();

        var coverages = new Array();
        if($('#coverage_edit').val()){
            coverages = JSON.parse($('#coverage_edit').val());
        }

        coverages.splice($(this).parent('div').attr('data'),1);

        $('#coverage_edit_info').children('div').each(function(index,e){
            console.log(index);
            $(this).attr("data",index);
        })

        $('#coverage_edit').val(JSON.stringify(coverages));
    });

    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();


    jq_filter_status.combobox({
        width: 100,
        data: status_data,
        editable: false,
        onSelect: function(){
            search_content();
        }
    });

    $('#editType').combotree({
        editable: false,
        multiple:true,
        data: type_data
    });

    jq_setStatus.combobox({
        editable: false,
        data: status_data
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

                var coverages = new Array();
                if($('#coverage_edit').val()){
                    coverages = JSON.parse($('#coverage_edit').val());
                }

                var coverage = {};
                coverage.province = storage.getItem('province');
                coverage.city = storage.getItem('city');
                coverage.area = storage.getItem('area');

                if(storage.getItem('business')){
                    coverage.business = storage.getItem('business');
                }
                if(storage.getItem('points')){
                    coverage.points = storage.getItem('points');
                }

                if(!coverage.province || !coverage.city || !coverage.area){
                    $.messager.show({
                        title: '提示',
                        msg: '服务范围选择错误',
                        timeout: 3500,
                        showType: 'slide'
                    });
                    storage.removeItem('province');
                    storage.removeItem('city');
                    storage.removeItem('area');
                    storage.removeItem('business');
                    storage.removeItem('points');
                    jq_edit_coverage_dialog.dialog('close');
                }else{
                    coverages.push(coverage);

                    $('#coverage_edit').val(JSON.stringify(coverages));
                    $('#coverage_edit_info').html($('#coverage_edit_info').html()+parse_coverage_item(coverage,false,coverages.length));


                    storage.removeItem('province');
                    storage.removeItem('city');
                    storage.removeItem('area');
                    storage.removeItem('business');
                    storage.removeItem('points');
                    jq_edit_coverage_dialog.dialog('close');
                }


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
            map.centerAndZoom(new BMap.Point(31.229402,121.455129), 12);

            var cityList = new BMapLib.CityList({
                container: 'edit_coverage_container',
                map: map
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
        sortName: 'id',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'id', title:'id', hidden:true},
            {field:'name', title:'名字', width:30, sortable: false},
            {field:'start_time', title:'开始服务时间', width:30, sortable: true},
            {field:'end_time', title:'结束服务时间', width:30, sortable: true},
            {field:'beauticians_count', title:'保洁师数量', width:20, sortable: true},
            {field:'status', title:'状态', width:30, sortable: true,
                formatter: function(value, row){
                    return get_filed_text(value, status_data);
                }
            }
        ]],
        onSelect: function(index, row){

            var data = $.extend({}, row);
            jq_content_form.form('load', data);

            $('#admins_edit_info').html('');

            if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }
            $('#address_edit_info').html(data.address.detail);
            $('#address_edit').val(JSON.stringify(data.address));

            var coverage_info = '';
            for(var i in data.coverage){
                coverage_info += parse_coverage_item(data.coverage[i],i == data.coverage.length-1,i);

            }

            $('#coverage_edit_info').html(coverage_info);
            $('#coverage_edit').val(JSON.stringify(data.coverage));
            $("#on_loading").show();
            $('#id_str').html(data.id);

        },

        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');

            jq_content_form.form('clear');
            $('#id_str').html('');
            jq_action_info.html('');
            $('#address_edit').val('')
            $('#address_edit_info').html('');

            $('#coverage_edit').val('')
            $('#coverage_edit_info').html('');
            jq_dg_content.datagrid('clearSelections');

            jq_setStatus.combobox('setValue', 100);
            $('#editType').combobox('setValue', 100);
        }
    });

    jq_content_form.form({
        url: module_router + '/edit',
        onSubmit: function(param){
            if ($('#station_id').val() == ""){
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
                $('#address_edit').val('')
                $('#address_edit_info').html('');
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
                $('#address_add').val('')
                $('#address_add_info').html('');
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

function parse_coverage_item(coverage,is_last,index){
    if(coverage.business){
        return '<div data='+index+'>['+coverage.province+'-'+coverage.city+'-'+coverage.area+'-'+coverage.business+']'+'&nbsp;&nbsp;<a href="#" class="del_coverage">删除</a></div>';
    }else{
        return '<div data='+index+'>['+coverage.province+'-'+coverage.city+'-'+coverage.area+']'+'&nbsp;&nbsp;<a href="#"  class="del_coverage">删除</a></div>';
    }
}

function edit_edit_coverage(){
    jq_edit_coverage_dialog.dialog('open');
}

function save_content(){
    if ($('#station_id').val() == ""){
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
    $('#addType').combotree({
        editable: false,
        multiple:true,
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
</script>