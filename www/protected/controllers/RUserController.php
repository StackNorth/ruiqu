<?php
class RUserController extends AdminController{

    public function actionIndex()
    {
        // 订单类型
        $type_data = Yii::app()->params['o2o_service'];
        $type = CommonFn::getComboboxData($type_data, 100, true, 100);
        $this->render('index', array(
            'type'         => $type
        ));
    }

    public function actionList(){

        $search = Yii::app()->request->getParam('search', '');
        $id = Yii::app()->request->getParam('id', '');

        $params = CommonFn::getPageParams();
        if(isset($params['sort']) && isset($params['sort']['register_time'])){
            $params['sort'] = array('_id' => $params['sort']['register_time']);
        }


        $criteria = new EMongoCriteria($params);



        if ($id != ''){
            $user_id = new MongoId($id);
            $criteria->_id('==', $user_id);
        }

        if ($search != '' && !CommonFn::isMongoId($search)){
            $criteria->user_name('or', new MongoRegex('/' . $search . '/'));
            if (CommonFn::isMongoId($search)){
                $criteria->_id('or', new MongoId($search));
            }
        }
        if (CommonFn::isMongoId($search)) {
            $criteria = new EMongoCriteria();
            $criteria->_id('==', new MongoId($search));
        }
        $cursor = RUser::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = RUser::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionUpdate(){

        $id = Yii::app()->request->getParam('id', '');


        if(!$id){
            CommonFn::requestAjax(false, "缺少必须参数");
        }



        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoId($id);
        $user = RUser::model()->find($criteria);


        $keys = array('status','certify_status','is_fake_user','city_info');

        $success = $user->save(true, $keys, true);


        CommonFn::requestAjax($success, $message, array());
    }

    public function actionGetCoupons(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $UserCoupon = new UserCoupon();
        $criteria = new EMongoCriteria();
        $criteria->user('==',new MongoId($user_id));
        $current_time = time();
        $criteria->end_time('>=',$current_time);
        $criteria->status('==',1);
        $coupons = UserCoupon::model()->findAll($criteria);
        $coupon_list = array(); 
        foreach ($coupons as $value) {
            $coupon = Coupon::get($value->coupon);
            if($coupon->status!=1){
                continue;
            }
            $coupon = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
            $coupon['unuseable_reason'] = '可使用';
            $coupon_list[] = $coupon;
        }
        $criteria = new EMongoCriteria();
        $criteria->user('==',new MongoId($user_id));
        $criteria->status('==',-1);
        $coupons = UserCoupon::model()->findAll($criteria);
        foreach ($coupons as $value) {
            $coupon = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
            $coupon['unuseable_reason'] = '已使用';
            $coupon_list[] = $coupon;
        }
        $criteria = new EMongoCriteria();
        $criteria->user('==',new MongoId($user_id));
        $current_time = time();
        $criteria->end_time('<',$current_time);
        $coupons = UserCoupon::model()->findAll($criteria);
        foreach ($coupons as $value) {
            $coupon = $UserCoupon->parseRow($value,array('id','start_time','end_time','start_time_str','end_time_str','coupon'));
            $coupon['unuseable_reason'] = '已过期';
            $coupon_list[] = $coupon;
        }
        foreach ($coupon_list as $key => $value) {
            $coupon_list[$key]['name'] = $value['coupon']['name'];
            $coupon_list[$key]['value'] = $value['coupon']['value'];
            $coupon_list[$key]['min_price'] = $value['coupon']['min_price'];
            $coupon_list[$key]['type_str'] = $value['coupon']['type_str'];
        }
        $total = count($coupon_list);
        $data = $coupon_list;
        echo CommonFn::composeDatagridData($data, $total);
    }

    public function actionChangeBalance(){
        $id = Yii::app()->request->getParam('id');
        $amount = intval(Yii::app()->request->getParam('amount'));
        $memo = Yii::app()->request->getParam('memo');
        $type = Yii::app()->request->getParam('type');

        if(!$id||!$amount||!$memo||!$type){
            CommonFn::requestAjax(false, "缺少必须参数");
        }

        if(!$memo){
            CommonFn::requestAjax(false, "备注必填哦");
        }

        $user = RUser::get(new MongoId($id));
        if(!$user){
            CommonFn::requestAjax(false, "用户不存在");
        }

        $user->balance = $user->balance+$amount;
        if($user->balance<0){
            CommonFn::requestAjax(false, "用户余额不能小于0");
        }
        $user->save();

        $balance_log = new BalanceLog();
        $balance_log->time = time();
        $balance_log->user =  $user->_id;
        $balance_log->memo = $memo;
        $balance_log->type = $type;
        $balance_log->amount = $amount;
        $balance_log->save(true);

        CommonFn::requestAjax(true, '修改成功', array());
    }

    public function actionBalanceLog(){
        $id = Yii::app()->request->getParam('id', '');

        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);

        if ($id != ''){
            $id = new MongoId($id);
            $criteria->user('==', $id);
        }else{
            CommonFn::requestAjax(false, "缺少必须参数");
        }

        $cursor = BalanceLog::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = BalanceLog::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }


     public function actionSendCoupon() {
         $user_id = Yii::app()->request->getParam('user_id', '');
         $coupon_id = Yii::app()->request->getParam('coupon_id', '');
         $start_time = Yii::app()->request->getParam('start_time', '');
         $end_time = Yii::app()->request->getParam('end_time', '');
         $need_sms = intval(Yii::app()->request->getParam('need_sms', '0'));
         $copy = Yii::app()->request->getParam('copy', '');

         $mongo = new MongoClient(DB_CONNETC);
         $db = $mongo->fuwu;
         $coll = 'admin_send_coupon_log';
         $collection = $db->selectCollection($coll);

         $admin = Yii::app()->user;
         $admin_id = $admin->id;
         $user_id = new MongoId($user_id);
         $coupon_id = new MongoId($coupon_id);
         $time = time();

         $user = RUser::get($user_id);

         //开始与结束时间处理
         $start_time = empty($start_time) ? $time : strtotime($start_time);
         $end_time   = empty($end_time) ? strtotime('+30 day', $start_time) : strtotime($end_time);

         //插入user_coupons表
         $flag_user_coupon = Service::factory('CouponService')->giveCoupon($user_id, $coupon_id, $start_time, $end_time);
         if (!$flag_user_coupon) {
             CommonFn::requestAjax($flag_user_coupon, '发放优惠券失败', array());
         }

          //插入admin_send_coupon_log表
         $data = array(
             'admin_id'  => $admin_id,
             'user_id'   => $user_id,
             'coupon_id' => $coupon_id,
             'time'      => $time
         );
         $flag_send_log = $collection->insert($data);
         if ($flag_send_log['err'] != null) {
             CommonFn::requestAjax($flag_user_coupon, '优惠券已发放，日志记录失败', array());
         }

          //发送短信
         if ($need_sms) {
             if (isset($user->mobile)&&$user->mobile != '') {
                 Service::factory('SendSMSService')->send_sms($copy, $user->mobile);
             } else if (!empty($user->shop_address) && isset($user->shop_address[0]['mobile'])) {
                 $mobile = $user->shop_address[0]['mobile'];
                 Service::factory('SendSMSService')->send_sms($copy, $mobile);
             }
         }

         CommonFn::requestAjax(true, '优惠券已成功发放');
     }
}
