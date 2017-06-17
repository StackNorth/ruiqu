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
                    <span>提成方案</span>
                    <input id="filter_scheme">
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
                                            <span>头像: </span>
                                        </div>
                                        <div class="box_flex f_content" id="content_avatar">
                                            <div id="content_avatar_show"></div>
                                            <div>
                                                <a href="#" iconCls="icon-add" id="content_avatar_uploader" class="easyui-linkbutton" plain="true">
                                                    上传图片
                                                </a>
                                            </div>
                                            <input type="hidden" name="avatar" id="content_avatar_info" />
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
                                            <span>提成方案: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input name="scheme" id="content_scheme"/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>服务类型: </span>
                                        </div>
                                        <div class="box_flex f_content" id="service_type_container">
                                            <?php foreach ($service_type as $key => $value): ?>
                                                <?php if (($key-1) % 4 == 0 && $key != 1): ?>
                                                    <br>
                                                <?php endif ?>
                                                <span class="service_type_list">
                                                    <input type="checkbox" value="<?=$key?>" name="service_type[]" /><?=$value['name']?>
                                                </span>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>描述: </span>
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
        <div region="center" title="微信信息">
            <div class="easyui-layout detail_layout">
                <div data-options="region:'center'" class="detail_center">
                    <form id="weixin_info_form" method="post">
                        <ul>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>姓名</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input type="hidden" name="_id" />
                                        <input type="hidden" name="name" />
                                        <span id="weixin_info_name"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>微信ID</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="weixin_userid" id="weixin_info_userid" />
                                    </div>
                                </div>
                            </li>
                            <li class="f_item">
                                <div class="box">
                                    <div class="f_label">
                                        <span>手机号</span>
                                    </div>
                                    <div class="box_flex f_content">
                                        <input name="mobile" id="weixin_info_mobile" />
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
                <div data-options="region:'south'" class="detail_south">
                    <div class="detail_toolbar">
                        <a href="#" class="easyui-linkbutton set-button" id="save_weixin_info" iconCls="icon-save" onclick="save_weixin_info()">保存</a>
                    </div>
                </div>
            </div>
        </div>
        <div region="center" title="服务时间">
            <div class="easyui-layout detail_layout">
                <div data-options="region:'center'" class="detail_center">
                    <div id="datetimepicker_container"></div>
                    <div>
                        <form id="free_time_form" method="post">
                            <input type="hidden" name="_id" />
                            <input type="hidden" name="old_time_list" id="old_time_list" value="{}"/>
                            <input type="hidden" name="new_time_list" id="new_time_list" value="{}"/>
                        </form>
                    </div>
                </div>
                <div data-options="region:'south'" class="detail_south">
                    <div class="detail_toolbar">
                        <a href="#" class="easyui-linkbutton set_button" id="save_selected_time" iconCls="icon-save" onclick="save_timeline();return false;">保存</a>
                    </div>
                </div>
            </div>
        </div>
        <div region="center" title="服务范围">
            <div class="easyui-layout detail_layout">
                <div data-options="region:'center'" class="detail_center">
                    <div style="width: 540px;height: 460px;">
                        <div id="add_coverage_container" style="padding-bottom: 10px;display:inline-block;width:400px;"></div>
                        <div id="add_coverage_button" style="display:inline-block;">
                            <a href="#" class="easyui-linkbutton set_button" iconCls="icon-add" plain="true" onclick="add_coverage();">添加服务范围</a>
                        </div>
                        <div id="add_coverage_map_container" style="width:520px;height:340px;"></div>
                        <div>
                            <div id="coverage_info">
                            </div>
                            <form id="coverage_form" method="post">
                                <input type="hidden" name="_id" />
                                <input type="hidden" name="coverage_json" id="coverage_json" />
                            </form>
                        </div>
                    </div>
                </div>
                <div data-options="region:'south'" class="detail_south">
                    <div class="detail_toolbar">
                        <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="save_coverage();return false;">保存</a>
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
var scheme_option = <?php echo json_encode($scheme_option); ?>;

// base
var module_root = site_root + '/index.php?r=o2o/tech';
var w_width = $(window).width();
var w_height = $(window).height();

// datagrid
var jq_dg_content = $('#dg_content');
var jq_search = $('#search');
var jq_filter_status = $('#filter_status');
var jq_filter_scheme = $('#filter_scheme');

// content_form
var jq_content_form = $('#content_form');
var jq_content_status = $('#content_status');
var jq_content_scheme = $('#content_scheme');

// add_form
var jq_add_dialog = $('#add_dialog');
var jq_add_form = $('#add_form');

// free_time
var datePickerStart = <?=json_encode($datePickerStart)?>;
var datePickerEnd = <?=json_encode($datePickerEnd)?>;
var jq_free_time_form = $('#free_time_form');

// coverage
var jq_coverage_form = $('#coverage_form');

