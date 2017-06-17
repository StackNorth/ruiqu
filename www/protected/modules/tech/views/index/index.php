<link rel="stylesheet" href="http://cdn.amazeui.org//amazeui/2.5.0/css/amazeui.min.css">
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datetimepicker.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datetimepicker.js?v=2"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/amazeui.datetimepicker.zh-CN.js?v=2"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/baidu_map/CityList.js?v=201405243209"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qiniu_upload_single.js?v=20200307"></script>
<div id="main">
    <div region="west" id="west_panel" border="false">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <div>
                    <input id="search">
                    <span>状态</span>
                    <input id="filter_status">
                    <div class="right">
                        <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div region="center" class="easyui-accordion">
        <div region="center" title="基本信息">
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
                                            <input type="hidden" name="_id" id="content_id_hide" value="" />
                                            <span id="content_id"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>名字: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="name" />
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>手机号: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="mobile" id="content_mobile" readonly/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>身份证号: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="id_num"  readonly/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>状态: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="status" id="content_status" />
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>紧急联络人姓名: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="contact_name"  readonly/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>紧急联络人电话: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="contact_phone"  readonly/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>介绍人: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="invitor" id="invitor" readonly/>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>上半身正面照片: </span>
                                        </div>
                                        <div class="box_flex f_content" id="content_avatar">
                                            <div id="content_img_upper_body_show"></div>
                                            <div>
                                                <a href="#" iconCls="icon-add" id="content_img_upper_body_uploader" class="easyui-linkbutton" plain="true">
                                                    上传图片
                                                </a>
                                            </div>
                                            <input type="hidden" name="img_upper_body" id="content_img_upper_body_info" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>手持身份证: </span>
                                        </div>
                                        <div class="box_flex f_content" id="content_avatar">
                                            <div id="content_img_handheld_card_show"></div>
                                            <div>
                                                <a href="#" iconCls="icon-add" id="content_img_handheld_card_uploader" class="easyui-linkbutton" plain="true">
                                                    上传图片
                                                </a>
                                            </div>
                                            <input type="hidden" name="img_handheld_card" id="content_img_handheld_card_info" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>身份证正面照片: </span>
                                        </div>
                                        <div class="box_flex f_content" id="content_avatar">
                                            <div id="content_img_card_front_show"></div>
                                            <div>
                                                <a href="#" iconCls="icon-add" id="content_img_card_front_uploader" class="easyui-linkbutton" plain="true">
                                                    上传图片
                                                </a>
                                            </div>
                                            <input type="hidden" name="img_card_front" id="content_img_card_front_info" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>身份证反面照片: </span>
                                        </div>
                                        <div class="box_flex f_content" id="content_avatar">
                                            <div id="content_img_card_back_show"></div>
                                            <div>
                                                <a href="#" iconCls="icon-add" id="content_img_card_back_uploader" class="easyui-linkbutton" plain="true">
                                                    上传图片
                                                </a>
                                            </div>
                                            <input type="hidden" name="img_card_back" id="content_img_card_back_info" />
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>备注: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <textarea name="desc" style="width:200px;height:120px;"></textarea>
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
</div>
<style type="text/css">
body {margin: 8px;}
.f_label {width: 90px;}
.service_type_list {display:inline-block;width:110px;}
</style>
<script type="text/javascript">
// option
var status_option = <?php echo json_encode($status_option); ?>;

// base
var module_root = site_root + '/index.php?r=tech/index';
var w_width = $(window).width();
var w_height = $(window).height();

// datagrid
var jq_dg_content = $('#dg_content');
var jq_search = $('#search');
var jq_filter_status = $('#filter_status');

// content_form
var jq_content_form = $('#content_form');
var jq_content_status = $('#content_status');

// add_form
var jq_add_dialog = $('#add_dialog');
var jq_add_form = $('#add_form');



