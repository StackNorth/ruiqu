<div id="main">
    <div id="head" class="layoutbox">
        <div id="vselect"></div>
    </div>
    <div id="content" class="layoutbox" style="display: none;">
        <div id="info" class="am-g">
            <div class="am-u-sm-12 am-text-center am-text-default">
                <span>该月共有&nbsp;{{count}}条评价</span>
            </div>
        </div>
        <div id="vtable" style="margin-top: 2%;"></div>
    </div>
    <div id="footer"></div>
</div>
<script type="text/javascript">
/* sessionStorage设置 */
if (!sessionStorage.getItem('timelist_index_comment')) {
    sessionStorage.setItem('timelist_index_comment', '0');
    var timelist_index_comment = 0;
} else {
    timelist_index_str = sessionStorage.getItem('timelist_index_comment');
    var timelist_index_comment = parseInt(timelist_index_str);
}

var username = <?php echo json_encode($username); ?>;
document.title = '我的评价-'+username;
/* 获取变量 */
var userid = <?php echo json_encode($userid); ?>;
var user = <?php echo json_encode($user); ?>;
var timelist = <?php echo json_encode($timelist); ?>;
var jq_vtable = $('#vtable');
var jq_vselect = $('#vselect');

jq_vselect.vselect({
    options: timelist,
    selected: timelist_index_comment,
    onSelect: function(value, index) {
        // 筛选列表
        jq_vtable.vtable({
            page: 1,
            query: {
                userid: userid,
                start: value
            }
        });
        // sessionStorage设置
        sessionStorage.setItem('timelist_index_comment', index);
    }
});

/* vtable插件 */
jq_vtable.vtable({
    url: '/index.php?r=o2o/myComment/list',
    pagination: true,
    rows: 30,
    sort: 'time',
    query: {
        userid: userid,
        start: timelist[jq_vselect.getVIndex()]['value']
    },
    columns: [
        {key: 'time_str_short', name: '时间'},
        {key: 'score', name: '评分'}
    ],
    beforeLoad: function() {
        $.vloading('open');
    },
    onSelect: function(index, row) {
        if (row.order == '') {
            $.valert('订单信息未录入');
        }
        window.location.href = '/index.php?r=o2o/myComment/info&order='+row.order+'&user='+user;
    },
    afterLoad: function(data) {
        $.vloading('close');
        v_info.count = data.count;
        $('#content').show();
    }
});

/* 基本信息 */
var v_info = new Vue({
    el: '#info',
    data: {
        count: 0
    }
});
</script>