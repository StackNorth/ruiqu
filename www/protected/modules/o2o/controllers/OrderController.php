<?php
/**
 * OrderController o2o下单相关接口
 */
class  OrderController extends O2oBaseController{

    public function actionAdd(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $products = json_decode(Yii::app()->getRequest()->getParam("products","[]"),true);
        $memo = Yii::app()->getRequest()->getParam("memo","");
        $booking_time = Yii::app()->getRequest()->getParam("booking_time",'');
        $precedence = intval(Yii::app()->getRequest()->getParam("precedence",0));
        $coupons = json_decode(Yii::app()->getRequest()->getParam("coupons","[]"),true);
        $address_id = Yii::app()->getRequest()->getParam("address_id");
        $tech_id = Yii::app()->getRequest()->getParam("tech_id");
        $station = Yii::app()->getRequest()->getParam("station");
        $order_channel = Yii::app()->getRequest()->getParam("order_channel");

        $counts = Yii::app()->request->getParam('counts', 1);
        $extra = json_decode(Yii::app()->getRequest()->getParam("extra","[]"),true);

        $balance = floatval(Yii::app()->getRequest()->getParam("balance",0));//余额支付的金额

        //CommonFn::requestAjax(false,'系统升级中，暂时不能下单');

        if(!$precedence){
            $booking_check  = strtotime($booking_time);
            $verity_check = $booking_check - time();
            //if($verity_check < 13800){
                //CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
            //}
        }

        //活动暂用  start 判断用户是否购买过  test //583247cca84ea01d428b46a1 master//  58324b7e9f5160a4048b5758
        $doubleEleventId = '58324b7e9f5160a4048b5758';
        //$flag_book = 0;

        if($products[0]['product_id'] == $doubleEleventId) {

            $criteria_user = new EMongoCriteria();
            $criteria_user->user('==', new MongoId($user_id));
            $criteria_user->addCond('products.product', '==', new MongoId($doubleEleventId));//双十一产品id
            //$criteria_user->addCond('status','==',1);
            $order = ROrder::model()->findAll($criteria_user);
            $o = CommonFn::getRowsFromCursor($order);

            foreach ($o as $value) {
                if ($value['status'] == 0 || $value['status'] == -1) {
                    $t = ROrder::model()->get(new MongoId($value['_id']));
                    $t->delete();
                } else {
                    CommonFn::requestAjax(false, '对不起，日常保洁两小时体验只能购买一单，你已经抢购过');
                }
            }
          /*  //取出双十一活动暂用
            $criteria_time = new EMongoCriteria();
            $criteria_time->addCond('products.product', '==', new MongoId($doubleEleventId));
            $cursor_time = ROrder::model()->findAll($criteria_time);
            $rows = array();
            if(!empty($cursor_time)) {
                $rows = CommonFn::getRowsFromCursor($cursor_time);
            }
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $day_start = mktime(0, 0, 0, $m, $d, $y);
            $day_end = mktime(23, 59, 59, $m, $d, $y);
            $total = 0;
            if ($rows) {
                foreach ($rows as $orders) {
                    //判断订单是否达到111单
                    if ($total >= 111) {
                        CommonFn::requestAjax(false, '今天的双11订单已经抢购光了');

                    }

                    //判断是否是当天订单
                    if ($orders['order_time'] >= $day_start && $orders['order_time'] <= $day_end) {
                        $total += 1;
                    }
                }
            }*/

            //$flag_book = 1;
        }

        //end  //取出双十一活动暂用


        if(!$user_id||!$products||!$address_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = CommonFn::apigetObJ($user_id,'ZUser',CommonFn::getMessage('user','id_not_exist'),array(),201);

        if($balance){
            if($user_obj->balance < $balance){
                CommonFn::requestAjax(false,'余额不足哦');
            }
        }

        $address_list = $user_obj->shop_address?$user_obj->shop_address:array();
        $address = array();
        foreach ($address_list as $value) {
            if($address_id == $value['address_id']){
                $address = $value;
            }
        }

        if(!$address){
            file_put_contents('/data/erroraddressc.log',var_export($address,true),FILE_APPEND);
            CommonFn::requestAjax(false,CommonFn::getMessage('o2o','address_false'));
        }else{
            if($station){
                $station = Station::get(new MongoId($station));
                if(!$station){
                    file_put_contents('/data/erroraddressa.log',var_export($address,true),FILE_APPEND);
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','address_cannot_service'));
                }
                $divide_station = $station->_id;
            }else{
                file_put_contents('/data/erroraddressb.log',var_export($address,true),FILE_APPEND);
                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','address_cannot_service'));
            }
            $o2o_address['name'] = $address['name'];
            $o2o_address['mobile'] = $address['mobile'];
            $o2o_address['province'] = $address['address']['province'];
            $o2o_address['city'] = $address['address']['city'];
            $o2o_address['area'] = $address['address']['area'];
            $o2o_address['detail'] = $address['address']['detail'];
            $o2o_address['position'] = isset($address['position'])?$address['position']:array(0,0);
            $o2o_address['poi'] = isset($address['address']['poi'])?$address['address']['poi']:array();
        }
        $product_list = array();
        $price = 0.0;
        $service_type = 0;
        foreach ($products as $product) {
            if(isset($product['product_id']) && $product['count'] >= 1){
                $product_temp = array();
                $product_obj = Product::get(new MongoId($product['product_id']));
                if($product_obj){
                    $price += ($product_obj->price*$product['count']);
                    $price += $extra[0]['price']*$product['count'];

                    $product_temp['product'] = $product_obj->_id;
                    $product_temp['count'] = $product['count'];
                    $product_temp['extra'] = $extra[0];
                    $product_list[] = $product_temp;
                    if($product_obj->is_extra == 0){
                        $service_type = $product_obj->type;
                    }
                }else{
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_not_exist'));
                }
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_illegal'));
            }
        }
        if($precedence){
            $price += 40;
        }
        $final_price = $price;
        $used_coupon = array();
        foreach ($coupons as $coupon){
            $user_coupon = UserCoupon::get(new MongoId($coupon));
            $current_time = time();
            if($user_coupon && (string)$user_coupon->user == $user_id && $user_coupon->start_time < $current_time && $user_coupon->end_time > $current_time && $user_coupon->status == 1){
                $coupon = Coupon::get($user_coupon->coupon);
                //判断优惠券可用时间
                $weekend_check = (date('w',strtotime($booking_time))==0 || date('w',strtotime($booking_time))==6)?1:0;
                $booking_hour = date('H',strtotime($booking_time));
                if($weekend_check){
                    if($coupon->workday_limit==1){
                        CommonFn::requestAjax(false,CommonFn::getMessage('o2o','coupon_unuseable'));
                    }
                }else{
                    if($coupon->workday_limit==2){
                        CommonFn::requestAjax(false,CommonFn::getMessage('o2o','coupon_unuseable'));
                    }
                }
                if($coupon->time_limit_start && $coupon->time_limit_end && ($coupon->time_limit_start > $booking_hour || $coupon->time_limit_end < $booking_hour)){
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','coupon_unuseable'));
                }

                $new_user_coupons = Yii::app()->params['new_user_coupons'];
                if(($coupon->type == 0 || $coupon->type == $service_type ) && $coupon->status==1 && $coupon->min_price <= $final_price){
                    $final_price = $final_price-$coupon->value>=0?$final_price-$coupon->value:0; 
                    $used_coupon[] = $user_coupon->_id; 
                    $user_coupon->status = -1;
                    $user_coupon->use_time = time();
                    $user_coupon->update(array('status','use_time'),true);
                }else{
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','coupon_unuseable'));
                }
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','coupon_unuseable'));
            }
        }
        $order = new ROrder();
        $order->order_time = time();
        $order->booking_time = strtotime($booking_time);
        $order->products = $product_list;
        $order->precedence = $precedence;
        $order->price = $price;
        $order->channel = $order_channel;
        $order->counts = $counts;
        $order->final_price = $final_price;
        $order->pay_price = $order->final_price-$balance;
        $order->address = $o2o_address;
        $order->memo = $memo;
        $order->station = $divide_station;
        $order->coupons = $used_coupon;
        if(isset($user_coupon->coupon)){
            $order->coupon_type = $user_coupon->coupon;
        }


        if($order->pay_price == 0){
            $user_obj->balance = $user_obj->balance-$balance;
            $user_obj->save();

            $balance_log = new BalanceLog();
            $balance_log->time = time();
            $balance_log->user =  $user_obj->_id;
            $balance_log->memo = '微信下订单';
            $balance_log->type = 'order';
            $balance_log->amount = $balance;
            $balance_log->save(true);

            if($order->final_price>0){
                $order->pay_channel = 'balance';
            }
            $order->status = 1;
            //start 长期订暂用
            if($order->status == 1 ) {
                if($products[0]['product_id'] == '5835423fa84ea0ac7a8b4568') {
                    $u_id = new MongoId($user_id);
                    $start_time = date_create("2016-11-11")->format('U');//发放优惠券可用开始时间 2016.11.14
                    $end_time = date_create("2016-12-15")->format('U');   //发放优惠券过期时间 17天

                    if ($extra[0]['type'] == "3张（2小时/人）"){
                        for( $i=0;$i<3;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("5835527fa84ea02e758b45b7"), $start_time, $end_time);//发放代金券
                        }
                    } else if ($extra[0]['type'] == "3张（3小时/人）"){
                        for( $i=0;$i<3;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("58356a2aa84ea03b018b46bb"), $start_time, $end_time);//发放代金券
                        }
                    } elseif ($extra[0]['type'] == "6张（2小时/人）"){
                        for( $i=0;$i<6;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("5835527fa84ea02e758b45b7"), $start_time, $end_time);//发放代金券
                        }
                    } else{

                        for( $i=0;$i<6;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("58356a2aa84ea03b018b46bb"), $start_time, $end_time);//发放代金券
                        }
                    }
                }
            }
            //end暂用
            //修改订单状态
            CommonFn::sendOrderSms($order,(string)$order->_id);
        }else{
            $order->status = 0;
        }
        $order->user = $user_obj->_id;
        $order->type = $service_type;
        //$order->technician = intval($tech_id);
        if($order->save()){
            $data = ROrder::model()->parseRow($order);

            /*foreach ($products as $product) {
                if(isset($product['product_id']) && $product['count'] >= 1){
                    $product_obj = Product::get(new MongoId($product['product_id']));
                    if($product_obj){
                        $product_obj->sale_count+=$product['count'];
                        $product_obj->update(array('sale_count'),true);
                    }
                }
            }*/
            CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
        }
    }

    public function actionList(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $type = Yii::app()->getRequest()->getParam("type");
        $order_type = Yii::app()->getRequest()->getParam("order_type");
        if(!$order_type){
            $order_type = $type;
        }
        $user_obj = CommonFn::apigetObJ($user_id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
        $page = intval(Yii::app()->getRequest()->getParam("page",1));
        $pagesize = Yii::app()->params['ROrderListPageSize'];
        if($order_type == 1 ){
            $conditions = array(
                                'user'=>array('==',$user_obj->_id),
                                'status'=>array('notin',array(-3,-2,-1,6,7))
                            );
        }elseif ($order_type == 2) {
            $conditions = array(
                                'user'=>array('==',$user_obj->_id),
                                'status'=>array('in',array(-3,-2,-1,7))
                            );
        }elseif($order_type == 3){
            $conditions = array(
                                'user'=>array('==',$user_obj->_id),
                                'status'=>array('==',6)
                            );
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $order = array(
                        'booking_time'=>'desc'
                        );
        $model = new ROrder();
        $pagedata = CommonFn::getPagedata($model,$page,$pagesize,$conditions,$order);

        $order_list = $pagedata['res'];
        foreach ($order_list as $key => $value) {
            $order_list[$key] = $model->output($value);
        }
        $data = array_values($order_list);
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,200,array('sum_count' => $pagedata['sum_count'],'sum_page'=>$pagedata['sum_page'],'page_size'=>$pagedata['page_size'],'current_page'=>$pagedata['current_page']));
    }

    public function actionPay(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $pay_channel = Yii::app()->getRequest()->getParam("pay_channel");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        if(!$user_id || !$pay_channel || !$order_id || !CommonFn::isMongoId($order_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order->charge_id){
            CommonFn::requestAjax(false,'此订单已支付过',array('have_pay'=>1));
        }
        foreach ($order->products as $value) {
            if(isset($value['product'])){
                $product = Product::get($value['product']);
            }
            if($product){
                break;
            }
        }
        if(isset($product)&&$product){
            $str = isset(Yii::app()->params['o2o_service'][$product->type]['name'])?Yii::app()->params['o2o_service'][$product->type]['name']:'';
        }
        $str = isset($str)&&$str?$str:'上门';
        $amount = ceil($order->pay_price*1000)/10;

        $result = Service::factory('PayService')->Pay($pay_channel,$amount,(string)$order->_id,$str,$str,$user_obj->wx_pub_openid);
        if($result === false){
            CommonFn::requestAjax(false,'支付遇到点问题了，请稍候再试');
        }else{
            // 支付成功后用户有效订单数增加
            if (isset($user_obj->order_count)) {
                $user_obj->order_count += 1;
                //支付成功 判断是否是长期定 start
                if($order->products[0]['product'] == '5835423fa84ea0ac7a8b4568') {
                    $u_id = new MongoId($user_id);
                    $start_time = date_create("2016-11-11")->format('U');//发放优惠券可用开始时间 2016.11.14
                    $end_time = date_create("2016-12-15")->format('U');   //发放优惠券过期时间 17天

                    if ($order->products[0]['extra']['type']['type'] == "3张（2小时/人）"){
                        for( $i=0;$i<3;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("5835527fa84ea02e758b45b7"), $start_time, $end_time);//发放代金券
                        }
                    } else if ($order->products[0]['extra']['type']['type'] == "3张（3小时/人）"){
                        for( $i=0;$i<3;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("58356a2aa84ea03b018b46bb"), $start_time, $end_time);//发放代金券
                        }
                    } elseif ($order->products[0]['extra']['type']['type'] == "6张（2小时/人）"){
                        for( $i=0;$i<6;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("5835527fa84ea02e758b45b7"), $start_time, $end_time);//发放代金券
                        }
                    } else{

                        for( $i=0;$i<6;$i++) {
                            Service::factory('CouponService')->giveCoupon($u_id, new MongoId("58356a2aa84ea03b018b46bb"), $start_time, $end_time);//发放代金券
                        }
                    }
                }
                // 长期定end
            } else {
                $user_obj->order_count = 1;
            }

            //支付成功
            $user_obj->save();
            CommonFn::requestAjax(true,'success',json_decode($result),200,array('booking_time'=>$order->booking_time));
        }
    }

    public function actionDel(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        if(!$user_id || !$order_id || !CommonFn::isMongoId($order_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        $status = -1;
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order->status != 0){
            CommonFn::requestAjax(false,'此订单暂不支持取消');
        }
        if( $order->status!=-1 && $order->status!=-2 ){
            foreach ($order->coupons as $user_coupon) {
                $user_coupon = UserCoupon::get($user_coupon);
                $user_coupon->status = 1;
                $user_coupon->update(array('status'),true);
            }
        }

        $order->status = $status;
        $arr_order = array('status');
        $success = $order->update($arr_order,true);
        if($success){
            $order_info = $order->parseRow($order);
        }
        CommonFn::requestAjax($success, '');
       
    }

    public function actionUsableCoupon(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $get_all = Yii::app()->getRequest()->getParam("get_all");
        $type = Yii::app()->getRequest()->getParam("type");
        $extra = json_decode(Yii::app()->getRequest()->getParam("extra","[]"),true);
        $booking_time = Yii::app()->getRequest()->getParam("booking_time");
        if($booking_time){
            $booking_time = strtotime($booking_time);
            $weekend_check = (date('w',$booking_time)==0||date('w',$booking_time)==6)?1:0;
            $booking_hour = date('H',$booking_time);
        }
        $device_id = Yii::app()->getRequest()->getParam("device_id",'');
        $products = json_decode(Yii::app()->getRequest()->getParam("products",'[]'),true);
        if(!CommonFn::isMongoId($user_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        
        $UserCoupon = new UserCoupon();
        if($get_all){
            $criteria = new EMongoCriteria();
            $criteria->user('==',new MongoId($user_id));
            $current_time = time();
            $criteria->end_time('>=',$current_time);
            $criteria->status('==',1);

            $coupons = UserCoupon::model()->findAll($criteria);
            $coupon_list = array(); 
            $coupon_list['useable_coupons'] = array(); 
            foreach ($coupons as $value) {
                $coupon = Coupon::get($value->coupon);
                if($coupon->status!=1){
                    continue;
                }
                $coupon_list['useable_coupons'][] = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
            }

            if(count($coupon_list['useable_coupons']) <= 15){
                $criteria = new EMongoCriteria();
                $criteria->user('==',new MongoId($user_id));
                $criteria->status('==',-1);

                $coupons = UserCoupon::model()->findAll($criteria);
                $coupon_list['used_coupons'] = array();
                foreach ($coupons as $value) {
                    $coupon = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
                    $coupon['unuseable_reason'] = '已使用';
                    $coupon_list['used_coupons'][] = $coupon;
                }
                $criteria = new EMongoCriteria();
                $criteria->user('==',new MongoId($user_id));
                $current_time = time();
                $criteria->end_time('<',$current_time);

                $coupons = UserCoupon::model()->findAll($criteria);
                $coupon_list['overtime_coupons'] = array();
                foreach ($coupons as $value) {
                    $coupon = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
                    $coupon['unuseable_reason'] = '已过期';
                    $coupon_list['overtime_coupons'][] = $coupon;
                }
            }else{
                $coupon_list['used_coupons'] = array();
                $coupon_list['overtime_coupons'] = array();
            }
            CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$coupon_list);
        }
        $price = 0;
        if($products){
            foreach ($products as $product) {
                if(isset($product['product_id']) && $product['count'] >= 1){
                    $product_temp = array();
                    $product_obj = Product::get(new MongoId($product['product_id']));
                    if($product_obj){
                        $price += ($product_obj->price*$product['count']);

                        $price += $extra[0]['price']*$product['count'];
                    }else{
                        CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_not_exist'));
                    }
                }else{
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_illegal'));
                }
            }
        }

        $criteria = new EMongoCriteria();
        $criteria->user('==',new MongoId($user_id));
        $current_time = time();
        $criteria->start_time('<=',$current_time);
        $criteria->end_time('>=',$current_time);
        $criteria->status('==',1);
        $coupons = UserCoupon::model()->findAll($criteria);

        $coupon_list = array(); 
        $unuseable_coupons = array();
        foreach ($coupons as $value) {
            $coupon = Coupon::get($value->coupon);
            if($coupon->status!=1){
                continue;
            }
            if($coupon->min_price>$price || ($type && $coupon->type != $type && $coupon->type != 0)){
                continue;
            }
            if($booking_time){
                if($weekend_check){
                    if($coupon->workday_limit==1){
                        $unuseable_coupons[] = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
                        continue;
                    }
                }else{
                    if($coupon->workday_limit==2){
                        $unuseable_coupons[] = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
                        continue;
                    }
                }
                if($coupon->time_limit_start && $coupon->time_limit_end && ($coupon->time_limit_start > $booking_hour || $coupon->time_limit_end < $booking_hour)){
                    $unuseable_coupons[] = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
                    continue;
                }
            }
            $coupon_list[] = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
        }
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$coupon_list,200,array('unuseable_coupons' => $unuseable_coupons));
    }

    public function actionCheckAddress(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $address_id = Yii::app()->getRequest()->getParam("address_id");
        $user_obj = CommonFn::apigetObJ($user_id,'ZUser',CommonFn::getMessage('user','id_not_exist'),array(),201);
        $address_list = $user_obj->shop_address?$user_obj->shop_address:array();
        $address = array();
        foreach ($address_list as $value) {
            if($address_id == $value['address_id']){
                $address = $value;
            }
        }
        if(!$address){
            file_put_contents('/data/erroraddress_user.log',var_export($user_id,true),FILE_APPEND);
            CommonFn::requestAjax(false,CommonFn::getMessage('shop','address_false'));
        }else{
            if(isset($address['address']['province']) && ($address['address']['province'] == '上海市' || $address['address']['province'] == '')){
                CommonFn::requestAjax(true,'success',array('station' => '58bd62ebce93ada5048b4578'));
            }else{
                file_put_contents('/data/erroraddress.log',var_export($address,true),FILE_APPEND);
                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','address_cannot_service'));
            }
        }
    }

    public function actionAppend(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        $products = json_decode(Yii::app()->getRequest()->getParam("products","[]"),true);
        if(!$user_id || !$products || !$order_id || !CommonFn::isMongoId($order_id) || !CommonFn::isMongoId($user_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $product_list = array();
        $price = 0;
        foreach ($products as $product) {
            if(isset($product['product_id']) && $product['count'] >= 1){
                $product_temp = array();
                $product_obj = Product::get(new MongoId($product['product_id']));
                if($product_obj){
                    $price += ($product_obj->price*$product['count']);
                    $product_temp['product'] = $product_obj->_id;
                    $product_temp['count'] = $product['count'];
                    $product_list[] = $product_temp;
                    if($product_obj->is_extra != 1){
                        CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_illegal'));
                    }
                }else{
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_not_exist'));
                }
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','product_illegal'));
            }
        }
        $append_product = new AppendOrder();
        $append_product->order = $order->_id;
        $append_product->products = $product_list;
        $append_product->price = $price;
        $append_product->append_time = time();
        $success = $append_product->save();
        if($success){
            $order->append_orders[] =  $append_product->_id;
            $order->update(array('append_orders'),true);
            $list = new ARedisList('append_order_list');
            $list->push((string)$order->_id);
            $append_info = $append_product->parseRow($append_product);
            CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$append_info);
        }
        CommonFn::requestAjax($success, '');
    }

    public function actionAppendPay(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $pay_channel = Yii::app()->getRequest()->getParam("pay_channel");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        $append_id = Yii::app()->getRequest()->getParam("append_id");
        if(!$user_id || !$pay_channel || !$order_id || !$append_id || !CommonFn::isMongoId($append_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        $append_product = AppendOrder::get(new MongoId($append_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($append_product->status == 1){
            CommonFn::requestAjax(false,'此订单已支付过',array('have_pay'=>1));
        }
        $str = '附加服务';
        $amount = ceil($append_product->price*1000)/10;
        $result = Service::factory('PayService')->Pay($pay_channel,$amount,(string)$append_product->_id,$str,$str,$user_obj->wx_pub_openid);
        if($result === false){
            CommonFn::requestAjax(false,'支付遇到点问题了，请稍候再试');
        }else{
            // 追加订单并付款后通知保洁师
            if (!empty($order->technician)) {
                $techObj = TechInfo::get($order->technician);
                if (!empty($techObj) && !empty($techObj->weixin_userid)) {
                    $wechat = O2oApp::getWechatActive();
                    $url_prefix = ENVIRONMENT == 'product' ? 'http:// api.yiguanjia.me' : 'http:// apitest.yiguanjia.me';
                    $wechat_data = array(
                        'touser'  => $techObj->weixin_userid,
                        'msgtype' => 'news',
                        'agentid' => '24',
                        'news'    => array(
                            'articles' => array(
                                array(
                                    'title' => '壹橙管家提示-新的追加订单',
                                    'description' => $techObj->name.'你好！预定时间在'.date('m月d日H:i', $order->booking_time).'的订单刚刚被用户追加了新的服务，请点击查看。',
                                    'url' => $url_prefix.'/index.php?r=o2o/myOrder/info&order='.(string)$order->_id.'&user='.$order->technician,
                                ),
                            ),
                        ),
                    );
                    $wechat->sendMessage($wechat_data);
                }
            }
            CommonFn::requestAjax(true,'success',json_decode($result));
        }
    }

    public function actionRetrieve(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $order_id = Yii::app()->getRequest()->getParam("order_id");

        if(!$user_id || !$order_id ){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order->status != 1){
            CommonFn::requestAjax(false,'此订单不可申请退款');
        }
        $order->status = -3;
        $order->apply_refund_time = time();


        if($order->update(array('status','apply_refund_time'),true)){
            // 发送通知给保洁师
            if (!empty($order->technician)) {
                $techObj = TechInfo::get($order->technician);
                if (!empty($techObj) && !empty($techObj->weixin_userid)) {
                    $wechat = O2oApp::getWechatActive();
                    $url_prefix = ENVIRONMENT == 'product' ? 'http:// api.yiguanjia.me' : 'http:// apitest.yiguanjia.me';
                    $wechat_data = array(
                        'touser'  => $techObj->weixin_userid,
                        'msgtype' => 'news',
                        'agentid' => '24',
                        'news'    => array(
                            'articles' => array(
                                array(
                                    'title' => '壹橙管家提示-申请退款',
                                    'description' => $techObj->name.'你好！预定时间在'.date('m月d日H:i', $order->booking_time).'的订单刚刚已被用户申请退款，请点击查看。',
                                    'url' => $url_prefix.'/index.php?r=o2o/myOrder/info&order='.$order_id.'&user='.$order->technician,
                                ),
                            ),
                        ),
                    );
                    $wechat->sendMessage($wechat_data);
                }
            }
            CommonFn::requestAjax(true,'申请退款成功，请等待确认');
        }else{
            CommonFn::requestAjax(false,'请稍后再试');
        }
    }

    public function actionConfirmComplete(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        if(!$user_id || !$order_id ){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if(!in_array($order->status, array(1,2,3,4,5))){
            CommonFn::requestAjax(false,'此订单不支持修改状态');
        }
        $order->status = 6;
        $order->finish_time = time();

        $success = $order->update(array('finish_time','status'),true);
        if($success ){
            // 保洁师订单统计
            if (isset($order->technicians)) {

                foreach($order->technicians as $technician){
                    $tech_obj = TechInfo::get($technician['technician_id']);
                    if ($tech_obj) {
                        $order_count = $tech_obj->order_count + 1;
                        $tech_obj->order_count = $order_count;
                    }
                }


            }
            CommonFn::requestAjax(true,'订单已完成，期待您的下次预约');
        }else{
            CommonFn::requestAjax(false,'请稍后再试');
        }
    }

    public function actionDetail(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        if(!$user_id || !$order_id || !CommonFn::isMongoId($order_id) || !CommonFn::isMongoId($user_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $order_info = $order->parseRow($order);
        CommonFn::requestAjax(true,'',$order_info);
    }


    //选择保洁师
    public function actionSelectTech(){
        $booking_time = Yii::app()->getRequest()->getParam("booking_time");
        $service_type = json_decode(Yii::app()->getRequest()->getParam("service_type","[]"),true);
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $address_id = Yii::app()->getRequest()->getParam("address_id");
        $user_obj = CommonFn::apigetObJ($user_id,'ZUser',CommonFn::getMessage('user','id_not_exist'),array(),201);
        $address_list = $user_obj->shop_address?$user_obj->shop_address:array();
        $address = array();
        foreach ($address_list as $value) {
            if($address_id == $value['address_id']){
                $address = $value;
            }
        }
        if(strtotime($booking_time) < time()){
            CommonFn::requestAjax(false,'此时间段不可预约');
        }
        if(!$address){
            CommonFn::requestAjax(false,CommonFn::getMessage('shop','address_false'));
        }
        if(!isset($address['position'][0])||$address['position'][0]==0||is_int($address['position'][0])){
            $address_res = CommonFn::simple_http('http://api.map.baidu.com/geocoder/v2/?ak=B349f0b32ef6e78b2e678f45cb9fddaf&address='.$address['address']['area'].$address['address']['detail'].'&city='.$address['address']['city'].'&output=json');
            $address_info = json_decode($address_res,true);
            $position = $address_info['result']['location'];
            $detail_res = CommonFn::simple_http('http://api.map.baidu.com/geocoder/v2/?ak=B349f0b32ef6e78b2e678f45cb9fddaf&location='.$position['lat'].','.$position['lng'].'&output=json&pois=1');
            $detail_info = json_decode($detail_res,true);
            $business = explode(',',$detail_info['result']['business']);
            
        }else{
            $detail_res = CommonFn::simple_http('http://api.map.baidu.com/geocoder/v2/?ak=B349f0b32ef6e78b2e678f45cb9fddaf&location='.$address['position'][1].','.$address['position'][0].'&output=json&pois=1');
            $detail_info = json_decode($detail_res,true);
            $business = explode(',',$detail_info['result']['business']);
        }
        $user_model = new TechInfo();
        $criteria = new EMongoCriteria();
        $criteria->service_type('all', $service_type);
        if( !in_array(9, $service_type) && !in_array(10, $service_type)){
            foreach ($business as $value) {
                $criteria->addCond('business','or',$value);
            }
        }
        $criteria->status('==', 1);
        $cursor = $user_model->findAll($criteria);
        //获取满足服务类型保洁师列表
        $match_service_tech_list = array();
        $tech_list = array();
        foreach ($cursor as $key => $value) {
            $match_service_tech_list[] = $value->_id;
            $tech_list[$value->_id] = $value;
        }
        $parse_time = strtotime(date('Y-m-d H:00',strtotime($booking_time)));
        $FreeTimeRecord = FreeTimeRecord::get($parse_time);
        // $last_parse_time = strtotime(date('Y-m-d H:00',strtotime($booking_time)))-3600;
        // $LastFreeTimeRecord = FreeTimeRecord::get($last_parse_time);
        // $next_parse_time = strtotime(date('Y-m-d H:00',strtotime($booking_time)))-3600;
        // $NextFreeTimeRecord = FreeTimeRecord::get($next_parse_time);
        //获取此时段空闲保洁师列表
        $free_tech_list = array();
        // $last_free_tech_list = array();
        // $next_free_tech_list = array();
        if($FreeTimeRecord){
            $free_tech_list = $FreeTimeRecord->free_technician;
        }
        // if($LastFreeTimeRecord){
        //     $last_free_tech_list = $LastFreeTimeRecord->free_technician;
        // }
        // if($NextFreeTimeRecord){
        //     $next_free_tech_list = $NextFreeTimeRecord->free_technician;
        // }
        //空闲且满足服务能力保洁师，推荐用户选择
        $recommend_tech = array_intersect($match_service_tech_list,$free_tech_list);
        $can_select_tech = array();
        foreach ($recommend_tech as $key => $value) {
            $tech = $tech_list[$value];
            unset($tech_list[$value]);
            $tmp['id'] = $tech->_id;
            $tmp['name'] = $tech->name;
            $tmp['desc'] = $tech->desc;
            $tmp['service_type'] = $tech->service_type;
            $tmp['avatar'] = $tech->avatar;
            $tmp['favourable_count'] = $tech->favourable_count;
            $can_select_tech[] = $tmp;
        }
        //满足服务能力，但此时段不空闲，展示给用户此保洁师下个空闲时段
        $service_match_tech = array();
        foreach ($tech_list as $key => $value) {
            $tmp['id'] = $value->_id;
            $tmp['name'] = $value->name;
            $tmp['desc'] = $value->desc;
            $tmp['service_type'] = $value->service_type;
            $tmp['avatar'] = $value->avatar;
            $tmp['favourable_count'] = $value->favourable_count;
            $criteria = new EMongoCriteria();
            $criteria->free_technician('==', $tmp['id']);
            $criteria->_id('>',time());
            $criteria->limit(1);
            $criteria->sort('_id', EMongoCriteria::SORT_ASC);
            if(FreeTimeRecord::model()->count($criteria)){
                $free_time = FreeTimeRecord::model()->findAll($criteria);
                foreach ($free_time as $next_free_time) {
                    $tmp['next_free_time'] = FreeTimeRecord::parseFreeTime($next_free_time->_id);
                    break;
                }
            }else{
                continue;
            }
            $service_match_tech[] = $tmp;
        }
        $data['can_select_tech'] = $can_select_tech;
        $data['service_match_tech'] = $service_match_tech;
        CommonFn::requestAjax(true,'',$data);
    }

     /**
     * 获取评价列表接口
     */
    public function actionTechComment() {
        $page = intval(Yii::app()->getRequest()->getParam("page",1));
        //$tech_id = intval(Yii::app()->getRequest()->getParam("tech_id"));
        $pagesize = Yii::app()->params['O2oCommentListPageSize'];
        if(!$tech_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $conditions = array(
                            //'technician'=>array('==',$tech_id),
                            'status'=>array('==',1),
                        );
        $order = array(
                        'time' => 'desc'
                        );
        $model = new Comment();
        $pagedata = CommonFn::getPagedata($model,$page,$pagesize,$conditions,$order);
        $data['comments'] = $pagedata['res'];
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,200,array('sum_count' => $pagedata['sum_count'],'sum_page'=>$pagedata['sum_page'],'page_size'=>$pagedata['page_size'],'current_page'=>$pagedata['current_page']));
    }

}