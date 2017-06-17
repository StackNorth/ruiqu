<?php
class ConfirmController extends CController{
    public  $layout="none";
    public function actionPaySuccess(){
        $input_data = json_decode(file_get_contents("php://input"), true);
        $input_data = $input_data['data']['object'];
        if($input_data['object'] == 'charge'&& $input_data['paid']==true){

            //TODO update database

            if(strpos($input_data['order_no'], 'char') !== false  ){    //充值卡订单
                // 支付成功后 用户余额/代金券  增加   暂时充值卡发放的代金券没有过期时间

                $order = RechargeOrder::get(new MongoId(str_replace("char","",$input_data['order_no'])));

                $recharge = Recharge::get(new MongoId($order->recharge));

                $user_obj = RUser::get($order->user);

                $amount = $recharge->denomination;


                if($recharge->cash_back){
                    $amount += $recharge->cash_back;
                }
                if(isset($recharge->coupons) && is_array($recharge->coupons) && count($recharge->coupons)){
                    foreach ($recharge->coupons as $coupon_id) {
                        //$start_time = time();
                        //$end_time = strtotime(date('Y-m-d',$start_time+1209600));
                        Service::factory('CouponService')->giveCoupon($user_obj->_id, $coupon_id);
                    }
                }

                $user_obj->balance = $user_obj->balance+$amount;
                $user_obj->save();

                $log = new RechargeLog();
                $log->time = time();
                $log->user =  $user_obj->_id;
                $log->recharge = $recharge->_id;
                $log->save(true);

                $balance_log = new BalanceLog();
                $balance_log->time = time();
                $balance_log->user =  $user_obj->_id;
                $balance_log->memo = '购买充值卡充值';
                $balance_log->type = 'recharge';
                $balance_log->amount = $amount;
                $balance_log->save(true);

                $order->charge_id = $input_data['id'];
                $order->pay_channel = $input_data['channel'];
                $order->status = 1;
                $order->update(array('charge_id','pay_channel','status'),true);

                echo 'success';
                die();

            }else{
                $order = ROrder::get(new MongoId($input_data['order_no']));
                if(!$order || $order->status == -1){
                    echo 'fail';
                    die();
                }
                $order->charge_id = $input_data['id'];
                $order->pay_channel = $input_data['channel'];
                if($order->final_price>$order->pay_price){
                    $order->pay_channel = 'mix';
                }
                $order->status = 1;
                if($order->update(array('charge_id','pay_channel','status','pay_channel'),true)){
                    $list = new ARedisList('o2o_after_pay_success');
                    $list->push(json_encode($input_data));

                    //如果有余额支付  扣除用户余额  并生成余额变动日志
                    if($order->final_price>$order->pay_price){
                        $user_obj = RUser::get($order->user);
                        $user_obj->balance = $user_obj->balance-($order->final_price-$order->pay_price);
                        $user_obj->save();

                        $balance_log = new BalanceLog();
                        $balance_log->time = time();
                        $balance_log->user =  $user_obj->_id;
                        $balance_log->memo = '微信下订单';
                        $balance_log->type = 'order';
                        $balance_log->amount = $order->final_price-$order->pay_price;
                        $balance_log->save(true);
                    }

                    echo 'success';
                    die();
                }else{
                    echo 'fail';
                    die();
                }
            }

        }elseif($input_data['object'] == 'refund'&& $input_data['succeed']==true){
            //TODO update database
            echo 'success';
            die();
        }else{
            echo 'fail';
            die();
        }
    }

    public function actionCheckMobile(){
        $mobile = Yii::app()->getRequest()->getParam("mobile",'');
        if($mobile && preg_match("/\d{11}/",$mobile)){
            $criteria = new EMongoCriteria();
            $criteria->mobile('==',$mobile);
            $user = Mobile::model()->find($criteria);
            if($user){
                echo 'fail';
            }else{
                $userAr  = new Mobile();
                $userAr->mobile = $mobile;
                if($userAr->save()){
                    echo 'success';
                }else{
                    echo 'fail';
                }
            }
        }else{
            echo 'fail';
        }
    }

    public function actionSyncCode(){
        exec("sh /var/www/ruiqu/webshell/SyncCodeDev.sh");
        echo 'success';
    }




}

