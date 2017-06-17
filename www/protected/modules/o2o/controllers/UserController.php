<?php
/**
 * UserController o2o用户相关接口
 *
 *  
 *
 */
class  UserController extends O2oBaseController{
    
    public function actionInfo(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");

        if(CommonFn::isMongoId($user_id)){
            $user = Service::factory('UserService')->getUser(new MongoId($user_id),false);
            if($user){
                $user = $user->parseRow($user->attributes);

                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$user);
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('user','id_not_exist'));
            }
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
    }

    //兑换码兑换优惠券
    public function actionExchangeCoupon(){
        $user_id = Yii::app()->getRequest()->getParam("user_id","");
        $exchange_code = Yii::app()->getRequest()->getParam("exchange_code",0);
        //$device_id = Yii::app()->request->getParam('device_id');
        $device_id = '';
        if(CommonFn::isMongoId($user_id) && $exchange_code){
            $user = CommonFn::apigetObJ($user_id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
            $criteria = new EMongoCriteria();
            $criteria->code('==',$exchange_code);
            $exchange_code = CouponCode::model()->find($criteria);
            $current_time = time();

            if($exchange_code && $exchange_code->status == 0 && $exchange_code->stop_time > $current_time){

                $criteria = new EMongoCriteria();
                if($device_id){
                   // $criteria->user('or',$user->_id);
                    //$criteria->user_device_id('or',$device_id);
                    //$criteria->channel('==',$exchange_code->channel);
                }else{
                    $criteria->channel('==',$exchange_code->channel);
                    $criteria->user('==',$user->_id);
                }
                $have_check = CouponCode::model()->count($criteria);
                if($have_check){
                    CommonFn::requestAjax(false,CommonFn::getMessage('o2o','exchange_code_have_same'));
                }

                foreach ($exchange_code->coupons as $coupon_id) {
                    Service::factory('CouponService')->giveCoupon($user->_id,$coupon_id);
                }
                $exchange_code->use_time = $current_time;
                $exchange_code->user = $user->_id;
                //$exchange_code->user_device_id = $device_id;
                $exchange_code->status = 1;
                $exchange_code->update(array('use_time','status','user'),true);
                CommonFn::requestAjax(true,CommonFn::getMessage('o2o','exchange_succeed'));
            }elseif($exchange_code && $exchange_code->status == 1){
                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','exchange_code_used'));
            }else{

                CommonFn::requestAjax(false,CommonFn::getMessage('o2o','exchange_code_unuseable'));
            }
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }

    }



}