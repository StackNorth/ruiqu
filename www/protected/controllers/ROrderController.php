<?php
class ROrderController extends AdminController{

    public function actionIndex()
    {
        $status_option = ROrder::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $channel_option = ROrder::$channel_option;
        $channels = CommonFn::getComboboxData($channel_option, 100, true, 100);


        // 服务信息
        $criteria_main = new EMongoCriteria();
        $criteria_main->status('==', 1);
        $cursor_main = Product::model()->findAll($criteria_main);
        $rows_main = CommonFn::getRowsFromCursor($cursor_main);
        $main_row = array();
        foreach($rows_main as $row){
            $ids = (string)$row['_id'];
            $main_row[$ids] = array('name' =>$row['name']);
        }
        $main_products = CommonFn::getComboboxData($main_row, 100, true, 100);
        // 服务点信息
        $criteria_station = new EMongoCriteria();
        $cursor = Station::model()->findAll($criteria_station);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Station::model()->parse($rows);
        $station_data = array();
        foreach ($parsedRows as $key => $v) {
            $station_data = array_merge($station_data, array($v['id'] => array('name' => $v['name'])));
        }
        $station = CommonFn::getComboboxData($station_data, 'all', true, 'all');

        // 订单类型
        $type_data = Yii::app()->params['o2o_service'];
        $type = CommonFn::getComboboxData($type_data, 100, true, 100);

        $this->render('index', array(
            'status'       => $status,
            'main_products'=> $main_products,
            'channels'     => $channels,
            'station'      => $station,
            'type'         => $type
        ));
    }

    public function actionList(){
        $filter_status = intval(Yii::app()->request->getParam('status', 100));
        $search = Yii::app()->request->getParam('search', '');
        $id = Yii::app()->request->getParam('id', '');
        $date_start_order = Yii::app()->request->getParam('date_start_order');
        $date_end_order = Yii::app()->request->getParam('date_end_order');
        $date_start_book= Yii::app()->request->getParam('date_start_book');
        $date_end_book = Yii::app()->request->getParam('date_end_book');

        $type = Yii::app()->request->getParam('type', 100);

        $have_pay = Yii::app()->request->getParam('have_pay', 0);

        $channel = Yii::app()->request->getParam('channel', 100);

        //$station = Yii::app()->request->getParam('station', 'all');

        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);


        if ($filter_status != 100){
            $criteria->status('==', $filter_status);
        }

        if ($id != ''){
            $order_id = new MongoId($id);
            $criteria->_id('==', $order_id);
        }

        if ($channel != 100){
            $criteria->channel('==', $channel);
        }

        if ($type != 100) {
            $criteria->type = $type;
        }


        if ($search != ''){
            if(CommonFn::isMongoId($search)){
                //$criteria->_id('==', new MongoId($search));

                $criteria->addCond('_id','or',new MongoId($search));
                $criteria->addCond('user','or',new MongoId($search));
            }
            else if(preg_match('/\d{8,11}/', $search)){
                $criteria->addCond('address.mobile','==',$search);
                if($have_pay){
                    $criteria->addCond('status','>',0);
                }

            }else if(strlen($search) == 27){
                $criteria->charge_id('==', $search);
            }else{
                $criteria->addCond('address.name','==',$search);
            }
        }

        // 下单时间处理
        if (!empty($date_start_order) && !empty($date_end_order)) {
            // 开始时间处理
            $timestamp_start_order = strtotime($date_start_order);
            // 结束时间处理，需通过strototime()增加一天
            $timestamp_end_order = strtotime('+1 day', strtotime($date_end_order));

            $criteria->order_time('>=', $timestamp_start_order);
            $criteria->order_time('<=', $timestamp_end_order);
        }

        // 预约时间处理
        if (!empty($date_start_book) && !empty($date_end_book)) {
            // 开始时间处理
            $timestamp_start_book = strtotime($date_start_book);
            // 结束时间处理，需通过strototime()增加一天
            $timestamp_end_book = strtotime('+1 day', strtotime($date_end_book));

            $criteria->booking_time('>=', $timestamp_start_book);
            $criteria->booking_time('<=', $timestamp_end_book);
        }

        // 服务站处理
        // if ($station != 'all') {
        // $criteria->station = new MongoId($station);
        //}

        $cursor = ROrder::model()->findAll($criteria);

        $total = $cursor->count();

        $rows = CommonFn::getRowsFromCursor($cursor);

        $parsedRows = ROrder::model()->parse($rows);

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');

