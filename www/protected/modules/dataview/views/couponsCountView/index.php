<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
    .options {
        display: inline-block;
        border: 1px solid #e5e5e5;
        background: #fff;
        color: #000;
        padding: 3px 6px;
        text-decoration: none;
    }
</style>
<div id="main">
<div region="west" border="false" id="west_panel" style="width: 460px;">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <div style="padding: 2px 0px 1px 0px;">
                <span class="tb_label">开始</span>
                <input id="date_start" type="text" value="<?php echo date('Y-m-d', time()); ?>" style="width: 100px"/>
                <span class="tb_label">结束</span>
                <input id="date_end" type="text" value="<?php echo date('Y-m-d', time()); ?>"style="width: 100px"/>
            </div>
            <div style="padding: 1px 0px 1px 0px;">
                <span class="tb_label">状态</span>
                <input id="status_filter_datagrid" type="text" style="width: 100px" />
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </div>
            <div style="padding: 1px 0px 2px 0px;">
                <span id="time_filter_id_span" style="display:none;">
                    <span class="tb_label">范围</span>
                    <input id="time_filter_id" type="text" style="width: 100px;"/>
                </span>
                <a href="#" id="showSetFilterTimeIdBtn" class='easyui-linkbutton' iconCls="icon-filter" plain="true" onclick="showSetFilterTimeId();return false;">打开时间范围选取</a>
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="getChartById();return false;">获取单个优惠券统计</a>
            </div>
        </div>
    </div>
</div>
<div region="center" title="统计图表">
<div class="easyui-layout detail_layout">
<div data-options="region:'center'" class="detail_center">
<div class="detail_main">
    <div id="getEchart">
        <p>
            <input id="status_filter_echart" type="text" style="width: 100px;" />
            <span id="time_filter_span">
                <input id="time_filter" style="width:180px;"/>
            </span>
            <span id="date_range_span" style="display:none;">
                <input id="date_start_echart" style="width: 100px" />
                <input id="date_end_echart" style="width: 100px" />
            </span>
            <span id="loading" style="display:none;">正在查询，请稍等</span>
        </p>
        <p>
            <a href="#" class='easyui-linkbutton' iconCls="icon-filter" plain="true" onclick="showSetDateRange();return false;">选择具体日期</a>
            <a href="#" class='easyui-linkbutton' iconCls="icon-filter" plain="true" onclick="showSetFilterTime();return false;">选择时间范围</a>
            <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="getChartBar();return false;">获取柱状图</a>
            <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="getChartPie();return false;">获取饼状图</a>
        </p>
    </div>
    <form id="content_form">
        <input id="coupon_id" type="hidden" name="id" />
    </form>
    <div data-options="region:'center'" class="detail_center">
        <div class="detail_main">
            <div id="echart_by_id" style="display:none;width:700px;height:450px;"></div>
            <div style="padding: 0px 0px 8px 0;">
                <span id="echart_price_title" style="display:none;">优惠券统计图</span>
                <span id="echart_price_sub" style="display:none;">&nbsp;根据折后价格统计</span>
            </div>
            <div id="echart_price" style="display:none;height:450px;width:1000px"></div>
            <div style="padding: 0px 0px 8px 0;">
                <span id="echart_count_title" style="display:none;">优惠券统计图</span>
                <span id="echart_count_sub" style="display:none;">&nbsp;根据使用数量统计</span>
            </div>
            <div id="echart_count" style="display:none;height:450px;width:1000px"></div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script type="text/javascript">
var w_width = $(window).width();
var w_height = $(window).height();

var module_router = site_root + '/index.php?r=dataview/couponsCountView';

var jq_content_form = $('#content_form');
var jq_dg_content =$('#dg_content');
var jq_date_start = $('#date_start');
var jq_date_end = $('#date_end');
var jq_date_start_echart = $('#date_start_echart');
var jq_date_end_echart = $('#date_end_echart');
var jq_time_filter = $('#time_filter');
var jq_time_filter_id = $('#time_filter_id');
var jq_status_filter_datagrid = $('#status_filter_datagrid');
var jq_status_filter_echart = $('#status_filter_echart');