$(function() {
    var p_width = parseInt(w_width / 2);
    if (p_width < 550) {p_width = 550}
    var d_width = p_width - 18;

    $('#west_panel').css({width: p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    // content_form
    jq_content_status.combobox({
        data: status_option,
        editable: false
    });

    jq_content_form.form({
        url: module_root + '/edit',
        onSubmit: function(params) {
            $.messager.progress();
            var isValid = $(this).form('validate');
            if (!isValid) {
                $.messager.progress('close');
            }
            return isValid;
        },
        success: function(res) {
            $.messager.progress('close');
            var res = JSON.parse(res);
            if(res.success) {
                jq_dg_content.datagrid('reload');
                $.messager.show({
                    title: '提示',
                    msg: '保存成功',
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

    // datagrid
    jq_search.searchbox({
        width: 150,
        searcher: function() {
            search_content();
        },
        prompt: 'ID、名字、微信ID'
    });

    jq_filter_status.combobox({
        data: status_option,
        width: 80,
        editable: false,
        onSelect: function() {
            search_content();
        }
    });


    jq_dg_content.datagrid({
        height: w_height - 18,
        width: d_width,
        title: '保洁师列表',
        idField: '_id',
        url: module_root + '/list',
        toolbar: '#tb_content',
        nowrap: false,
        singleSelect: true,
        fitColumns: true,
        queryParams: $.extend({
            status: 100
        }, get_param_obj()),
        sortName: '_id',
        sortOrder: 'desc',
        pagination: true,
        pageSize: 30,
        pageList: [10, 20, 30, 50],
        columns: [[
            {field: 'name', title: '名字', width: 100},
            {field: 'city', title: '城市', width: 100},
            {field: 'mobile', title: '手机号', width: 100},
            {field: 'status', title: '状态', width: 40,
                formatter: function(value, row) {
                    if (value == 1) {
                        var color = 'green';
                    } else if (value == 0) {
                        var color = 'orange';
                    } else {
                        var color = 'red';
                    }
                    return '<span style="color:'+color+'">'+row.status_str+'</span>';
                }
            }
        ]],
        onSelect: function(index, row) {
            // 载入数据
            var data = $.extend(row, {});
            jq_content_form.form('load', data);
            $('#content_id').html(row._id);
            //$('#contact_name').html(row.contact_info[0]);
            //$('#contact_phone').html(row.contact_info[1]);
            // 头像显示
            $('input[name="file"]').remove();
            $('#content_avatar_show').empty();
            /*if (data.avatar) {
                var avatar_url = data.avatar;
                $('#content_avatar_info').val(avatar_url);
                $('#content_avatar_show').html('<img src="'+avatar_url+'" style="max-width: 260px" />');
            } else {
                $('#content_avatar_info').val('');
            }*/

            // 头像上传
            $.fn.qiniu_upload_single('init', {
                bucket: 'avatars',
                button: '#content_avatar_uploader',
                success_callback: function(result) {
                    console.log(result);
                    var img_url = result.url;

                    var img = new Image();
                    img.onload = function() {
                        var imgWidth = img.width;
                        var imgHeight = img.height;
                        $('#content_avatar_info').val(img_url);
                        $('#content_avatar_show').html('<img src="'+img_url+'" style="max-width: 260px;"/>');
                    }
                    img.src = img_url;
                },
                fail_callback: function() {
                    $.messager.alert('提示', '上传失败，请重试');
                    return false;
                }
            });

            // 上半身图像
            $('#content_img_upper_body_show').empty();
            if (data.img_upper_body) {
                var img_upper_body_url = data.img_upper_body;
                $('#content_img_upper_body_info').val(img_upper_body_url);
                $('#content_img_upper_body_show').html('<img src="'+img_upper_body_url+'" style="max-width: 260px" />');
            } else {
                $('#content_img_upper_body_info').val('');
            }

            // 上半身上传
            $.fn.qiniu_upload_single('init', {
                bucket: 'avatars',
                button: '#content_img_upper_body_uploader',
                success_callback: function(result) {
                    console.log(result);
                    var img_url = result.url;

                    var img = new Image();
                    img.onload = function() {
                        var imgWidth = img.width;
                        var imgHeight = img.height;
                        $('#content_img_upper_body_info').val(img_url);
                        $('#content_img_upper_body_show').html('<img src="'+img_url+'" style="max-width: 260px;"/>');
                    }
                    img.src = img_url;
                },
                fail_callback: function() {
                    $.messager.alert('提示', '上传失败，请重试');
                    return false;
                }
            });
            // 手持身身份证图像
            $('#content_img_handheld_card_show').empty();
            if (data.img_handheld_card) {
                var img_handheld_card_url = data.img_handheld_card;
                $('#content_img_handheld_card_info').val(img_handheld_card_url);
                $('#content_img_handheld_card_show').html('<img src="'+img_handheld_card_url+'" style="max-width: 260px" />');
            } else {
                $('#content_img_handheld_card_info').val('');
            }
            // 手持身身份证上传
            $.fn.qiniu_upload_single('init', {
                bucket: 'avatars',
                button: '#content_img_handheld_card_uploader',
                success_callback: function(result) {
                    console.log(result);
                    var img_url = result.url;

                    var img = new Image();
                    img.onload = function() {
                        var imgWidth = img.width;
                        var imgHeight = img.height;
                        $('#content_img_handheld_card_info').val(img_url);
                        $('#content_img_handheld_card_show').html('<img src="'+img_url+'" style="max-width: 260px;"/>');
                    }
                    img.src = img_url;
                },
                fail_callback: function() {
                    $.messager.alert('提示', '上传失败，请重试');
                    return false;
                }
            });
            // 身份证正面图像
            $('#content_img_card_front_show').empty();
            if (data.img_card_front) {
                var img_card_front_url = data.img_card_front;
                $('#content_img_card_front_info').val(img_card_front_url);
                $('#content_img_card_front_show').html('<img src="'+img_card_front_url+'" style="max-width: 260px" />');
            } else {
                $('#content_img_card_front_info').val('');
            }
            // 身份证正面上传
            $.fn.qiniu_upload_single('init', {
                bucket: 'avatars',
                button: '#content_img_card_front_uploader',
                success_callback: function(result) {
                    console.log(result);
                    var img_url = result.url;

                    var img = new Image();
                    img.onload = function() {
                        var imgWidth = img.width;
                        var imgHeight = img.height;
                        $('#content_img_card_front_info').val(img_url);
                        $('#content_img_card_front_show').html('<img src="'+img_url+'" style="max-width: 260px;"/>');
                    }
                    img.src = img_url;
                },
                fail_callback: function() {
                    $.messager.alert('提示', '上传失败，请重试');
                    return false;
                }
            });
            // 身份证反面图像
            $('#content_img_card_back_show').empty();
            if (data.img_card_back) {
                var img_card_back_url = data.img_card_back;
                $('#content_img_card_back_info').val(img_card_back_url);
                $('#content_img_card_back_show').html('<img src="'+img_card_back_url+'" style="max-width: 260px" />');
            } else {
                $('#content_img_card_back_info').val('');
            }
            // 身份证反面照片上传
            $.fn.qiniu_upload_single('init', {
                bucket: 'avatars',
                button: '#content_img_card_back_uploader',
                success_callback: function(result) {
                    console.log(result);
                    var img_url = result.url;

                    var img = new Image();
                    img.onload = function() {
                        var imgWidth = img.width;
                        var imgHeight = img.height;
                        $('#content_img_card_back_info').val(img_url);
                        $('#content_img_card_back_show').html('<img src="'+img_url+'" style="max-width: 260px;"/>');
                    }
                    img.src = img_url;
                },
                fail_callback: function() {
                    $.messager.alert('提示', '上传失败，请重试');
                    return false;
                }
            });

            // 服务类型选择
            $('#service_type_container').children().each(function (index, element) {
                var val = parseInt($(this).find('input').val());
                if ($.inArray(val, data.service_type) != -1) {
                    $(this).find('input').prop('checked', true);
                } else {
                    $(this).find('input').prop('checked', false);
                }
            });

        },
        onLoadSuccess: function() {
            // 基本信息
            jq_content_form.form('clear');
            $('#content_id').html('');
            $('#content_avatar_show').empty();

            $('#content_img_upper_body_show').empty();
            $('#content_img_handheld_card_show').empty();
            $('#content_img_card_front_show').empty();
            $('#content_img_card_back_show').empty();

        }
    });


});

function search_content() {
    var status = jq_filter_status.combobox('getValue');
    //var scheme = jq_filter_scheme.combobox('getValue');
    var search = jq_search.searchbox('getValue');

    var query = {
        status: status,
        search: search
    };

    jq_dg_content.datagrid({
        queryParams: query
    });
}

function save_content() {
    if (!$('#content_id_hide').val()) {
        $.messager.alert('提示', '请选择一个保洁师');
        return false;
    }

    var status = jq_content_status.combobox('getValue');

    if (parseInt(status) == -1) {
        $.messager.confirm('提示', '确认删除吗？', function (r) {
            if (!r) {
                return false;
            } else {
                jq_content_form.form('submit');
            }
        });
    } else {
        jq_content_form.form('submit');
    }
}

</script>