        $status = intval(Yii::app()->request->getParam('status',1));

        $counts = intval(Yii::app()->request->getParam('count',1));

        $booking_time = intval(Yii::app()->request->getParam('booking_time', time()));

        $deal_time = intval(Yii::app()->request->getParam('deal_time', time()));

        $remark = Yii::app()->request->getParam('remark', '');

        //$station = Yii::app()->request->getParam('station_id', '');
        $technician_ids = array();
        $technician_names = array();
        $technicians = array();
        $nums = Yii::app()->request->getParam('tech_nums');
        for($i=0;$i<=$nums;$i++) {
            if(Yii::app()->request->getParam('extra_add_info_'.$i)) {
                $technician_ids[] = Yii::app()->request->getParam('extra_add_info_id_'.$i);
                $technician_names[] = Yii::app()->request->getParam('extra_add_info_'.$i);
            }
        }

        if(!$counts){
            CommonFn::requestAjax(false, '购买数量错误');
        }

        // 保洁师信息检查
        // 根据ID直接查询保洁师信息(优先使用联想功能)
        if (isset($technician_ids)) {
            foreach($technician_ids as $key => $technician_id){
                $technician_obj = TechInfo::get($technician_id);
                if ($technician_obj) {
                    $technicians[$key]['technician_id'] = $technician_obj->_id;
                    $technicians[$key]['technician_name']  = $technician_obj->name;
                } else {
                    CommonFn::requestAjax(false, '保洁师不存在');
                    break;
                }
            }
            // ID为0时根据输入框信息查询
        } else if (isset($technician_names)) {
            $criteria = new EMongoCriteria();
            foreach($technician_names as $key => $technician_name) {
                $criteria->name = $technician_name;
                $technician_obj = TechInfo::model()->find($criteria);
                if ($technician_obj) {
                    $technicians[$key]['technician_id'] = $technician_obj->_id;
                    $technicians[$key]['technician_name'] = $technician_obj->name;
                } else {
                    CommonFn::requestAjax(false, '保洁师不存在');
                    break;
                }
            }
        } else {
            $technician_obj = null;
            //$technician_id = 0;
            // $technician_name = '';
            foreach($technicians as $key => $value) {
                $technicians[$key]['$technician_id'] = 0;
                $technicians[$key]['$technician_name'] = 0;
            }
        }
        //var_dump($technicians);exit;
        if($status == 100){
            CommonFn::requestAjax(false, '必须指定状态！');
        }

        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoId($id);
        $order = ROrder::model()->find($criteria);
        // if($order->status == -1 || $order->status == -2){
        //     CommonFn::requestAjax(false, '已取消,已退款订单不支持更改');
        // }
        // 获取用户信息，修改用户订单统计
        //     2015-11-16
        $user_id = $order->user;
        if (!empty($user_id)) {
            $user = RUser::get($user_id);
        } else {
            $user = '';
        }

        if (empty($order)){
            CommonFn::requestAjax(false, '订单不存在');
        }



        if(($order->status!=-1||$order->status!=-2)&&($status==-1||$status==-2)){
            foreach ($order->coupons as $user_coupon) {
                $user_coupon = UserCoupon::get($user_coupon);
                $user_coupon->status = 1;
                $user_coupon->update(array('status'),true);
            }
        }
        if($order->status!=-2 && $status == -2) {
            $order->refund_time = time();
            $order_info = $order->parseRow($order);
            $month = date('m', $order_info['booking_time']);
            $day = date('d', $order_info['booking_time']);
            $address = $order_info['address']['poi']['name'] . $order_info['address']['detail'];


            CommonSMS::send('order_retrieve', array('month' => $month, 'day' => $day, 'address' => $address, 'mobile' => $order_info['address']['mobile']));
            // 申请退款处理完成后通知保洁师
            foreach ($technician_ids as $key => $technician_id) {
                $technician_obj = TechInfo::get($technician_id);
                if ($technician_obj && $technician_obj->weixin_userid) {
                    $url_prefix = ENVIRONMENT == 'product' ? 'http://api.yiguanjia.me' : 'http://apitest.yiguanjia.me';
                    $wechat = O2oApp::getWechatActive();
                    $wechat_data = array(
                        'touser' => $technician_obj->weixin_userid,
                        'msgtype' => 'news',
                        'agentid' => '1',
                        'news' => array(
                            'articles' => array(
                                array(
                                    'title' => '壹橙管家提示-订单退款完成',
                                    'description' => $technician_obj->name . '你好！用户于' . date('m月d日H:i', $order->apply_refund_time) . '申请退款的订单已处理完成。',
                                    //'url' => $url_prefix . '/index.php?r=o2o/myOrder/info&order=' . $id . '&user=' . $technician_id,
                                    'url' => $url_prefix . '/index.php?r=o2o/myOrder/index'
                                ),
                            ),
                        ),
                    );

                    if (!empty($order->append_orders)) {
                        $count = count($order->append_orders);
                        $wechat_data['news']['articles'][0]['description'] .= "\n\n本订单包含" . $count . "个追加订单，请注意查看。";
                    }
                    $wechat->sendMessage($wechat_data);
                }
            }
        
        }