var time_filter = <?php echo json_encode($time_filter); ?>;
var status_filter = <?php echo json_encode($status_filter); ?>;

var show_time_filter = 0;

// 载入echarts配置
require.config({
    paths : {
        echarts: 'http://echarts.baidu.com/build/dist'
    }
});

$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }

    jq_date_start.datebox({});
    jq_date_end.datebox({});

    jq_date_start_echart.datebox({});
    jq_date_end_echart.datebox({});

    jq_status_filter_datagrid.combobox({
        width    : 100,
        data     : status_filter,
        editable : false,
        onSelect : function () {
            search_content();
        }
    });

    jq_time_filter.combobox({
        width    : 180,
        data     : time_filter,
        editable : false
    });

    jq_status_filter_echart.combobox({
        width    : 100,
        data     : status_filter,
        editable : false
    });

    jq_time_filter_id.combobox({
        width    : 100,
        data     : time_filter,
        editable : false
    });

    jq_time_filter_id.combobox('setValue', 'Days');

    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '订单统计',
        width: 450,
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
        sortName: 'price',
        sortOrder: 'desc',
        queryParams: $.extend(
            get_param_obj(),
            {
                date_start : jq_date_start.datebox('getValue'),
                date_end   : jq_date_end.datebox('getValue'),
                status     : 1
            }
        ),
        frozenColumns:[],
        columns:[[
            {field: 'name', title: '分类', width: 50},
            {field: 'alias_name', title: '别名', width: 20},
            {field: 'price', title: '原价', width: 30},
            {field: 'final_price', title: '折后', width: 30},
            {field: 'count', title: '数量', width: 30},
            {field: 'status_str', title: '状态', width: 15}
        ]],

        onSelect: function (index, row) {
            var data = $.extend({}, row);
            jq_content_form.form('load', data);
        },

        onLoadSuccess: function(){
            $(this).datagrid('clearChecked');
            jq_content_form.form('clear');
        }
    });
});

function search_content () {
    var date_start = jq_date_start.datebox('getValue');
    var date_end = jq_date_end.datebox('getValue');
    var status = jq_status_filter_datagrid.combobox('getValue');

    jq_dg_content.datagrid({
        pageNum : 1,
        queryParams : {
            date_start : date_start,
            date_end   : date_end,
            status     : status
        }
    });
}

function showSetDateRange () {
    jq_time_filter.combobox('setValue', 'Days');
    jq_date_start_echart.datebox('setValue', '');
    jq_date_end_echart.datebox('setValue', '');

    $('#time_filter_span').css({'display':'none'});
    $('#date_range_span').css({'display':'inline-block'});
}

function showSetFilterTime () {
    jq_date_start_echart.datebox('setValue', '');
    jq_date_end_echart.datebox('setValue', '');
    jq_time_filter.combobox('setValue', 'noSelect');
    
    $('#time_filter_span').css({'display':'inline-block'});
    $('#date_range_span').css({'display':'none'});
}

function getChartBar () {
    var time_filter = jq_time_filter.combobox('getValue');
    var status_filter_echart = jq_status_filter_echart.combobox('getValue');
    var date_start = jq_date_start_echart.datebox('getValue');
    var date_end = jq_date_end_echart.datebox('getValue');

    if (time_filter == 'noSelect') {
        alert('请选择时间范围！');
        return false;
    }

    $('#loading').fadeIn('slow', function(){});

    $('#echart_price').show();
    $('#echart_count').show();
    $('#echart_by_id').hide();

    $.post(
        module_router + '/getChartBar',
        {
            time_filter   : time_filter,
            status_filter : status_filter_echart,
            date_start    : date_start,
            date_end      : date_end
        },
        function (data, status) {
            $('#loading').fadeOut('slow', function(){});
            var data = jQuery.parseJSON(data);
            showChartBar(data);
        }
    );
}

