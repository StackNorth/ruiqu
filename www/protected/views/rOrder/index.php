<style>
    .f_label {width: 90px;}
    .accordion-body {padding: 0;}
    #view_select_position {
        display:inline-block;
        padding:1px 4px 1px 4px;
        border:1px solid #999999;
        text-decoration:none;
        color:#333333;
    }
</style>

<div id="main">
    <div region="west" border="false" id="west_panel">
        <table id="dg_content"></table>
        <div id="tb_content">
            <div class="tb_line">
                <input id="ss" />
                <span class="tb_label">服务</span>
                <input id="filter_type" style="width:100px"/>
            </div>
            <div class="tb_line">
                <span class="tb_label">状态: </span>
                <input id="filter_status" />
                <span class="tb_label">来源: </span>
                <input id="filter_channel" />
            </div>
            <div class="tb_line">
                <span class="tb_label">下单</span>
                <span class="tb_label">开始</span>
                <input type="text" id="date_start_order" style="width:100px;" />
                <span class="tb_label">结束</span>
                <input type="text" id="date_end_order" style="width:100px;" />
            </div>
            <div class="right">
                <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
            </div>
            <div class="tb_line">
                <span class="tb_label">预约</span>
                <span class="tb_label">开始</span>
                <input type="text" id="date_start_book" style="width:100px;"/>
                <span class="tb_label">结束</span>
                <input type="text" id="date_end_book" style="width:100px;"/>
            </div>
            <div style="margin: 3px 2px;padding:5px;border: 1px solid #95B8E7;">
                <a href="#" class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_content();return false;">新增订单</a>
            </div>
        </div>
    </div>
    <div id="acc_container" class="accordion" region="center">
        <div region="center" title="订单信息" data-options="iconCls:'icon-save',selected:true">
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
                                            <input type="hidden" name="id" id="order_id" value='' />
                                            <span id="id_str"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>订购的服务: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span  id="product_info" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>数量: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="counts" name="counts" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>使用的代金券: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span  id="coupon_info" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>地址/联系方式: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span  id="address_contact" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>支付方式: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="pay_channel" name="pay_channel" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>charge_id: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="charge_id" name="charge_id" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>预约时间: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="booking_time" type="text" >
                                            <input type="hidden" name="booking_time" id="booking_time_str"  />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>订单处理时间: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="deal_time" type="text" >
                                            <input type="hidden" name="deal_time" id="deal_time_str"  />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>接单时间: </span>
                                        </div>
                                        <div class="box_flex f_content">

                                            <input  name="take_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>出发时间: </span>
                                        </div>
                                        <div class="box_flex f_content">

                                            <input  name="set_out_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>到达时间: </span>
                                        </div>
                                        <div class="box_flex f_content">

                                            <input  name="arrive_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>完成时间: </span>
                                        </div>
                                        <div class="box_flex f_content">

                                            <input  name="finish_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>订单取消时间: </span>
                                        </div>
                                        <div class="box_flex f_content">

                                            <input  name="cancel_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>订单申请退款时间: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input  name="apply_refund_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item" style="display: none;">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>订单退款时间: </span>
                                        </div>
                                        <div class="box_flex f_content">

                                            <input  name="refund_time_str" style="width: 250px;" readonly="readonly" />
                                        </div>
                                    </div>
                                </li>

                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>保洁师选择: </span>
                                        </div>

                                        <div class="box_flex f_content" id="tech">
                                            <div id="extra_add_info">

                                            </div>
                                            <input name="technician_name" style="width: 250px;" id="setTechnician_content"/>
                                            <input name="technician" type="hidden" id="technician_id" value="0" />
                                            <input name="tech_nums" id="tech_nums" type="hidden" value="0"/>
                                            <a class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="add_extra();return false;">添加保洁师</a><br/>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>客户签字: </span>
                                        </div>
                                        <div class="box_flex f_content" id="content_avatar">
                                            <div id="tech_show"></div>
                                            <!--<div>
                                                <a href="#" iconCls="icon-add" id="content_avatar_uploader" class="easyui-linkbutton" plain="true">
                                                    上传图片
                                                </a>
                                            </div>
                                            <input type="hidden" name="avatar" id="content_avatar_info" />-->
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>备注: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="memo"  name="memo" style="width: 250px;"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>后台备注: </span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <textarea name="remark" style="width: 250px;min-height: 200px"></textarea>
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                            <span>状态:</span>
                                        </div>
                                        <div class="box_flex f_content">
                                            <input id="setStatus" name="status" />
                                        </div>
                                    </div>
                                </li>
                                <li class="f_item">
                                    <div class="box">
                                        <div class="f_label">
                                        </div>
                                        <div class="box_flex f_content">
                                            <span id="action_info" style="color:green;"></span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                    <div data-options="region:'south'" class="detail_south">
                        <div class="detail_toolbar">
                            <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="save_content();return false;">保存</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div region="center" title="重新指派" data-options="iconCls:'icon-save'" style="overflow:auto;padding:10px;">
            <div class="easyui-layout detail_layout">
                <div data-options="region:'center'" class="detail_center">
                    <div class="detail_main">
                        <span id="resetTechTip">该订单不支持重新分配保洁师</span>
                        <div id="resetTechDiv" style="display:none;">
                            <form id="resetTec_form" method="post">
                                <ul>
                                    <li class="f_item">
                                        <div class="box">
                                            <div class="f_label">
                                                <span>选择保洁师: </span>
                                            </div>

                                            <div class="box_flex f_content" id="reset_tech">
                                                <div id="reset_extra_add_info">

                                                </div>

                                                <input name="id" type="hidden" />
                                                <input id="resetTec" name="reset_technician" type="hidden" value="0"/>
                                                <input id="resetTecName" name="reset_technician_name" style="width:150px;"/>
                                                <input id="reset_tech_nums" name="nums" value="0" type="hidden" />
                                                <a class='easyui-linkbutton' plain="true" iconCls="icon-add" onclick="reset_add_extra();return false;">添加保洁师</a><br/>
                                                <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="reset_technician();return false;">保存</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div data-options="region:'south'" class="detail_south">
                <div class="detail_toolbar">
                    <a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="reset_technician();return false;">保存</a>
                </div>
            </div> -->
        </div>
    </div>