        //取消订单
        if($order->status!=-1 && $status == -1){
            // 已完成订单不能取消
            if ($order->status == 6) {
                CommonFn::requestAjax(false, '已完成订单不可取消');
            }

            $order->cancel_time = time();
            $order_info = $order->parseRow($order);
        }
        //确认接单
        if($order->status!=3 && $status == 3){

            $order_info = $order->parseRow($order);
        }
        //订单完成后执行
        if($order->status!=6 && $status == 6 ){
            $order->finish_time = time();

            $order_info = $order->parseRow($order);

            /*$result = Service::factory('ScoreService')->changeScore((string)$order->user,intval($order->final_price),'下单奖爪币');
            if($result){
                $z_message = new ZMessage();
                $from_user = Yii::app()->params['sys_user'];
                $message_data = array(
                    'from_user' => $from_user,
                    'to_user' => (string)$order->user,
                    'content' => '您成功下单，获得了'.intval($order->final_price).'个爪币的奖励。',
                    'pics' => array(),
                    'voice' => array(),
                    'video'=> array()
                );
                $z_message->addMessage($message_data);
                CommonSMS::send('final_order',array('name' =>$order_info['address']['name'],'num' =>intval($order->final_price),'mobile'=>$order_info['address']['mobile']));
            }*/


            // 保洁师订单统计处理
            foreach($technician_ids as $key => $technician_id) {
                $technician_obj = TechInfo::get($technician_id);
                if ($technician_obj) {
                    $tech_order_count = $technician_obj->order_count + 1;
                    $technician_obj->order_count = $tech_order_count;
                    $technician_obj->save();
                }
            }
        }


        $order->status = $status;
        $order->booking_time = $booking_time;
        $order->deal_time = $deal_time;
        $order->remark = $remark;
        $order->counts = $counts;

        //$order->station = new MongoId($station);
        // 是否通知保洁师

        $toTech = !empty($order->technicians)? true : false;
        //$order->technician = $technician_id;
        //$order->technician_name = $technician_name;
        $order->technicians = $technicians;

