<div>
    <div id="header">
        <div class="am-g">
            <div class="am-u-sm-12">
                <div class="layoutbox">
                    订单详情
                </div>
            </div>
        </div>
    </div>
    <!-- 订单详情 -->
    <div id="info">
        <div class="am-popup-bd" id="info_content" style="display: none;">
            <div class="am-g">
                <div class="am-u-sm-4">追加时间</div>
                <div class="am-u-sm-8">{{info.append_time_str}}</div>
            </div>
            <div class="am-g">
                <div class="am-u-sm-4">产品</div>
                <div class="am-u-sm-12">
                    <ul class="am-list am-list-static am-list-border">
                        <li v-for="product in info.products">
                            名称:&nbsp;{{product.name}}<br>
                            价格:&nbsp;{{product.price}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="am-g">
                <button type="button" class="am-btn am-btn-warning am-radius" style="width:100%;"
                onclick="getOrderInfo();">查看被追加的订单</button>
                <input type="hidden" id="order" :value="info.order" />
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
document.title = '我的提成-详情';
var order = <?php echo json_encode($order); ?>;
var user = <?php echo json_encode($user); ?>;

var v_order = new Vue({
    el: '#info',
    data: {
        info: []
    }
});

$(function() {
    getCommisionInfo(order, user);
});

function getOrderInfo(order) {
    var orderID = $('#order').val();
    window.location.href = '/index.php?r=o2o/myCommision/info&order='+orderID+'&user='+user+'&type='+0;
}

function getCommisionInfo(order, user) {
    if (order.length == 0 || user.length == 0) {
        $.valert('订单信息未录入');
        return false;
    }

    $.vloading('open');
    $.post(
        '/index.php?r=o2o/o2oApp/getAppendInfo',
        {
            id: order,
            user: user
        },
        function(res) {
            $.vloading('close');
            var data = $.parseJSON(res);
            if (data.success == false) {
                $.valert(data.msg);
            } else {
                v_order.info = data.content;
                $('#info_content').show();
            }
        }
    );
}
</script>