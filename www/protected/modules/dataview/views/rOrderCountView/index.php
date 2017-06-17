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
<div region="west" border="false" id="west_panel" style="width: 430px;">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <p>
                <span class="tb_label">开始</span>
                <input type="text" id="date_start" value="<?php echo date('Y-m-d', time()); ?>" style="width: 100px;"/>
                <span class="tb_label">结束</span>
                <input type="text" id="date_end" value="<?php echo date('Y-m-d', time()); ?>" style="width: 100px;"/>
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </p>
            <p>
                <span class="tb_label">筛选</span>
                <input id="filter_list" />
            </p>
        </div>
    </div>
</div>
<div region="center" title="统计图表">
<div class="easyui-layout detail_layout">
<div data-options="region:'center'" class="detail_center">
<div class="detail_main">
    <form id="content_form">
        <div id="getEChart">
            <p>
                <input id="filter_echart">
                <span id="filter_time_span">
                    <input id="filter_time">
                </span>
                <span id='date_range_span' style="display:none;">
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
    </form>
    <div data-options="region:'center'" class="detail_center">
        <div class="detail_main">
            <div></div>
            <div id="echart_price" style="height:450px;width:720px"></div>
            <div id="echart_count" style="height:450px;width:720px"></div>
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

var module_router = site_root + '/index.php?r=dataview/rOrderCountView';

var jq_content_form = $('#content_form');
var jq_dg_content =$('#dg_content');
var jq_date_start = $('#date_start');
var jq_date_end = $('#date_end');
var jq_date_start_echart = $('#date_start_echart');
var jq_date_end_echart = $('#date_end_echart');
var jq_filter_list = $('#filter_list');
var jq_filter_echart = $('#filter_echart');
var jq_filter_time = $('#filter_time');

var filter = <?php echo json_encode($filter); ?>;
var filter_time = <?php echo json_encode($filter_time); ?>;

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

    jq_filter_list.combobox({
        width: 100,
        data: filter,
        editable: false,
        onSelect: function () {
            search_content();
        }
    });

    jq_filter_echart.combobox({
        width: 100,
        data: filter,
        editable: false
    });

    jq_filter_time.combobox({
        width: 200,
        data: filter_time,
        editable: false
    })

    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '订单统计',
        width: 420,
        height: w_height - 18,
        fitColumns: true,
        autoRowHeight: true,
        striped: true,
        toolbar: '#tb_content',
        singleSelect: true,
        selectOnCheck: false,
        checkOnSelect: false,
        pagination: false,
        nowrap: false,
        idField: 'filter_index',
        sortName: 'count',
        sortOrder: 'desc',
        queryParams: get_param_obj(),
        frozenColumns:[],
        columns:[[
            {field: 'filter_str', title: '分类', width: 30},
            {field: 'ori_price', title: '原价', width:30},
            {field: 'price', title: '折后', width: 30},
            {field: 'count', title: '数量', width: 30}
        ]],

        queryParams: {
            date_start : jq_date_start.datebox('getValue'),
            date_end   : jq_date_end.datebox('getValue'),
            filter     : 1
        },

        onSelect: function (index, row) {
            var data = $.extend({}, row);
            jq_content_form.form('load', data);
        }
    });
});

function search_content () {
    var date_start = jq_date_start.datebox('getValue');
    var date_end = jq_date_end.datebox('getValue');
    var filter_list = jq_filter_list.combobox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            filter: filter_list,
            date_start: date_start,
            date_end: date_end
        }
    });
}

function showSetDateRange () {
    jq_filter_time.combobox('setValue', 'Days');
    jq_date_start_echart.datebox('setValue', '');
    jq_date_end_echart.datebox('setValue', '');

    $('#filter_time_span').css({'display':'none'});
    $('#date_range_span').css({'display':'inline-block'});
}

function showSetFilterTime () {
    jq_date_start_echart.datebox('setValue', '');
    jq_date_end_echart.datebox('setValue', '');

    $('#filter_time_span').css({'display':'inline-block'});
    $('#date_range_span').css({'display':'none'});
}

function getChartBar () {
    var filter_echart = jq_filter_echart.combobox('getValue');
    var filter_time = jq_filter_time.combobox('getValue');
    var date_start_echart = jq_date_start_echart.combobox('getValue');
    var date_end_echart = jq_date_end_echart.combobox('getValue');

    $('#loading').fadeIn('slow', function () {});

    $.post(
        module_router + '/getChartBar',
        {
            filter      : filter_echart,
            filter_time : filter_time,
            date_start  : date_start_echart,
            date_end    : date_end_echart
        },
        function (data, status) {
            $('#loading').fadeOut('slow', function () {});
            var data = jQuery.parseJSON(data);
            showChartBar(data);
        }
    );
}

function getChartPie () {
    var date_start_echart = jq_date_start_echart.combobox('getValue');
    var date_end_echart = jq_date_end_echart.combobox('getValue');
    var filter_echart = jq_filter_echart.combobox('getValue');
    var filter_time = jq_filter_time.combobox('getValue');

    $('#loading').fadeIn('slow', function () {});

    $.post(
        module_router + '/getChartPie',
        {
            date_start  : date_start_echart,
            date_end    : date_end_echart,
            filter      : filter_echart,
            filter_time : filter_time
        },
        function (data, status) {
            $('#loading').fadeOut('slow', function () {});
            var data = jQuery.parseJSON(data);
            showChartPie(data);
        }
    );
}

function showChartBar (data) {
    require(
        [
            'echarts',
            'echarts/chart/line',
            'echarts/chart/bar'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_price'));
            var option = {
                title : {
                    text: '订单价格统计',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    y    : 'bottom',
                    data : data['filter_arr']
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
                    y2 : '18%'
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
                            name : data['content'][key]['filter'],
                            type : 'bar',
                            data : data['content'][key]['price']
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
                title : {
                    text: '订单数量统计',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    y    : 'bottom',
                    data : data['filter_arr']
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
                    y2 : '18%'
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
                            name : data['content'][key]['filter'],
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

function showChartPie (data) {
    require(
        [
            'echarts',
            'echarts/chart/pie',
            'echarts/chart/funnel'
        ],
        function (ec) {
            var material_chart = ec.init(document.getElementById('echart_price'));

            option = {
                title : {
                    text: '订单价格分部',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient : 'vertical',
                    x : 'left',
                    y : 'bottom',
                    data: data['filter_arr']
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
                                    name  : data['content'][key]['filter'],
                                    value : data['content'][key]['price'],
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
                title : {
                    text: '订单数量分部',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient : 'vertical',
                    x : 'left',
                    y : 'bottom',
                    data: data['filter_arr']
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
                                    name  : data['content'][key]['filter'],
                                    value : data['content'][key]['count'],
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
</script>