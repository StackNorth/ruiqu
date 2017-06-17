<?php
class CouponController extends AdminController{

    public function actionIndex()
    {
        $status_option = Coupon::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $type = CommonFn::getComboboxData(Yii::app()->params['o2o_service'], 0, true, 0);
        $workday_limit_option = Coupon::$workday_limit_option;
        $workday_limit = CommonFn::getComboboxData($workday_limit_option, 0, false);
        $this->render('index', array(
            'status' => $status,
            'type'=>$type,
            'workday_limit' => $workday_limit
        ));
    }

    public function actionList(){
        $search = Yii::app()->request->getParam('search', '');
        $filter_status = intval(Yii::app()->request->getParam('status', 100));
        $id = Yii::app()->request->getParam('id', '');

        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);

        if ($id != ''){
            $coupon_id = new MongoId($id);
            $criteria->_id('==', $coupon_id);
        }
        if ($filter_status != 100){
            $criteria->status('==', $filter_status);
        }

        // 模糊搜索处理
        // 2015-11-19
        if ($search != '') {
            if (strlen($search) == 24) {
                $criteria->_id('or', new MongoId($search));
            }
            $criteria->name('or', new MongoRegex('/'.$search.'/'));
        }

        $criteria->sort('time', EMongoCriteria::SORT_DESC);

        $cursor = Coupon::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Coupon::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');

        $status = intval(Yii::app()->request->getParam('status', 1));
        $name = Yii::app()->request->getParam('name', '');
        $alias_name = Yii::app()->request->getParam('alias_name', '');

        $memo = Yii::app()->request->getParam('memo', '');

        $type = Yii::app()->request->getParam('type', 0);

        $value = intval(Yii::app()->request->getParam('value', 0));
        $min_price = intval(Yii::app()->request->getParam('min_price', 0));

        if($status == 100 ){
            CommonFn::requestAjax(false, '必须指定状态和服务类型！');
        }
        if($name == ''||$alias_name == '' || !$value  ){
            CommonFn::requestAjax(false, '必填内容为空');
        }

        $status=$status>1?1:$status;

        if(!$id){
            $coupon = new Coupon();
            $coupon->time = time();
        }else{
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $coupon = Coupon::model()->find($criteria);
            if (empty($coupon)){
                CommonFn::requestAjax(false, '代金券 不存在');
            }
        }

        $arr_coupon = array('status','name','value','min_price','type','memo','alias_name', 'time');

        $workday_limit = intval(Yii::app()->request->getParam('workday_limit', 0));
        $coupon->workday_limit = $workday_limit;
        array_push($arr_coupon, 'workday_limit');

        $time_limit_start = intval(Yii::app()->request->getParam('time_limit_start', ''));
        $time_limit_end = intval(Yii::app()->request->getParam('time_limit_end', ''));
        if (!empty($time_limit_start) && !empty($time_limit_end)) {
            $coupon->time_limit_start = intval($time_limit_start);
            $coupon->time_limit_end = intval($time_limit_end);
            array_push($arr_coupon, 'time_limit_start', 'time_limit_end');
        }

        $coupon->status = $status;
        $coupon->alias_name = $alias_name;
        $coupon->name = $name;
        $coupon->value = $value;
        $coupon->min_price = $min_price;
        $coupon->type = $type;
        $coupon->memo = $memo;

        $success = $coupon->save(true,$arr_coupon);
        CommonFn::requestAjax($success, '', array());
    }

