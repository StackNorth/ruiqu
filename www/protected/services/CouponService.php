<?php
//代金券service
class CouponService extends Service{
    /**
     * giveCoupon 给单个用户发放代金券
     * @author   >
     * @param mongoID $user_id 用户mongoid
     * @param mongoID $coupon_id 代金券mongoid
     * @param int $start_time 代金券可以使用的开始时间
     * @param int $end_time 代金券可以使用的结束时间
     * @return mixed
     */
    public function giveCoupon($user_id,$coupon_id,$start_time = 0 ,$end_time =0 ) {
        $user = RUser::get($user_id);
        if(!$user){
            return '用户不存在';
        }
        $coupon = Coupon::get($coupon_id);
        if(!$coupon){
            return '此优惠券不存在';
        }
        //$device_id = Yii::app()->request->getParam('device_id');
        $user_coupon = new UserCoupon();
        $user_coupon->start_time = intval($start_time?$start_time:time());
        $user_coupon->end_time = intval($end_time?$end_time:time()+2592000);
        $user_coupon->coupon = $coupon_id;
        $user_coupon->user = $user_id;
        //$user_coupon->user_device_id = $device_id;
        $user_coupon->status = 1;
        if($user_coupon->save()){
            return true;
        }else{
            return $user_coupon->getScenarioError();
        }
    }

}
?>