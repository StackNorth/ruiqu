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
                <span class="tb_label">服务点</span>
                <input id="station" name="station_data" />
            </p>
            <p>
                <span class="tb_label">开始</span>
                <input type="text" id="date_start" value="<?php echo date('Y-m-d', strtotime('-6 month' ,time())); ?>" style="width: 100px;"/>
                <span class="tb_label">结束</span>
                <input type="text" id="date_end" value="<?php echo date('Y-m-d', time()); ?>" style="width: 100px;"/>
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </p>
        </div>
    </div>
</div>
<div region="center" title="统计图表">
<div class="easyui-layout detail_layout">
<div data-options="region:'center'" class="detail_center">
<div class="detail_main">
    <div data-options="region:'center'" class="detail_center">
        <div class="detail_main">
            <form id="content_form" method="post">
                <p>
                    <a class="options" href="/index.php?r=stockViewStation/all">总体概览</a>
                    <span id="days"><a href="/index.php?r=stockViewStation/index" class="options">最近一周</a></span>
                    <span id="weeks"><a href="/index.php?r=stockViewStation/findByWeeks" class="options">最近一月</a></span>
                </p>
            </form>
            <div id="main_station" style="height:600px;width:900px"></div>
        </div>
    </div>
</div>
</div>

</div>
</div>
</div>

<!-- 引入自动填充选择插件 -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/selector.js"></script>
<!-- 用户选择插件引入结束 -->
<!-- 引入echarts插件 -->
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<!-- 引入结束 -->
<script type="text/javascript">
// 载入echarts配置
require.config({
    paths : {
        echarts: 'http://echarts.baidu.com/build/dist'
    }
});

var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');
var jq_filter_station = $('#station');

var module_router = site_root + '/index.php?r=stockViewStation';

var jq_date_start = $('#date_start');
var jq_date_end = $('#date_end');

var station_data = <?php echo json_encode($station_data); ?>;

$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }

    jq_date_start.datebox({});

    jq_date_end.datebox({});

    jq_filter_station.combobox({
        width: 100,
        data: station_data,
        editable: false,
        onSelect: function () {
            search_content();
        }
    });

    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '服务点物资领用统计',
        width: 420,
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
        idField: 'station',
        sortName: 'price_count',
        sortOrder: 'desc',
        queryParams: $.extend(
            get_param_obj(),
            {
                date_start : jq_date_start.datebox('getValue'),
                date_end : jq_date_end.datebox('getValue'),
                merge_data_days : 1
            }
        ),
        frozenColumns:[],
        columns:[[
            {field:'stationName', title:'服务点',width:30},
            {field:'operate_count', title:'领取次数', width:20},
            {field:'price_count', title:'总价格', width:30},
            {field:'station', title:'操作', width:30,
                formatter: function (value, row, index) {
                   // return '<a href="/index.php?r=stock/index&objectName='+row.objectName+'">详情</a>'
                   return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=stockViewStation&station='+row['station']+'\');">详情</a>';
                }
            }
        ]],

        onSelect: function(index, row){

            var data = $.extend({}, row);
            $('#on_loading').show();
            jq_content_form.form('load', data);
            var data = row['data'];

            $('#weeks').html('<a class="options" href="/index.php?r=stockViewStation/findByWeeks&station='+row['station']+'">最近一月</a>');
            $('#days').html('<a class="options" href="/index.php?r=stockViewStation/index&station='+row['station']+'">最近一周</a>');

            require(
                [
                    'echarts',
                    'echarts/chart/line',
                    'echarts/chart/bar'
                ],
                function (ec) {
                    var material_chart = ec.init(document.getElementById('main_station'));

                    var option = {
                        title : {
                            text: row.stationName+'\n'+data.month_range+'物资领取情况',
                            subtext: ''
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['领取总价']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                type : 'category',
                                data : data.month_arr
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'领取总价',
                                type:'line',
                                data:data.price_count
                            }
                        ]
                    };
                    material_chart.setOption(option);
                }
            );
        },
    });
});

function search_content () {
    var stationName = jq_filter_station.combobox('getValue');
    var date_start = jq_date_start.datebox('getValue');
    var date_end = jq_date_end.datebox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            stationName: stationName,
            date_start: date_start,
            date_end: date_end,
            merge_data_months : 1
        }
    });
}
</script>
<!-- start echarts -->
<script type="text/javascript">
require(
    [
        'echarts',
        'echarts/chart/line',
        'echarts/chart/bar'
    ],
    function (ec) {
        var material_chart = ec.init(document.getElementById('main_station'));

        var option = {
            title : {
                text: '<?php echo $month_range.'\n'.$stationName; ?>物资领取情况',
                subtext: ''
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['领取总价']
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    data : [<?php echo $month; ?>]
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'领取总价',
                    type:'line',
                    data:[<?php echo $price; ?>],
                }
            ]
        };
        material_chart.setOption(option);
        }
);
</script>