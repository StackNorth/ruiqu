<div id="main">
    <div id="head" class="layoutbox">
        <div id="vselect"></div>
    </div>
    <div id="content" class="layoutbox">
        <div id="info" class="am-g">
            <div class="am-u-sm-12 am-text-center am-text-default">
                <span>该月共有&nbsp;{{count}}个订单</span>
            </div>
        </div>
        <div id="vtable" style="margin-top: 2%;"></div>
    </div>
    <div id="footer"></div>
</div>
<script type="text/javascript">
/* sessionStorage设置 */
if (!sessionStorage.getItem('timelist_index_order')) {
    sessionStorage.setItem('timelist_index_order', '0');
    var timelist_index_order = 0;
} else {
    timelist_index_str = sessionStorage.getItem('timelist_index_order');
    var timelist_index_order = parseInt(timelist_index_str);
}

var username = <?php echo json_encode($username); ?>;
document.title = '我的订单-'+username;
/* 获取变量 */
var userid = <?php echo json_encode($userid); ?>;
var user = <?php echo json_encode($user); ?>;
var timelist = <?php echo json_encode($timelist); ?>;
var jq_vtable = $('#vtable');
var jq_vselect = $('#vselect');

jq_vselect.vselect({
    options: timelist,
    selected: timelist_index_order,
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
        sessionStorage.setItem('timelist_index_order', index);
    }
});

var v_info = new Vue({
    el: '#info',
    data: {
        count: 0
    }
});

/* vtable插件 */
jq_vtable.vtable({
    url: '/index.php?r=o2o/myOrder/list',
    pagination: true,
    rows: 30,
    sort: 'booking_time',
    query: {
        userid: userid,
        start: timelist[jq_vselect.getVIndex()]['value']
    },
    columns: [
        {key: 'booking_time_str', name: '预约时间'},
        {key: 'status_str', name: '状态'}
    ],
    beforeLoad: function() {
        $.vloading('open');
    },
    onSelect: function(index, row) {
        var order = row.id;
        if (order.length == 0) {
            $.valert('订单信息未录入');
        } else {
            window.location.href = '/index.php?r=o2o/myOrder/info&order='+order+'&user='+user;
        }
    },
    afterLoad: function(data) {
        $.vloading('close');
        v_info.count = data.count;
    }
});
</script>