</div>
<div style="display: none;">
    <div id="add_dialog" style="padding: 15px 0;">
        <form id="add_form" method="post">
            <ul>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>手机号: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="mobile_add" name="mobile" style="width: 250px;"  />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>姓名: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="name_add" name="name" style="width: 250px;"  />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>坐标: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input type="text" id="view_latitude" name="latitude" style="width: 80px;" readonly/>
                            <input type="text" id="view_longitude" name="longitude" style="width: 80px;" readonly/>
                            <a href="javascript:void();" id="view_select_position">选择地址</a>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>地址: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="add_province" name="province" type="hidden" />
                            <input id="add_city" name="city" type="hidden"/>
                            <input id="add_area" name="area" type="hidden" />
                            <input id="poi_name" name="poi_name" type="hidden" />
                            <input id="poi_uid" name="poi_uid" type="hidden" />
                            <span id="re_address"></span>
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>补充地址: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="detail_add" name="detail" placeholder="例如：1号楼406室" style="width: 250px;" />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>订购的服务: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="main_products" name="main_products" />
                            <br>
                            <div id="extra_items"></div>
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>订单类型: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="set_type" name="type" />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>购买数量: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="set_count" name="counts"  placeholder="1" />
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>订单来源: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="setChannels_add" name="channel" />
                        </div>
                    </div>
                </li>


                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>下单时间: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="order_time_add" name="order_time_add" type="text" >
                        </div>
                    </div>
                </li>
                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>预约时间: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="booking_time_add" name="booking_time_add" type="text" >
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>订单金额: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="price_add" name="price" style="width: 250px;"  />
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>折扣后金额: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="final_price_add" name="final_price" style="width: 250px;"  />
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>状态: </span>
                        </div>
                        <div class="box_flex f_content">
                            <input id="setStatus_add" name="status" />
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>用户备注: </span>
                        </div>
                        <div class="box_flex f_content">
                            <textarea name="memo" style="width: 250px;min-height: 100px"></textarea>
                        </div>
                    </div>
                </li>

                <li class="f_item">
                    <div class="box">
                        <div class="f_label">
                            <span>后台备注: </span>
                        </div>
                        <div class="box_flex f_content">
                            <textarea name="remark" style="width: 250px;min-height: 100px"></textarea>
                        </div>
                    </div>
                </li>

            </ul>
        </form>
    </div>

</div>