        //var_dump($order);exit;
        $arr_order = array('cancel_time','refund_time','finish_time','status','booking_time','deal_time','remark', 'station', 'technicians');
        $success = $order->save(true,$arr_order);
        $char = false;
        // 通知保洁师
        if (in_array($status, array(1,2,3,4,5)) && $toTech && $success) {

            foreach($technician_ids as $key => $technician_id) {

                $technician_obj = TechInfo::get($technician_id);

                if ($technician_obj && $technician_obj->weixin_userid) {
                    $url_prefix = ENVIRONMENT == 'product' ? 'http://api.yiguanjia.me' : 'http://apitest.yiguanjia.me';
                    $wechat = O2oApp::getWechatActive();
                    $wechat_data = array(
                        'touser' => $technician_obj->weixin_userid,
                        'msgtype' => 'news',
                        'agentid' => 1,
                        'news' => array(
                            'articles' => array(
                                array(
                                    'title' => '壹橙管家提示-新订单',
                                    'description' => $technician_obj->name . '你好！刚刚有一个新的订单被分配给你，请点击查看。',
                                    //'url' => $url_prefix . '/index.php?r=o2o/myOrder/info&order=' . $id . '&user=' . $technician_id,
                                    'url' => $url_prefix . '/index.php?r=o2o/myOrder/index'
                                ),
                            ),
                        ),
                    );
                    $char = $wechat->sendMessage($wechat_data);
                }
            }
        }
        CommonFn::requestAjax($success, '微信'.$char, array());
    }


    // 根据手机号  获取对应用户的地址信息
    public function actionGetUserInfo(){
        $mobile = Yii::app()->request->getParam('mobile', '');
        $length = strlen($mobile);
        if ($length < 11) {
            $result[] = array(
                'id' => 0,
                'data' => '',
                'description' => '请继续输入',
                'content' => array()
            );

            echo json_encode($result);exit;
        }

        // $mobile_regex = new MongoRegex($mobile);
        $criteria = new EMongoCriteria();
        $criteria->sort('order_time', EMongoCriteria::SORT_DESC);
        $criteria->addCond('address.mobile', '==', $mobile);

        $cursor = ROrder::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);

        if (empty($rows)) {
            $result[] = array(
                'id' => 0,
                'data' => '',
                'description' => '没有匹配信息',
                'content' => array()
            );

            echo json_encode($result);exit;
        }


        $result = array();
        $details = array();
        $index = 0;
        foreach($rows as $row){
            // 加入根据detail筛选，避免产生重复信息
            if (in_array($row['address']['detail'], $details)) continue;

            $details[] = $row['address']['detail'];
            $pio = isset($row['address']['poi']['name'])?$row['address']['poi']['name']:'';

            // 坐标获取（根据不同的来源）
            if ($row['channel'] == 'dianping') {
                $latitude = $row['address']['position']['lat'];
                $longitude = $row['address']['position']['lng'];
            } else {
                $latitude = isset($row['address']['position']['1'])?$row['address']['position']['1']:'';
                $longitude = isset($row['address']['position']['0'])?$row['address']['position']['0']:'';
            }

            // poi获取
            $poi_name = isset($row['address']['poi']['name']) ? $row['address']['poi']['name'] : '';
            $poi_uid   = isset($row['address']['poi']['uid']) ? $row['address']['poi']['uid'] : '';

            $result[] = array(
                'id'          => $index,
                'data'        => $row['address']['mobile'],
                'description' => $row['address']['province'].' '.$row['address']['city'].' '.$row['address']['area'].' '.$pio.$row['address']['detail'],
                'content'     => array(
                    'name'      => $row['address']['name'],
                    'latitude'  => $latitude,
                    'longitude' => $longitude,
                    'province'  => $row['address']['province'],
                    'city'      => $row['address']['city'],
                    'area'      => $row['address']['area'],
                    'detail'    => $row['address']['detail'],
                    'poi_name'  => $poi_name,
                    'poi_uid'   => $poi_uid,
                    'memo'      => $row['memo']
                )
            );

            $index++;
        }
        echo json_encode($result);exit;



    }

    public function actionAdd () {
        // ------ 必须传入的数值 ------
        // --------- 订单信息 ---------
        $channel       = Yii::app()->request->getParam('channel', '');
        $booking_time  = Yii::app()->request->getParam('booking_time_add', '');
        $order_time    = Yii::app()->request->getParam('order_time_add', '');
        $main_products = Yii::app()->request->getParam('main_products', '');
        $price         = floatval(Yii::app()->request->getParam('price', 0));
        $final_price   = floatval(Yii::app()->request->getParam('final_price', 0));
        $status        = Yii::app()->request->getParam('status', -3);
        $station       = Yii::app()->request->getParam('station', '58bd62ebce93ada5048b4578');
        // --------- 地址信息 ---------
        $mobile        = Yii::app()->request->getParam('mobile', 0);
        $latitude      = Yii::app()->request->getParam('latitude', 0);
        $longitude     = Yii::app()->request->getParam('longitude', 0);
        $province      = Yii::app()->request->getParam('province', '');
        $city          = Yii::app()->request->getParam('city', '');
        $area          = Yii::app()->request->getParam('area', '');
        $poi_name      = Yii::app()->request->getParam('poi_name', '');
        $detail        = Yii::app()->request->getParam('detail', '');

        $counts = intval(Yii::app()->request->getParam('counts', 1));
        $counts = $counts?$counts:1;
        $extra =  Yii::app()->request->getParam('extra','[]');
        $extra = json_decode($extra);

        // 数据完整性检查
        // 2015-11-02 因存在赠送订单，删除金额的数据检查 : $price == 0 || $final_price == 0 ||
        // 2015-11-16 取消服务点录入，删除服务点数据检查 :  || empty($station)
        $flag = empty($channel) || empty($booking_time) || empty($order_time);
        $flag = $flag || $status == -3;
        $flag = $flag || $mobile == 0 || $latitude == 0 || $longitude == 0;
        $flag = $flag || empty($province) || empty($city) || empty($area) || empty($poi_name) || empty($detail);
        if ($flag) {
            CommonFn::requestAjax(false, '请检查数据完整性', array());
            exit;
        }

        //if($channel == 'wz_app' || $channel == 'wx_pub'){
        //CommonFn::requestAjax(false, '不能录入渠道为壹橙管家微信||壹橙管家APP的订单', array());
        //exit;
        //}

        // 时间处理
        $booking_time = strtotime($booking_time);
        $order_time   = strtotime($order_time);

        // ------ 可以留空的数值 ------
        $box           = Yii::app()->request->getParam('box', array());
        $coupons       = Yii::app()->request->getParam('coupons', array());
        $memo          = Yii::app()->request->getParam('memo', '');
        $remark        = Yii::app()->request->getParam('remark', '');
        $precedence    = Yii::app()->request->getParam('precedence', 0);
        $have_comment  = Yii::app()->request->getParam('have_comment', 0);
        $name          = Yii::app()->request->getParam('name', '');
        $type          = Yii::app()->request->getParam('type', 0);
        $user          = Yii::app()->request->getParam('user', '');
        $poi_uid       = Yii::app()->request->getParam('poi_uid', '');

        // 用户名的判断
        $channel_option = ROrder::$channel_option;
        $name = empty($name) ? $channel_option[$channel]['name'].'用户' : $name;

        // 支付渠道
        $pay_channel = $channel;

        // 服务数据整合
        $products[] = array(
            'product' => new MongoId($main_products),
            'count'   => 1,
            'extra'     =>$extra
        );
        if (!empty($box)) {
            foreach ($box as $key => $value) {
                $products[] = array(
                    'product' => new MongoId($value),
                    'count'   => 1,
                    'extra'     =>$extra
                );
            }
        }

        // 地址数据整合
        $address = array(
            'province' => $province,
            'city'     => $city,
            'area'     => $area,
            'detail'   => $detail,
            'mobile'   => $mobile,
            'position' => array(
                // 'lat' => (float)$latitude,
                // 'lng' => (float)$longitude,
                0 => (float)$longitude,
                1 => (float)$latitude
            ),
            'poi'      => array(
                'name' => $poi_name,
                'uid'  => $poi_uid
            ),
            'name'     => $name
        );

        // 订单类型判断
        if ($type == 0) {
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($main_products);
            $cursor = Product::model()->find($criteria);
            $type = $cursor->type;
        }

        $rOrder = new ROrder();
        $rOrder->channel      = $channel;
        $rOrder->booking_time = intval($booking_time);
        $rOrder->order_time   = intval($order_time);
        $rOrder->products     = $products;
        $rOrder->price        = floatval($price);
        $rOrder->final_price  = floatval($final_price);
        $rOrder->precedence   = intval($precedence);
        $rOrder->counts      = $counts;
        $rOrder->coupons      = $coupons;
        $rOrder->user         = $user;
        $rOrder->status       = intval($status);
        $rOrder->memo         = $memo;
        $rOrder->remark       = $remark;
        $rOrder->type         = strval($type);          // 数据库内使用string类型
        $rOrder->have_comment = intval($have_comment);
        $rOrder->station      = new MongoId($station);
        $rOrder->address      = $address;
        $rOrder->pay_channel  = $pay_channel;

        $addROrder_arr = array('channel', 'booking_time', 'order_time', 'price','products', 'final_price', 'counts','precedence', 'coupons', 'user', 'status', 'memo', 'remark', 'type', 'have_comment', 'station', 'address', 'pay_channel');
        $success = $rOrder->save(true, $addROrder_arr);

        CommonFn::requestAjax($success, '', array());
    }

    /**
     * 请求申请退款订单接口
     */
    public function actionCheckRefundOrder() {
        $criteria = new EMongoCriteria();
        $criteria->status('==', -3);

        $cursor = ROrder::model()->findAll($criteria);

        $count = $cursor->count();
        if ($count > 0) {
            $data = array('code' => 1, 'count' => $count);
        } else {
            $data = array('code' => 0, 'count' => $count);
        }
        $list = new ARedisList('append_order_list');
        if($list->getCount() > 0){
            $key = $list->shift();
            $list->unshift($key);
            $data['procession_append_order_id'] = $key;
            $data['code'] = 2;
        }

        echo json_encode($data);
    }

    public function actionCancelProcess() {
        $orderid = Yii::app()->request->getParam('orderid','');
        $list = new ARedisList('append_order_list');
        $key = $list->shift();
    }
    /**
     * 重新选择保洁师接口
     */
    public function actionResetTech() {
        $id = Yii::app()->request->getParam('id', '');
        $nums = Yii::app()->request->getParam('nums', '');
        $technicians = array();
        $technician_ids = array();
        $technician_names = array();
        for($i=1;$i<=$nums;$i++) {
            if(Yii::app()->request->getParam('reset_extra_add_info_'.$i)) {
                $technician_ids[] = Yii::app()->request->getParam('reset_extra_add_info_id_'.$i);
                $technician_names[] =  Yii::app()->request->getParam('reset_extra_add_info_'.$i);

            }
        }
        // 保洁师信息检查
        // 根据ID直接查询保洁师信息(优先使用联想功能)
        $technician_objs = array();
        foreach($technician_ids as $key => $technician_id) {
            if ($technician_id != 0) {
                $technician_obj = TechInfo::get($technician_id);
                if ($technician_obj) {
                    $technicians[$key]['technician_id'] = $technician_obj->_id;
                    $technicians[$key]['technician_name']  = $technician_obj->name;

                } else {
                    CommonFn::requestAjax(false, '保洁师不存在');
                }
                // ID为0时根据输入框信息查询
            } else {
                foreach ($technician_names as $key => $technician_name)
                    if ($technician_name != '') {
                        $criteria = new EMongoCriteria();
                        $criteria->name = $technician_name;
                        $technician_obj = TechInfo::model()->find($criteria);
                        if ($technician_obj) {
                            $technicians[$key]['technician_id'] = $technician_obj->_id;
                            $technicians[$key]['technician_name']  = $technician_obj->name;
                        } else {
                            CommonFn::requestAjax(false, '保洁师不存在');
                        }
                    } else {
                        CommonFn::requestAjax(false, '保洁师姓名不能为空');
                    }

            }
            $technician_objs[] = $technician_obj;
        }

        $orderid = new MongoId($id);
        $order = ROrder::model()->get($orderid);
        $toTech = isset($order->technicians)  ? true : false;
        $fromTechs = $technician_names;
        //$order->technician = $technician_id;
        //$order->technician_name = $technician_name;
        $order->technicians = $technicians;
        $success = $order->save(true, array('technicians'));

        if ($toTech && $success) {
            foreach ($technician_objs as $technician_obj) {
                // 发送给被分配保洁师
                $wechat = O2oApp::getWechatActive();
                $url_prefix = ENVIRONMENT == 'product' ? 'http://api.yiguanjia.me' : 'http://apitest.yiguanjia.me';
                if (!empty($technician_obj->weixin_userid)) {
                    $wechat_data = array(
                        'touser' => $technician_obj->weixin_userid,
                        'msgtype' => 'news',
                        'agentid' => '1',
                        'news' => array(
                            'articles' => array(
                                array(
                                    'title' => '壹橙管家提示-新订单',
                                    'description' => $technician_obj->name . '你好！刚刚有一个新的订单被分配给你，请点击查看。',
                                    //'url' => $url_prefix . '/index.php?r=o2o/myOrder/info&order=' . $id . '&user=' . $technician_obj->_id,
                                    'url' => $url_prefix . '/index.php?r=o2o/myOrder/index'
                                ),
                            ),
                        ),
                    );
                    $wechat->sendMessage($wechat_data);
                }
            }

            // 发送给原保洁师
            foreach ($fromTechs as $fromTech) {
                $fromTechObj = TechInfo::get($fromTech);
                if (!empty($fromTechObj) && !empty($fromTechObj->weixin_userid)) {
                    $wechat_data = array(
                        'touser' => $fromTechObj->weixin_userid,
                        'msgtype' => 'news',
                        'agentid' => '1',
                        'news' => array(
                            'articles' => array(
                                array(
                                    'title' => '壹橙管家提示-订单已被重新分配',
                                    'description' => $fromTechObj->name . '你好！预定时间在' . date('m月d日H:i', $order->booking_time) . '的订单已被分配给其他保洁师。',
                                    'url' => $url_prefix . '/index.php?r=o2o/myOrder/info&order=' . $id . '&user=' . $technician_obj->_id,
                                ),
                            ),
                        ),
                    );
                    $wechat->sendMessage($wechat_data);
                }
            }
        }
        CommonFn::requestAjax($success, '', array());
    }

    public function actionOutputExcel(){
        $data = ROrder::model()->findAll();   //
        $rows = CommonFn::getRowsFromCursor($data);
        $data = ROrder::model()->parse($rows);
        $name='OrderList';    //生成的Excel文件文件名
        $res=Service::factory('ExcelToArrayService')->push($data,$name);
        echo "导出成功";
    }



}