// weixin
var jq_weixin_info_form = $('#weixin_info_form');

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

    jq_content_scheme.combobox({
        data: scheme_option,
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

    jq_filter_scheme.combobox({
        data: scheme_option,
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
            status: 1
        }, get_param_obj()),
        sortName: '_id',
        sortOrder: 'asc',
        pagination: true,
        pageSize: 30,
        pageList: [10, 20, 30, 50],
        columns: [[
            {field: 'name', title: '名字', width: 100},
            {field: 'weixin_userid', title: '微信ID', width: 100},
            {field: 'favourable_count', title: '好评数', width: 50},
            {field: 'order_count', title: '服务次数', width: 50},
            {field: 'scheme', title: '提成方案', width: 60,
                formatter: function(value, row) {
                    if (value == -1) {
                        return '<span style="color: orange;">'+row['scheme_str']+'</span>';
                    } else {
                        return '<span>'+row['scheme_str']+'</span>';
                    }
                }
            },
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

            // 头像显示
            $('input[name="file"]').remove();
            $('#content_avatar_show').empty();
            if (data.avatar) {
                var avatar_url = data.avatar;
                $('#content_avatar_info').val(avatar_url);
                $('#content_avatar_show').html('<img src="'+avatar_url+'" style="max-width: 60px" />');
            } else {
                $('#content_avatar_info').val('');
            }

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
                        $('#content_avatar_show').html('<img src="'+img_url+'" style="max-width: 60px;"/>');
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

            // 时间选择
            $('#datetimepicker_container').empty();
            $('#old_time_list').val('{}');
            $('#new_time_list').val('{}');
            showDatetimePicker(data._id);
            jq_free_time_form.form('load', data);

            // 服务范围选择
            initBaiduCityList();
            jq_coverage_form.form('load', data);
            $('#add_coverage_button').show();
            var coverage_info = '';
            for (key in data.coverage) {
                coverage_info += parse_coverage_item(data['coverage'][key], key);
            }
            $('#coverage_info').html(coverage_info);

            // 微信信息
            jq_weixin_info_form.form('load', data);
            $('#weixin_info_name').html(data.name);
            if (data.weixin_userid) {
                $('#weixin_info_userid').attr('readonly', true);
            } else {
                $('#weixin_info_userid').removeAttr('readonly');
            }
        },
        onLoadSuccess: function() {
            // 基本信息
            jq_content_form.form('clear');
            $('#content_id').html('');
            $('#content_avatar_show').empty();

            // 时间选择
            jq_free_time_form.form('clear');
            $('#datetimepicker_container').empty();

            // 服务范围
            jq_coverage_form.form('clear');
            $('#add_coverage_container').empty();
            $('#add_coverage_map_container').empty();
            $('#add_coverage_button').hide();
            $('#coverage_info').empty();
            
            // 微信信息
            jq_weixin_info_form.form('clear');
            $('#weixin_info_name').empty();
            $('#weixin_info_userid').removeAttr('disabled');
        }
    });

    // free_time
    jq_free_time_form.form({
        url: module_root + '/modifyFreetime',
        onSubmit: function(param) {
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

            if (res.success) {
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

    // coverage
    jq_coverage_form.form({
        url: module_root + '/modifyCoverage',
        onSubmit: function(param) {
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

            if (res.success) {
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

    // 删除item绑定事件
    $('body').on('click', '.del_coverage_item', function() {
        var coverages = new Array();
        if ($('#coverage_json').val()) {
            var coverages = JSON.parse($('#coverage_json').val());
        }

        coverages.splice($(this).parent('div').attr('data'), 1);
        $(this).parent('div').remove();
        $('#coverage_info').children('div').each(function(index, e) {
            $(this).attr('data', index);
        });

        $('#coverage_json').val(JSON.stringify(coverages));
    });

    // weixin_info
    jq_weixin_info_form.form({
        url: module_root + '/modifyWeixinInfo',
        onSubmit: function(param) {
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

            if (res.success) {
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
});

function search_content() {
    var status = jq_filter_status.combobox('getValue');
    var scheme = jq_filter_scheme.combobox('getValue');
    var search = jq_search.searchbox('getValue');

    var query = {
        status: status,
        scheme: scheme,
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

    if (jq_content_scheme.combobox('getValue') == 100) {
        $.messager.alert('提示', '请选择一个提成方案');
        return false;
    }

    var status = jq_content_status.combobox('getValue');
    if (parseInt(status) == -1) {
        $.messager.confirm('提示', '确认删除吗？<br>该操作将删除后台用户，同时禁用微信端成员', function (r) {
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

// 显示时间选择插件
function showDatetimePicker(_id) {
    $.post(
        module_root + '/getTechTimeline',
        {
            _id: _id
        },
        function (res) {
            var data = $.parseJSON(res);
            var old_time_list = {};
            var selected = {
                day: {},
                hours: {}
            }
            for (key in data) {
                if (data[key]['selected']) {
                    selected.day[key] = parseInt(data[key]['selected']);
                    selected.hours[key] = data[key]['selectedHours'].slice();
                    old_time_list[key] = data[key]['selectedHours'].slice();
                }
            }
            $('#old_time_list').val(JSON.stringify(old_time_list));
            $('#datetimepicker_container').append('<input id="datetimepicker" size="16" type="text" value="'+datePickerStart+'" readonly class="form-datetime am-form-field">');
            initDatetimepicker(datePickerStart, datePickerEnd, selected);
        }
    );
}

function initDatetimepicker(startDate, endDate, selected) {
    $('#datetimepicker').datetimepicker({
        language:  'zh-CN',
        format: 'yyyy-mm-dd hh:ii:ss',
        startDate: startDate,
        endDate: endDate,
        startView: 2,
        minView: 1,
        todayBtn: true,
        todayHighlight: true,
        startHours: 9,
        endHours: 19,
        minuteStep: 10,
        selected: selected
    }).on('changeHour', function(event){
        newSelectedTime(event);
    }).on('today', function(event) {
        newSelectedTime(event);
    }).on('allDay', function(event) {
        newSelectedTime(event);
    });
}

function newSelectedTime(event) {
    var new_time_list = JSON.parse($('#new_time_list').val());
    if (event.type == 'today') {
        var dateObj = new Date();
        var today_year = dateObj.getFullYear() + '';
        var today_month = dateObj.getMonth() + 1 + '';
        var today_date = dateObj.getDate() + '';
        if (today_month.length < 2) {
            today_month = '0' + today_month;
        }
        if (today_date.length < 2) {
            today_date = '0' + today_date;
        }

        var today_key = today_year + today_month + today_date;

        new_time_list[today_key] = [
            0, 0, 0, 0, 0, 0, 0, 0,
            0, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 0, 0, 0, 0
        ];
    } else {
        new_time_list = event.selectedHours;
    }

    $('#new_time_list').val(JSON.stringify(new_time_list));
}

function save_timeline() {
    jq_free_time_form.submit();
}

function initBaiduCityList() {
    map = new BMap.Map('add_coverage_map_container');
    map.centerAndZoom(new BMap.Point(31.229402,121.455129), 12);
    var cityList = new BMapLib.CityList({
        container: 'add_coverage_container',
        map: map
    });
}

function add_coverage() {
    var coverages = new Array();
    if ($('#coverage_json').val()) {
        coverages = JSON.parse($('#coverage_json').val());
    }

    var coverage_item = {};
    coverage_item.province = storage.getItem('province');
    coverage_item.city     = storage.getItem('city');
    coverage_item.area     = storage.getItem('area');

    if (storage.getItem('business') && storage.getItem('business') != '请选择') {
        coverage_item.business = storage.getItem('business');
    }

    if (coverage_item.city != '上海市') {
        $.messager.alert('提示', '必须选择上海市');
        return false;
    } else if (!coverage_item.province || !coverage_item.city || !coverage_item.area) {
        $.messager.alert('提示', '必须选择区');
        return false;
    } else if (!coverage_item.business) {
        var businessArray = [];
        $('#add_coverage_container>select').last().children().each(function (index, element) {
            if (this.title != '请选择') {
                var coverage_json = $('#coverage_json').val();
                var coverages = coverage_json ? JSON.parse(coverage_json) : [];
                coverage_item.business = this.title;
                coverages.push(coverage_item);
                $('#coverage_json').val(JSON.stringify(coverages));
                $('#coverage_info').html($('#coverage_info').html() + parse_coverage_item(coverage_item, coverages.length - 1));
            }
        });
    } else {
        coverages.push(coverage_item);
        $('#coverage_json').val(JSON.stringify(coverages));
        $('#coverage_info').html($('#coverage_info').html() + parse_coverage_item(coverage_item, coverages.length - 1));
    }

    storage.removeItem('province');
    storage.removeItem('city');
    storage.removeItem('area');
    storage.removeItem('business');
    storage.removeItem('points');
    initBaiduCityList();
}

function parse_coverage_item(item, index) {
    if (item.business) {
        return '<div data='+index+'>['+item.province+'-'+item.city+'-'+item.area+'-'+item.business+']&nbsp;&nbsp;<a href="#" class="del_coverage_item">删除</a></div>';
    } else {
        return '<div data='+index+'>['+item.province+'-'+item.city+'-'+item.area+']&nbsp;&nbsp;<a href="#" class="del_coverage_item">删除</a></div>';
    }
}

function save_coverage() {
    jq_coverage_form.submit();
}

function save_weixin_info() {
    if (!$('#weixin_info_userid').val()) {
        $.messager.alert('提示', '请输入微信ID');
        return false;
    }
    if (!$('#weixin_info_mobile').val()) {
        $.messager.alert('提示', '请输入手机号');
        return false;
    }
    jq_weixin_info_form.submit();
}
</script>