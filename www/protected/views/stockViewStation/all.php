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
<div region="west" border="false" id="west_panel" style="width: 530px;">
    <table id="dg_content"></table>
    <div id="tb_content">
        <div class="tb_line">
            <p>
                <span class="tb_label">服务点</span>
                <input id="station" name="station_data" />
            </p>
            <p>
                <span class="tb_label">开始</span>
                <input type="text" id="date_start" value="<?php echo date('Y-m-d', $date_start); ?>" style="width: 100px;"/>
                <span class="tb_label">结束</span>
                <input type="text" id="date_end" value="<?php echo date('Y-m-d', strtotime('-1 day', $date_end)); ?>" style="width: 100px;"/>
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
            <!-- <input type="hidden" name="id" id="material_id" value='' /> -->
            <form id="search_by_time" method="post" action="/index.php?r=stockViewStation/all">
                <div>
                    <p>
                        <input type="text" name="date_start" id="date_start_search" value="<?php echo date('Y-m-d', $date_start); ?>"/>
                        <input type="text" name="date_end" id="date_end_search" value="<?php echo date('Y-m-d', strtotime('-1 day', $date_end)); ?>"/>
                        <input type="submit" value="搜索" />
                    </p>
                    <p>
                        <a class="options" href="/index.php?r=stockViewStation/index">最近一周</a>
                        <a class="options" href="/index.php?r=stockViewStation/findByWeeks">最近一月</a>
                        <a class="options" href="/index.php?r=stockViewStation/findByMonths">半年数据</a>
                    </p>
                </div>
            </form>
            <form id="content_form" method="post">
                <div id="main_station" style="height:450px;width:900px"></div>
            </form>
        </div>
    </div>
</div>
</div>

</div>
</div>
</div>
<!-- 引入自动填充选择插件 -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/css/jquery.coolautosuggest.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/selector.js"></script>
<!-- 用户选择插件引入结束 -->
<!-- 引入echarts插件 -->
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<!-- 引入结束 -->
<script type="text/javascript">
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

var jq_date_start_search = $('#date_start_search');
var jq_date_end_search = $('#date_end_search');

var station_data = <?php echo json_encode($station_data); ?>;

$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }

    jq_date_start.datebox({});

    jq_date_end.datebox({});

    jq_date_start_search.datebox({});

    jq_date_end_search.datebox({});

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
        title: '库存操作记录',
        width: 520,
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
        frozenColumns:[],
        queryParams: $.extend(
            get_param_obj(),
            {
                date_start : jq_date_start.datebox('getValue'),
                date_end : jq_date_end.datebox('getValue')
            }
        ),
        columns:[[
            {field:'id', title:'id', hidden:true},
            {field:'stationName', title:'服务点',width:30},
            {field:'operate_count', title:'领取次数', width:20},
            {field:'price_count', title:'总价格', width:30},
            {field:'station', title:'操作', width:30,
                formatter: function (value, row, index) {
                   return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=stock&station='+row['station']+'\');">详情</a>';
                }
            }
        ]],

        onSelect: function(index, row){

            var data = $.extend({}, row);
            
            $('#on_loading').show();

            jq_content_form.form('reset');
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
            date_end: date_end
        }
    });
}
</script>
<!-- start echarts -->
<script type="text/javascript">
require.config({
    paths : {
        echarts: 'http://echarts.baidu.com/build/dist'
    }
});

require(
    [
        'echarts',
        'echarts/chart/pie',
        'echarts/chart/funnel'
    ],
    function (ec) {
        var material_chart = ec.init(document.getElementById('main_station'));

        option = {
            title : {
                text: '<?php echo $date_range; ?>领用物资总价分布',
                subtext: '',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                y : 'bottom',
                x : 'left',
                data:[<?php echo $stationNames; ?>]
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
                    name:'服务点',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                    <?php foreach ($price_count_arr as $key => $value) { ?>
                        {
                            value:<?php echo $value; ?>,
                            name:"<?php echo $key; ?>"
                        },
                    <?php } ?>
                    ]
                }
            ]
        };material_chart.setOption(option);
    }
);
</script>