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
                <div class="am-u-sm-4">预约时间</div>
                <div class="am-u-sm-8">{{info.booking_time_str}}</div>
            </div>
            <div class="am-g">
                <div class="am-u-sm-4">客户姓名</div>
                <div class="am-u-sm-8">{{info.address.name}}</div>
            </div>
            <div class="am-g">
                <div class="am-u-sm-4">地址</div>
                <div class="am-u-sm-8">{{info.address.city}}&nbsp;{{info.address.area}}&nbsp;{{info.address.poi.name}}&nbsp;{{info.address.detail}}</div>
            </div>
            <div class="am-g">
                <div class="am-u-sm-4">客户电话</div>
                <div class="am-u-sm-8"><a href="tel:{{info.address.mobile}}">{{info.address.mobile}}</a></div>
            </div>
            <br>
            <div class="am-g">
                <div class="am-u-sm-4">产品</div>
                <div class="am-u-sm-12">
                    <ul class="am-list am-list-static am-list-border">
                        <li v-for="product in info.products">
                            名称:&nbsp;{{info.products[0].product.name}}<br>
                            价格:&nbsp;{{info.products[0].product.price}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="am-g" v-if="info.append_orders.length > 0">
                <div class="am-u-sm-4">追加订单</div>
                <div class="am-u-sm-12">
                    <ul class="am-list am-list-static am-list-border">
                        <li v-for="append in info.append_orders">
                            <ul class="am-list am-list-static am-list-border">
                                <li v-for="product in append.products">
                                    服务:&nbsp;{{product.name}}<br>
                                    价格:&nbsp;{{product.price}}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
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

function getCommisionInfo(order, user) {
    if (order.length == 0 || user.length == 0) {
        $.valert('订单信息未录入');
        return false;
    }

    $.vloading('open');
    $.post(
        '/index.php?r=o2o/o2oApp/getOrderInfo',
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