<div style="display:none;">
    <div id="refund_tip_dialog" style="padding: 30px 0;">
        <div style="text-align:center;"><span id="refund_tip"></span></div>
    </div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript">
    var jq_dg_content = $('#dg_content');
    var jq_content_form = $('#content_form');
    var jq_filter_status = $('#filter_status');
    var jq_setStatus_add = $('#setStatus_add');
    var jq_setChannels_add = $('#setChannels_add');
    var jq_filter_channel = $('#filter_channel');
    var jq_filter_station = $('#filter_station');
    var module_router = site_root + '/index.php?r=ROrder';
    var status_data = <?php echo json_encode($status); ?>;
    var channel_data = <?php echo json_encode($channels); ?>;
    var main_products = <?php echo json_encode($main_products); ?>;
    var type = <?php echo json_encode($type); ?>;
    var station_data = <?php echo json_encode($station); ?>;

    var jq_action_info = $('#action_info');
    var jq_setStatus = $('#setStatus');
    var w_width = $(window).width();
    var w_height = $(window).height();
    var jq_ss = $('#ss');
    var jq_filter_type = $('#filter_type');

    var jq_add_dialog = $('#add_dialog');
    var jq_add_form = $('#add_form');

    var jq_date_start_order = $('#date_start_order');
    var jq_date_end_order = $('#date_end_order');
    var jq_date_start_book = $('#date_start_book');
    var jq_date_end_book = $('#date_end_book');

    var jq_set_precedence = $('#set_precedence');
    var jq_set_station  = $('#set_station');
    var jq_set_type = $('#set_type');

    var jq_acc = $('#acc_container');
    var jq_resetTec_form = $('#resetTec_form');

    // 申请退款订单提示
    var jq_refund_tip_dialog = $('#refund_tip_dialog');
    var jq_edit_extra_dialog = $('#edit_extra_dialog');
    $(function(){

        jq_acc.accordion({
            height: w_height - 18,
            onSelect: function(title) {

            }
        });

        jq_date_start_order.datebox({});
        jq_date_end_order.datebox({});

        jq_date_start_book.datebox({});
        jq_date_end_book.datebox({});

        jq_filter_type.combobox({
            editable : false,
            data : type,
            onSelect : function () {
                search_content();
            }
        })

        $('#mobile_add').coolautosuggest({
            url:"index.php?r=rOrder/GetUserInfo&mobile=",
            showThumbnail:false,
            showDescription:true,
            onSelected:function(result){
                if(result!=null){
                    var data = $.extend({}, result.content);
                    jq_add_form.form('load', data);
                    $('#re_address').html(result.poi_name);

                    var poi_name = result.poi_name;
                    if (!poi_name) {
                        // 反向地址查询
                        console.log('开始查询' + data.longitude + ' ' + data.latitude);
                        var geoForPoi = new BMap.Geocoder();
                        geoForPoi.getLocation(new BMap.Point(data.longitude, data.latitude),
                            function (result) {
                                if (result) {
                                    console.log(result);
                                    var addressComponents = result.addressComponents;
                                    var street = addressComponents.street;
                                    var streetNumber = addressComponents.streetNumber;

                                    $('#poi_name').val(street + streetNumber);
                                    $('#re_address').html(street + streetNumber);
                                }
                            }
                        );
                    }
                } else{
                    return false;
                }
            }
        });

        $('#view_select_position').click(function(){
            $.fn.position_selector('init',{
                width:$(window).width()-300,//弹框显示宽度
                height:$(window).height()-100,//弹框显示高度
                zoom:18,  //缩放级别
                locat:'上海',//默认城市
                can_edit:true,
                lat:$('#view_latitude').val(),
                lng:$('#view_longitude').val(),
                //选择成功之后的回调函数
                func_callback:function(e){
                    // console.log($('#suggestId').val());
                    // console.log(e);

                    var longitude = e[0];
                    var latitude = e[1];
                    var addressComponents;6
                    // var re_address = $('#suggestId').val();  // 参考地址

                    // 反向地理编码获取省市及区划信息
                    var geo = new BMap.Geocoder();
                    geo.getLocation(new BMap.Point(longitude, latitude),
                        function (result) {
                            if (result) {
                                console.log(result);
                                var addressComponents = result.addressComponents;

                                var province = addressComponents.province;
                                var city = addressComponents.city;
                                var area = addressComponents.district;
                                var street = addressComponents.street;
                                var streetNumber = addressComponents.streetNumber;

                                $('#add_province').val(province);
                                $('#add_city').val(city);
                                $('#add_area').val(area);
                                $('#poi_name').val(street + streetNumber);
                                $('#re_address').html(street + streetNumber);
                            }
                        }
                    );

                    $('#view_latitude').val(latitude);
                    $('#view_longitude').val(longitude);
                    $('#re_address').val(re_address);
                    return false;
                },
                element_id:'map_container'//弹窗ID
            });return false;
        });

        var buttons = $.extend([], $.fn.datebox.defaults.buttons);
        buttons[0].text = '确定';
        buttons[0].handler=function(){
            var vals = $('#booking_time').datetimebox('spinner').spinner('getValue').split(':');
            $('#booking_time_str').val(  parseInt($('#booking_time_str').val())-(parseInt($('#booking_time_str').val())+8*3600)%86400 +vals[0]*3600+vals[1]*60);

            var d=new Date(parseInt($('#booking_time_str').val())*1000);

            $('#booking_time').datetimebox('setText',formatDate(d));
            $('#booking_time').datetimebox('hidePanel');
        };

        $('#booking_time').datetimebox({
            required: false,
            showSeconds:false,
            buttons:buttons,
            onSelect: function(date){
                var currentDate = new Date();
                //console.log(date)
                // if(currentDate>=date){
                //     $.messager.show({
                //         title: '提示',
                //         msg: '必须选择现在之后的日期',
                //         timeout: 3500,
                //         showType: 'slide'
                //     });
                // }else{
                $('#booking_time_str').val(date.getTime()/1000);
                // }
            }
        });

        $('#booking_time_add').datetimebox({
            required: false,
            showSeconds:false,
            //buttons:buttons_add,
            onSelect: function(date){
                var currentDate = new Date();
                $('#booking_time_str_add').val(date.getTime()/1000);
            }
        });

        $('#order_time_add').datetimebox({
            required: false,
            showSeconds:false,
            //buttons:buttons_add,
            // onSelect: function(date){
            //     var currentDate = new Date();
            //     $('#order_time_str_add').val(date.getTime()/1000);
            // }
        });

        $('#deal_time').datetimebox({
            required: false,
            showSeconds:false,
            //buttons:buttons_deal,
            // onSelect: function(date){
            //     console.log(date);
            //     $('#deal_time_str').val(date.getTime()/1000);
            // }
        });

        $('#main_products').combobox({
            editable: false,
            data: (function () {
                var main_products_temp = new Array();
                $.extend(main_products_temp, main_products)
                main_products_temp.shift();

                return main_products_temp;
            })(),
            onSelect: function(rec){
                $.ajax({
                    type: "GET",
                    url: "index.php?r=product/get",
                    data: {id:rec.value},
                    dataType: "json",
                    success: function(data){
                        //console.log(data)
                        var _html = "";
                        for (i in data.extra){
                            console.log(JSON.stringify(data.extra[i]));
                            _html += '<input type="radio" name="extra" value=\''+JSON.stringify(data.extra[i])+'\' />'+data.extra[i]['type']+'--'+data.extra[i]['price']+'元<br />';
                        }

                        $('#extra_items').html(_html);

                    }
                });
                console.log(rec.value);
            }
        });

        jq_setStatus.combobox({
            editable: false,
            data: status_data
        });


        jq_setStatus_add.combobox({
            editable: false,
            data: (function () {
                var status_data_temp = new Array();
                $.extend(status_data_temp, status_data);
                status_data_temp.shift();

                return status_data_temp;
            })()
        });

        jq_setChannels_add.combobox({
            editable: false,
            data: (function () {
                var channel_data_temp = new Array();
                $.extend(channel_data_temp, channel_data);
                channel_data_temp.shift();

                return channel_data_temp;
            })()
        });



        jq_set_station.combobox({
            editable : false,
            data : (function () {
                var station_data_temp = new Array();
                $.extend(station_data_temp, station_data);
                station_data_temp.shift();

                return station_data_temp;
            })()
        });

        jq_set_type.combobox({
            editable : false,
            data : (function () {
                var type_temp = new Array();
                $.extend(type_temp, type);
                type_temp.shift();

                return type_temp;
            })()
        });
        var p_width = parseInt(w_width / 2);
        if (p_width < 520){
            p_width = 520;
        }
        var d_width = p_width - 10;
        $('#west_panel').css({width : p_width});
        $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

        jq_ss.searchbox({
            width: 130,
            searcher:function(value){
                search_content();
            },
            prompt: '请输入关键字'
        });

        jq_setStatus.combobox({
            editable: false,
            data: status_data
        });


        jq_add_dialog.dialog({
            title: '新建订单',
            width: 500,
            height: 500,
            closed: true,
            modal: true,
            buttons:[{
                text: '确认',
                iconCls: 'icon-ok',
                handler: function(){
                    // ------ 数据完整性检查 ------
                    var check = checkAddForm();
                    if (!check) {
                        return false;
                    } else {
                        $.messager.progress();
                        jq_add_form.submit();
                    }
                }
            },{
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function(){
                    jq_add_dialog.dialog('close');
                }
            }],
            onOpen:function(){
                jq_add_form.form('clear');
                jq_add_form.form('load', {});
                jq_set_precedence.combobox('setValue', 0);
                $('#extra_items').html('');

                $('#re_address').html('');
            }
        });

        jq_dg_content.datagrid({
            url: module_router + '/list',
            title: '订单列表',
            width: d_width,
            height: w_height - 18,
            fitColumns: true,
            autoRowHeight: true,
            striped: true,
            toolbar: '#tb_content',
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            rowStyler: function(index,row){
                if (row.precedence && row.status==1){
                    return 'color:red;';
                }else if(row.status==-3){
                    return 'color:green;';
                }
            },
            pagination: true,
            pageList: [20, 30, 50],
            pageSize: 20,
            nowrap: false,
            idField: 'id',
            sortName: 'order_time',
            sortOrder: 'desc',
            queryParams: get_param_obj(),
            frozenColumns:[[
                {field:'ck',checkbox:true}
            ]],
            columns:[[
                {field:'id', title:'id', hidden:true},
                {field:'type', title:'服务', width:50,
                    formatter: function(value, row){
                        if (value <= 6) {
                            return type[value].text;
                        } else {
                            return type[value-1].text;
                        }
                    }
                },
                {field:'user', title:'用户', width:50,
                    formatter: function(value, row){
                        var username = value.user_name;
                        if(value.otherPlatform=='1'){
                            return username;
                        }else{
                            return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rUser&id='+value.id+'\');">'+ username +'</a>';
                        }

                    }
                },
                {field:'order_time', title:'下单时间', width:70,sortable:true,formatter: function(value, row){
                    var now=new Date(value*1000);

                    var   month=now.getMonth()+1;
                    var   date=now.getDate();
                    var   hour = now.getHours();
                    return   month+"月"+date+"日"+hour+":00";
                }
                },
                {field:'order_count', title:'总单', width:30,sortable:false,
                    formatter: function(value, row){
                        if(value){
                            return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rOrder&have_pay=1&search='+row.address.mobile+'\');">'+ value +'</a>';
                        }else{
                            return value;
                        }

                    }
                },

                {field:'booking_time', title:'预约时间', width:60,sortable:true,formatter: function(value, row){
                    var now=new Date(value*1000);

                    var   month=now.getMonth()+1;
                    var   date=now.getDate();
                    var   hour = now.getHours();
                    return   month+"-"+date+" "+hour+":00";
                }},
                {field:'technicians', title:'保洁师', width:60,sortable:true,
                    formatter: function(value, row){
                        if (value == '') {
                            return "暂未选择保洁师";
                        } else {
                            var output = '';
                            for(var i in value){
                                output += value[i]['technician_name']+'&nbsp';
                            }
                            return output;
                        }
                        /*var output = '';
                        for(var i in row){
                            output += value[i]['technician']+'&nbsp';
                        }
                        return output;*/
                    }
                },
                {field:'af_sum_price', title:'总额', width:25,sortable:false},
                {field:'sum_price', title:'折后', width:25,sortable:false},
                {field:'status', title:'状态', width:40, sortable: true,
                    formatter: function(value, row){
                        return get_filed_text(value, status_data);
                    }
                },
                {field:'counts', title:'数量', width:20,sortable:false},
                {field:'score', title:'评价',width:20,
                    formatter:function(value, row) {
                        if (value == 100) {
                            return '无';
                        } else {
                            return '<a href="javascript:;" onclick="parent.load_url(\'<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=comment&id='+row.commentId+'\');">'+ value +'</a>';
                        }
                    }
                }
            ]],

            onSelect: function(index, row){
                $('#technician_id').val(0);
                $('#setTechnician_content').removeAttr('readonly');

                var data = $.extend({}, row);
                jq_content_form.form('load', data);
                jq_resetTec_form.form('clear');
                jq_resetTec_form.form('load', data);

                //如果technician_name存在则选择框为readonly
                tec_name = $('#setTechnician_content').val();
                if (tec_name.length > 0) {
                    $('#setTechnician_content').attr('readonly', 'true');
                }
                if(data.signUrl){
                    $('#tech_show').html('<img src="'+data.signUrl+'" style="max-width: 260px" />');
                }

                // 如果technician_name存在且订单状态为1-4则显示重新分配
                console.log(data.technician_name);
                if (data.technician_name != '' && data.status >= 1 && data.status <= 3) {
                    $('#resetTechTip').hide();
                    $('#resetTechDiv').show();
                } else {
                    $('#resetTechTip').show();
                    $('#resetTechDiv').hide();
                }

                $('#admins_edit_info').html('');
                var technicians = data['technicians'];
                var tmp = '';
                var nums = 0;
                for (var j in technicians) {
                    nums += 1;
                    tmp +='<div id="extra_add_info_'+technicians[j]['technician_id']+'" style="height:4px">'+technicians[j]['technician_name'];
                    /*tmp += '<a href="" onclick="delete_extra('+technicians[j]['technician_id']+');return false;">删除</a></div><br/>';*/
                    tmp += '<input type="hidden" id="extra_add_info_hidden_'+nums+'" name="extra_add_info_'+nums+'" value='+technicians[j]['technician_name']+'/>';
                    tmp += '<input type="hidden" id="extra_add_info_id_hidden_'+nums+'" name="extra_add_info_id_'+nums+'" value='+technicians[j]['technician_id']+'/></div><br/>';

                }
                $('#tech_nums').val(nums);

                var address_contact = '省:'+data['address']['province']+'<br />'+'市:'+data['address']['city']+'<br />'+'区:'+data['address']['area']+'<br />'+'地址:'+data['address']['poi']['name']+' '+data['address']['detail']+'<br />'+'姓名:'+data['address']['name']+'<br />'+'手机号:'+data['address']['mobile']+'<br />'+'<a href="javascript:void();" lat='+data['address']['position'][1]+' lng='+data['address']['position'][0]+' id="view_position">查看坐标</a>';
                $('#address_contact').html(address_contact);
                $('#extra_add_info').html(tmp);
                var products = data['products'];
                var product_info = '';

                for(var j in products){
                    if(j == products.length-1){
                        if(products[j].count>1){
                            product_info += products[j]['product'].name+"x"+products[j]['product'].count;
                        }else{
                            product_info += products[j]['product'].name;
                        }
                        if(!$.isEmptyObject(products[j]['extra'])){
                            product_info += '('+products[j]['extra'].type+'--'+products[j]['extra'].price+')';
                        }

                    }else{
                        if(products[j].count>1) {
                            product_info += products[j]['product'].name + "x" + products[j]['product'].count + " + ";
                        }else{
                            product_info += products[j]['product'].name + " + ";
                        }
                        if(!$.isEmptyObject(products[j].extra)){
                            product_info += '('+products[j]['extra'].type+'--'+products[j]['extra'].price+')';
                        }
                    }
                }



                var coupons = data['coupons'];
                var coupon_info = '';


                $('#booking_time').datetimebox('setValue', parse_time(data,'booking_time'));
                $('#deal_time').datetimebox('setValue', parse_time(data,'deal_time'));
                $('#product_info').html(product_info);

                $('#coupon_info').html(coupon_info);

                $('#view_position').on('click',function(){
                    console.log($(this).attr('lat'));
                    $.fn.position_selector('init',{
                        width:$(window).width()-300,//弹框显示宽度
                        height:$(window).height()-100,//弹框显示高度
                        zoom:18,  //缩放级别
                        locat:'上海',//默认城市
                        can_edit:true,
                        lat:$(this).attr('lat'),
                        lng:$(this).attr('lng'),
                        func_callback:function(){return false;},//选择成功之后的回调函数
                        element_id:'map_container'//弹窗ID
                    });return false;
                });

                for(var j in coupons){
                    console.log(coupons[j]['coupon']);
                    var value = coupons[j]['coupon']['name']+'(满'+coupons[j]['coupon']['min_price']+'免'+coupons[j]['coupon']['value']+')'+'('+coupons[j]['coupon']['alias_name']+')';

                    if(j == coupons.length-1){
                        coupon_info += value;
                    }else{
                        coupon_info += value+'+';
                    }
                }

                $('#booking_time').datetimebox('setValue', parse_time(data,'booking_time'));
                $('#deal_time').datetimebox('setValue', parse_time(data,'deal_time'));
                $('#product_info').html(product_info);
                $('#coupon_info').html(coupon_info);

                $('#view_position').on('click',function(){
                    console.log($(this).attr('lat'));
                    $.fn.position_selector('init',{
                        width:$(window).width()-300,//弹框显示宽度
                        height:$(window).height()-100,//弹框显示高度
                        zoom:18,  //缩放级别
                        locat:'上海',//默认城市
                        can_edit:true,
                        lat:$(this).attr('lat'),
                        lng:$(this).attr('lng'),
                        func:function(){return false;},//选择成功之后的回调函数
                        element_id:'map_container'//弹窗ID
                    });return false;
                });

                if (data['action_user'] != ''){
                    jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
                } else {
                    jq_action_info.html('');
                }

                $("#on_loading").show();
                $('#pay_channel').html(data.pay_channel);
                $('#counts').html(data.counts);
                $('#charge_id').html(data.charge_id);
                $('#memo').html(data.memo);
                $('#id_str').html(data.id);

            },

            onLoadSuccess: function(){
                $('#resetTechTip').hide();
                $('#resetTechDiv').hide();
                $('#setTechnician_content').removeAttr('readonly');
                $(this).datagrid('clearChecked');
                $('#address_contact').html('');
                $('#product_info').html('');
                $('#coupon_info').html('');
                jq_content_form.form('clear');
                jq_resetTec_form.form('clear');
                $('#id_str').html('');
                $('#counts').html('');
                $('#booking_time_str').val('');
                $('#deal_time_str').val('');
                jq_action_info.html('');
                jq_dg_content.datagrid('clearSelections');
                jq_setStatus.combobox('setValue', 100);
            }
        });

        jq_ss.searchbox({
            width: 140,
            searcher:function(value){
                search_content();
            },
            prompt: '请输入关键字'
        });

        jq_filter_status.combobox({
            width: 100,
            data: status_data,
            editable: false,
            onSelect: function(){
                search_content();
            }
        });

        jq_filter_channel.combobox({
            width: 100,
            data: channel_data,
            editable: false,
            onSelect: function(){
                search_content();
            }
        });

        jq_filter_station.combobox({
            width: 100,
            data: station_data,
            editable: false,
            onSelect: function () {
                search_content();
            }
        });

        // ------ content form ------
        $('#setStation_content').combobox({
            width: 250,
            data: (function(){
                var station_data_temp = new Array();
                $.extend(station_data_temp, station_data);
                station_data_temp.shift();

                return station_data_temp;
            })(),
            editable: false
        });

        jq_content_form.form({
            url: module_router + '/edit',
            onSubmit: function(param){
                if ($('#order_id').val() == ""){
                    return false;
                }
                var isValid = $(this).form('validate');
                if (!isValid){
                    $.messager.progress('close');
                }
                return isValid;
            },
            success: function(res){
                $.messager.progress('close');
                var res = JSON.parse(res);

                if (res.success){
                    jq_dg_content.datagrid('reload');
                }
                if(res.success){
                    $.messager.show({
                        title: '提示',
                        msg: '保存成功',
                        timeout: 3500,
                        showType: 'slide'
                    });

                    $('#technician_id').val(0);
                }else{
                    $.messager.show({
                        title: '提示',
                        msg: res.message,
                        timeout: 3500,
                        showType: 'slide'
                    });
                }
            }
        });

        jq_add_form.form({
            url : module_router + '/add',
            onSubmit : function (param) {
                var isValid = $(this).form('validate');
                if (!isValid){
                    $.messager.progress('close');
                }
                return isValid;
            },
            success : function (res) {
                $.messager.progress('close');
                var res = JSON.parse(res);

                if (res.success) {
                    $.messager.show({
                        title : '提示',
                        msg : '保存成功',
                        timeout : 3500,
                        showType : 'slide'
                    });
                    jq_add_dialog.dialog('close');
                    jq_dg_content.datagrid('reload');
                } else {
                    $.messager.show({
                        title : '提示',
                        msg : res.message,
                        timeout : 3500,
                        showType : 'slide'
                    });
                }
            }
        });

        jq_resetTec_form.form({
            url: module_router + '/resetTech',
            onSubmit: function(param) {
                var isValid = $(this).form('validate');
                if (!isValid){
                    $.messager.progress('close');
                }
                return isValid;
            },
            success: function(res) {
                $.messager.progress('close');
                var res = JSON.parse(res);

                if (res.success) {
                    $.messager.show({
                        title : '提示',
                        msg : '保存成功',
                        timeout : 3500,
                        showType : 'slide'
                    });
                    jq_dg_content.datagrid('reload');
                } else {
                    $.messager.show({
                        title : '提示',
                        msg : res.message,
                        timeout : 3500,
                        showType : 'slide'
                    });
                }
            }
        });

        // 自动填充
        var setTechnician_content = $('#setTechnician_content');
        setTechnician_content.coolautosuggest({
            url: 'index.php?r=o2o/tech/selectTech&tech=',
            showDescription: true,
            onSelected:function(result){
                $('#technician_id').val(result.tech_id);
                // console.log(result);
            }
        });

        var reset_tech_selector = $('#resetTecName');
        reset_tech_selector.coolautosuggest({
            url: '/index.php?r=o2o/tech/selectTech&tech=',
            showDescription: true,
            onSelected:function(result){
                $('#resetTec').val(result.tech_id);
            }
        });

        /* 每5分钟请求一次接口获取申请退款订单 */
        jq_refund_tip_dialog.dialog({
            title: '提示',
            width: 300,
            height: 150,
            closed: true,
            modal: true,
            buttons:[{
                text: '处理退款订单',
                iconCls: 'icon-ok',
                handler: function(){
                    url = '<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=rOrder&status=-3';
                    parent.load_url(url);
                    jq_refund_tip_dialog.dialog('close');
                }
            },{
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function(){
                    jq_refund_tip_dialog.dialog('close');
                }
            }],
            onOpen:function(){

            }
        });

        function checkRefundOrder() {
            $.post(
                module_router + '/checkRefundOrder',
                {},
                function(res) {
                    res = $.parseJSON(res);
                    if(res.code == 2){
                        // $content = '<a href="javascript:;" onclick="process_append_order(\''+res.procession_append_order_id+'\');">待处理追加订单</a>';
                        console.log(res);
                        $.messager.defaults.ok = '去处理';
                        $.messager.defaults.cancel = '取消';
                        $.messager.confirm('提示','有追加订单待处理',function(r){
                            if (r){
                                process_append_order(res.procession_append_order_id);
                            }else{
                                cancel_process_append_order(res.procession_append_order_id);
                            }
                        });
                    }else if (res.code == 1) {
                        $content = '共有<span style="color:red">'+res.count+'</span>个<span style="color: green;">申请退款</span>的订单未处理';
                        $('#refund_tip').html($content);
                        jq_refund_tip_dialog.dialog('open');
                    }else {
                        return false;
                    }
                }
            );
        }

        checkRefundOrder();
        setInterval(checkRefundOrder, 600000);
    });

    function search_content(){
        var filter_status = jq_filter_status.combobox('getValue');
        var filter_channel = jq_filter_channel.combobox('getValue');
        var date_start_order = jq_date_start_order.datebox('getValue');
        var date_end_order = jq_date_end_order.datebox('getValue');
        var date_start_book = jq_date_start_book.datebox('getValue');
        var date_end_book = jq_date_end_book.datebox('getValue');
        // var station = jq_filter_station.combobox('getValue');
        var type = jq_filter_type.combobox('getValue');

        var search = jq_ss.searchbox('getValue');
        jq_dg_content.datagrid({
            pageNum: 1,
            queryParams: {
                search : search,
                status : filter_status,
                channel : filter_channel,
                date_start_order : date_start_order,
                date_end_order : date_end_order,
                date_start_book : date_start_book,
                date_end_book : date_end_book,
                // station : station,
                type : type
            }
        });
    };

    function save_content(){
        if ($('#order_id').val() == ""){
            return false;
        }

        if (jq_setStatus.combobox('getValue') <0 ){
            $.messager.confirm('注意', '确认 取消/退款 该订单吗？', function(r){
                $.messager.progress();
                jq_content_form.submit();
            });
            return true;
        }

        if (jq_setStatus.combobox('getValue') == 6) {
            tec_name = $('#setTechnician_content').val();
            tec_id   = $('#technician_id').val();
            if (tec_name.length == 0 && tec_id.length == 0) {
                $.messager.alert('提示', '完成订单前请指定保洁师');
                return false;
            }
        }

        $.messager.progress();
        jq_content_form.submit();
    }

    function parse_time(data,attr){
        if(data[attr]){
            var d=new Date(data[attr]*1000);
            return formatDate(d);
        }else{
            return '';
        }
    }

    function   formatDate(now){
        var   year=now.getFullYear();
        var   month=now.getMonth()+1;
        var   date=now.getDate();
        var  hour = now.getHours();
        var  minute = now.getMinutes();
        return   year+"-"+month+"-"+date+" "+hour+":"+minute;
    }

    function add_content(){
        jq_add_dialog.dialog('open');
    }

    function reset_technician() {
        var id = jq_resetTec_form.find('input[name="id"]').val();
        if (id.length == 0) {
            $.messager.alert('提示', '请先选择一个订单');
            return false;
        }
        var name = jq_resetTec_form.find("input[id='reset_add']").val();
        var add = jq_resetTec_form.find('input[name="reset_technician_name"]').val();
        if (name == undefined) {
            $.messager.alert('提示', '请先选择一名保洁师');
            return false;
        }

        if (add.length != 0) {
            $.messager.alert('提示', '请点击添加保洁师');
            return false;
        }
        $.messager.progress();
        jq_resetTec_form.form('submit');
        $('#reset_extra_add_info').empty();
    }

    function checkAddForm () {
        // 正则检查
        // 手机号检查
        var mobile_regex = new RegExp(/^\d{8,11}$/);
        var mobile_text  = $('#mobile_add').val();
        if (!mobile_regex.test(mobile_text)) {
            $.messager.alert('提示', '手机号输入非法(如存在空格等)噢', 'warning');
            return false;
        }

        // 空数据检查
        if ($('#mobile_add').val() == '') {
            $.messager.alert('提示', '请填写手机号', 'warning');
            return false;
        }
        if ($('#name_add').val() == '') {
            $.messager.alert('提示', '请填写姓名', 'warning');
            return false;
        }
        if ($('#view_latitude').val() == '' || $('#view_longitude').val() == '') {
            $.messager.alert('提示', '请选择坐标', 'warning');
            return false;
        }
        if ($('#detail_add').val() == '') {
            $.messager.alert('提示', '请填写详细地址', 'warning');
            return false;
        }

        if ($('#set_type').combobox('getValue') == '') {
            $.messager.alert('提示', '请选择订单类型', 'warning');
            return false;
        }
        if ($('#setChannels_add').combobox('getValue') == '') {
            $.messager.alert('提示', '请选择订单来源', 'warning');
            return false;
        }
        if ($('#order_time_add').datebox('getValue') == '') {
            $.messager.alert('提示', '请选择订单时间', 'warning');
            return false;
        }
        if ($('#booking_time_add').datebox('getValue') == '') {
            $.messager.alert('提示', '请选择预约时间', 'warning');
            return false;
        }
        if ($('#price_add').val() == '') {
            $.messager.alert('提示', '请填写订单金额', 'warning');
            return false;
        }
        if ($('#final_price_add').val() == '') {
            $.messager.alert('提示', '请填写折扣后金额', 'warning');
            return false;
        }
        if ($('#setStatus_add').combobox('getValue') == '') {
            $.messager.alert('提示', '请选择订单状态', 'warning');
            return false;
        }

        if($('#extra_items').html().length && !$("input[name='extra']:checked").val()){
            $.messager.alert('提示', '服务详情没有选择', 'warning');
            return false;
        }



        return true;
    }
    function delete_extra(msg){
        $('#extra_add_info_'+msg).remove();
        $('#extra_add_info_hidden_'+msg).remove();
        $('#extra_add_info_id_hidden_'+msg).remove();
        var nums = Number($('#tech_nums').val());
        nums -= 1;
        $('#tech_nums').val(nums);
    }
    function add_extra(){
        var nums = Number($('#tech_nums').val());
        if ($('#setTechnician_content').val()){
            nums += 1;
            setTechnician_content = $('#setTechnician_content').val();
            setTechnician_id =  $('#technician_id').val();
            $output = "<div id='extra_add_info_"+nums+"' style='height: 4px'>"+setTechnician_content;
            $output +="<a href ='' onclick='delete_extra("+nums+");return false;'>删除</a></div><br/>";
            $output +="<input type='hidden' id='extra_add_info_hidden_"+nums+"' name='extra_add_info_"+nums+"' value='"+setTechnician_content+"' />";
            $output +="<input type='hidden' id='extra_add_info_id_hidden_"+nums+"' name='extra_add_info_id_"+nums+"' value='"+setTechnician_id+"' /></div>";

            $('#setTechnician_content').val('');

            $('#tech_nums').val(nums);
            $("#extra_add_info").append($output);
        }
    }
    function reset_delete_extra(msg){
        $('#reset_extra_add_info_'+msg).remove();
        $('#reset_extra_add_info_hidden_'+msg).remove();
        $('#reset_extra_add_info_id_hidden_'+msg).remove();
        $('#reset_add').remove();
        var nums = Number($('#reset_tech_nums').val());
        nums -= 1;
        $('#reset_tech_nums').val(nums);
    }
    function reset_add_extra(){
        var nums = Number($('#reset_tech_nums').val());
        if ($('#resetTec').val()) {
            nums += 1;
            resetTec = $('#resetTec').val();
            resetTecName = $('#resetTecName').val();

            $output = "<div id='reset_extra_add_info_"+nums+"' style='height: 4px'>"+resetTecName;
            $output +="<a href ='' onclick='reset_delete_extra("+nums+");return false;'>删除</a></div><br/>";
            $output +="<input type='hidden' id='reset_extra_add_info_hidden_"+nums+"' name='reset_extra_add_info_"+nums+"' value='"+resetTecName+"' />";
            $output += "<input type='hidden' id='reset_add' name='reset_add' value='1' />";
            $output +="<input type='hidden' id='reset_extra_add_info_id_hidden_"+nums+"' name='reset_extra_add_info_id_"+nums+"' value='"+resetTec+"' /></div>";
            $('#reset_tech_nums').val(nums);
            $("#reset_extra_add_info").append($output);
            $('#resetTecName').val('');
        }
    }
</script>