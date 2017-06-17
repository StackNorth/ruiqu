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
                <span class="tb_label">目标用户</span>
                <input class="user_selector" id="user" placeholder="目标用户" style="width: 150px;" />
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
            <form id="search_by_time" method="post" action="/index.php?r=stockViewUser/all">
                    <p>
                        <input type="text" name="date_start" id="date_start_search" value="<?php echo date('Y-m-d', $date_start); ?>"/>
                        <input type="text" name="date_end" id="date_end_search" value="<?php echo date('Y-m-d', strtotime('-1 day', $date_end)); ?>"/>
                        <input type="submit" value="搜索" />
                    </p>
                    <p>
                        <span><a href="/index.php?r=stockViewUser/index" class="options">最近一周</a></span>
                        <span><a href="/index.php?r=stockViewUser/findByWeeks" class="options">最近一月</a></span>
                        <span><a href="/index.php?r=stockViewUser/findByMonths" class="options">最近半年</a></span>
                    </p>
            </form>
            <div id="main_station" style="height:450px;width:900px"></div>
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
var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var jq_content_form = $('#content_form');

var module_router = site_root + '/index.php?r=stockViewUser';

var jq_date_start = $('#date_start');
var jq_date_end = $('#date_end');

var jq_date_start_search = $('#date_start_search');
var jq_date_end_search = $('#date_end_search');

$(function(){
    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
        p_width = 520;
    }

    jq_date_start.datebox({});

    jq_date_end.datebox({});

    jq_date_start_search.datebox({});

    jq_date_end_search.datebox({});

    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_dg_content.datagrid({
        url: module_router + '/list',
        title: '用户物资领用统计',
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
        idField: 'object',
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
            {field:'objectName', title:'用户',width:30},
            {field:'operate_count', title:'领取次数', width:20},
            {field:'price_count', title:'总价格', width:30},
            {field:'object', title:'操作', width:30,
                formatter: function (value, row, index) {
                   var formatString = '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=stockViewUser&object='+row['object']+'\');">图表</a>';
                   formatString += '&nbsp<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=stock&s_user='+row['objectName']+'\');">详情</a>';
                   return formatString;
                }
            }
        ]],

        onSelect: function(index, row){

            var data = $.extend({}, row);

            jq_content_form.form('reset');
        },
    });
});

function search_content () {
    var s_user = $('#user').val();
    var date_start = jq_date_start.datebox('getValue');
    var date_end = jq_date_end.datebox('getValue');

    jq_dg_content.datagrid({
        pageNum: 1,
        queryParams: {
            s_user: s_user,
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
                data:[<?php echo $objectNames; ?>]
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
                    name:'目标用户',
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