//生成兑换码
    public function actionCouponFactory(){
        $this->renderPartial('couponFactory');
        $count = Yii::app()->request->getParam('count',0) ;
        $coupons = Yii::app()->request->getParam('coupons','');
        $coupons_filter = str_replace(" ", "", $coupons);
        $coupons_filter_1 = str_replace("，", ",", $coupons_filter);
        // var_dump($coupons_filter);die(); 
        if(empty($coupons)){
            echo "请输入优惠码ID！";exit();
        }
        $couponsid = explode(',', $coupons_filter_1);
        if(count($couponsid)>5||count($couponsid)<1){
            echo "注意：0<优惠码数量<6！";
            exit;   
        }
        if($count>99||$count<1){
            echo "注意：0<生成兑换码数量<100！";
            exit;
        }
        // echo strlen('55667f6c0eb9fb14518b6e0a');die;
        $date = time();
        $i = 0;          
        $coupon_confirm = array();
        foreach ($couponsid as $key=>$value) {
            if(strlen($value)!=24){
                echo "请输入正确的优惠码ID！";exit;
            }
            $coupon_objId = new MongoId($value);
            $criteria = new EMongoCriteria();
            $criteria->_id = $coupon_objId;
            $criteria->status = 1;
            $coupon = Coupon::model()->find($criteria);
            // var_dump($coupon);die();
            if (empty($coupon)){
                echo '该代金券已暂停使用或已删除!';
                exit;
            }

            $coupon_confirm[] = $coupon_objId;
        }
        for (;$i<$count;$i++) {
            $model = new CouponCode();
            $model->coupons = $coupon_confirm;
            $model->code = $model->code +  $i*1101;
            $model->stop_time = time() + 86400*30;
            $model->save();
            file_put_contents(APP_PATH.'/download/'.$date.'_coupon_code.txt',$model->code."\r\n",FILE_APPEND);
        }
        echo '兑换码生成成功,共'.$count.'张-----------------';
        echo "查看地址：".APP_PATH."/download/".$date.'_coupon_code.txt';
    }

    // 选择优惠券
    public function actionSelectCoupon() {
        $search = Yii::app()->request->getParam('coupon', '');

        $criteria = new EMongoCriteria();
        if (strlen($search) == 24) {
            $criteria->_id('or', new MongoId($search));
        }
        $criteria->name('or', new MongoRegex('/'.$search.'/'));

        $cursor = Coupon::model()->findAll($criteria);
        $data = array();
        foreach ($cursor as $key => $row) {
            $description = 'ID:'.(string)$row->_id;
            $description .= "<br>".$row->memo;
            $data[] = array(
                'id' => $key,
                'data' => $row->name,
                'description' => $description,
                'cid' => (string)$row->_id
            );
        }

        echo json_encode($data);
    }

    // 获取优惠券信息
    public function actionGetCouponInfo() {
        $id = Yii::app()->request->getParam('coupon_id', '');
        $success = 0;
        $message = '未查询到优惠券';
        $content = array();

        if (!empty($id) && strlen($id) == 24) {
            $_id = new MongoId($id);
            $coupon = Coupon::get($_id);
            if ($coupon != false) {
                if ($coupon->status == 1) {
                    $success = 1;
                    $message = 'success';
                    $content = array(
                        '_id' => (string)$coupon->_id,
                        'value' => $coupon->value,
                        'name' => $coupon->name
                    );
                } else {
                    $message = '优惠券不可用';
                }
            }
        }

        $data = array(
            'success' => $success,
            'message' => $message,
            'content' => $content,
        );
        echo json_encode($data);
    }

    public function actionCouponCodeStates(){
        $exchange_code = intval(Yii::app()->getRequest()->getParam("exchange_code",0));
        $criteria = new EMongoCriteria();
        $criteria->code('==',$exchange_code);
        $exchange_code = CouponCode::model()->find($criteria);
        if($exchange_code){
            if($exchange_code && $exchange_code->status == 0 && $exchange_code->stop_time > time()){
                $data = array(
                    'success' => true,
                    'message' => '此兑换码可用',
                );
            }else{
                $data = array(
                    'success' => false,
                    'message' => '此兑换码不可用',
                );
            }
            
        }else{
            $data = array(
                    'success' => false,
                    'message' => '此兑换码不存在',
                );
        }
        echo json_encode($data);
    }

}