function showChartBar (data) {
    $('#echart_price_title').css({'display':'inline-block', 'font-size':'18px', 'font-weight':'bold'});
    $('#echart_count_title').css({'display':'inline-block', 'font-size':'18px', 'font-weight':'bold'});
    $('#echart_price_sub').css({'display':'inline-block', 'font-size':'14px', 'color':'#CCCCCC'});
    $('#echart_count_sub').css({'display':'inline-block', 'font-size':'14px', 'color':'#CCCCCC'});

    require(
        [
            'echarts',
            'echarts/chart/line',
            'echarts/chart/bar'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_price'));
            var option = {
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    orient  : 'vertical',
                    x       : 'left',
                    data    : data['coupons']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['bar', 'line']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                grid : {
                    x: 330
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : data['date_arr']
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        name : '价格',
                        axisLabel : {
                            formatter: '{value} 元'
                        }

                    }
                ],
                series : (function(){
                    var series = [];

                    for (var key in data.content) {

                        var item_price = {
                            name : data['content'][key]['coupon'],
                            type : 'bar',
                            data : data['content'][key]['final_price']
                        }

                        series.push(item_price);
                    }

                    return series;
                })()
            };
            material_chart.setOption(option);
        }
    );

    require(
        [
            'echarts',
            'echarts/chart/line',
            'echarts/chart/bar'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_count'));
            var option = {
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    orient : 'vertical',
                    x      : 'left',
                    data   : data['coupons']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['bar', 'line']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                grid : {
                    x: 330
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : data['date_arr']
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        name : '数量',
                        axisLabel : {
                            formatter: '{value} 单'
                        }

                    }
                ],
                series : (function(){
                    var series = [];

                    for (var key in data.content) {
                        var item_count = {
                            name : data['content'][key]['coupon'],
                            type : 'line',
                            data : data['content'][key]['count']
                        };

                        series.push(item_count);
                    }

                    return series;
                })()
            };
            material_chart.setOption(option);
        }
    );
}

function getChartPie () {
    var time_filter = jq_time_filter.combobox('getValue');
    var status_filter_echart = jq_status_filter_echart.combobox('getValue');
    var date_start = jq_date_start_echart.datebox('getValue');
    var date_end = jq_date_end_echart.datebox('getValue');

    if (time_filter == 'noSelect') {
        alert('请选择时间范围！');
        return false;
    }

    $('#loading').fadeIn('slow', function () {});

    $('#echart_price').show();
    $('#echart_count').show();
    $('#echart_by_id').hide();

    $.post(
        module_router + '/getChartPie',
        {
            time_filter   : time_filter,
            status_filter : status_filter_echart,
            date_start    : date_start,
            date_end      : date_end
        },
        function (data, status) {
            $('#loading').fadeOut('slow', function(){});
            var data = jQuery.parseJSON(data);
            showChartPie(data);
        }
    );
}

function showChartPie (data) {
    $('#echart_price_title').css({'display':'inline-block', 'font-size':'18px', 'font-weight':'bold'});
    $('#echart_count_title').css({'display':'inline-block', 'font-size':'18px', 'font-weight':'bold'});
    $('#echart_price_sub').css({'display':'inline-block', 'font-size':'14px', 'color':'#CCCCCC'});
    $('#echart_count_sub').css({'display':'inline-block', 'font-size':'14px', 'color':'#CCCCCC'});

    require(
        [
            'echarts',
            'echarts/chart/pie',
            'echarts/chart/funnel'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_price'));

            option = {
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient : 'vertical',
                    x : 'left',
                    data: data['coupons']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {
                            show: true, 
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'left',
                                    max: 1548
                                }
                            }
                        },
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                // grid : {
                //     x: 330
                // },
                calculable : true,
                series : [
                    {
                        name:'价格分布',
                        type:'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data : (function () {
                            var pie_data = [];
                            for (key in data.content) {
                                var item = {
                                    name  : data['content'][key]['coupon'],
                                    value : data['content'][key]['final_price']
                                };

                                pie_data.push(item);
                            }

                            return pie_data;
                        })()
                    }
                ]
            };material_chart.setOption(option);
        }
    );

    require(
        [
            'echarts',
            'echarts/chart/pie',
            'echarts/chart/funnel'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_count'));

            option = {
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient : 'vertical',
                    x : 'left',
                    data: data['coupons']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {
                            show: true, 
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'left',
                                    max: 1548
                                }
                            }
                        },
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                // grid : {
                //     x: 330
                // },
                calculable : true,
                series : [
                    {
                        name:'数量分布',
                        type:'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data : (function () {
                            var pie_data = [];
                            for (key in data.content) {
                                var item = {
                                    name  : data['content'][key]['coupon'],
                                    value : data['content'][key]['count']
                                };

                                pie_data.push(item);
                            }

                            return pie_data;
                        })()
                    }
                ]
            };material_chart.setOption(option);
        }
    );
}

function showSetFilterTimeId () {
    if (show_time_filter == 0) {
        $('#time_filter_id_span').show();
        $('#time_filter_id').combobox('setValue', 'noSelect');
        $('#showSetFilterTimeIdBtn span span:first-child').html('关闭时间范围选取');
        show_time_filter = 1;
    } else {
        $('#time_filter_id_span').hide();
        $('#time_filter_id').combobox('setValue', 'Days');
        $('#showSetFilterTimeIdBtn span span:first-child').html('打开时间范围选取');
        show_time_filter = 0;
    }
}

function getChartById () {
    var id = $('#coupon_id').val();
    var date_start = jq_date_start.combobox('getValue');
    var date_end = jq_date_end.combobox('getValue');
    var time_filter = jq_time_filter_id.combobox('getValue');

    if (id == '') {
        alert('请先选择优惠券！');
        return false;
    }

    if (time_filter == 'noSelect' || (show_time_filter == 0 && time_filter == 'Days' && (date_start == '' || date_end == ''))) {
        alert('请选择时间范围！');
        return false;
    }


    $('#loading').fadeIn('slow', function () {});

    $('#echart_price').hide();
    $('#echart_count').hide();
    $('#echart_by_id').show();
    $('#echart_price_title').hide();
    $('#echart_price_sub').hide();
    $('#echart_count_title').hide();
    $('#echart_count_sub').hide();

    $.post(
        module_router + '/getChartById',
        {
            id : id,
            date_start : date_start,
            date_end : date_end,
            time_filter : time_filter,
            filter_week : show_time_filter
        },
        function (data, status) {
            $('#loading').fadeOut('slow', function(){});
            var data = jQuery.parseJSON(data);
            showChartById(data);
        }
    );
}

function showChartById (data) {
    require(
        [
            'echarts',
            'echarts/chart/line',
            'echarts/chart/bar'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_by_id'));
            var option = {
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    y    : 'bottom',
                    data : ['原价', '折后价', '数量']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['bar', 'line']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : data['date_arr']
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        name : '价格',
                        axisLabel : {
                            formatter: '{value} 元'
                        }
                    },
                    {
                        type : 'value',
                        name : '数量',
                        axisLabel : {
                            formatter: '{value} 单'
                        }
                    }
                ],
                series : [
                    {
                        name : '原价',
                        type : 'line',
                        data : data['content']['price']
                    },
                    {
                        name : '折后价',
                        type : 'line',
                        data : data['content']['final_price']
                    },
                    {
                        name : '数量',
                        type : 'bar',
                        data : data['content']['count'],
                        yAxisIndex : 1
                    }
                ]
            };
            material_chart.setOption(option);
        }
    );
}
</script>