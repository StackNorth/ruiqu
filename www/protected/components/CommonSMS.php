<?php
/**
 * Created by PhpStorm.
 * User: songyongming
 * Date: 15/1/20
 * Time: 22:32
 */

class CommonSMS{

    public static function send($sms_type,$params = array()){
        switch ($sms_type) {
            case 'order_retrieve'://订单退款后发送短信
                Service::factory('SendSMSService')->tpl_send_sms(1594478,'#month#='.$params['month'].'&#day#='.$params['day'].'&#address#='.$params['address'],$params['mobile']);
                break;

            case 'final_order'://订单完成后发送短信
                Service::factory('SendSMSService')->tpl_send_sms(1594588,'#name#='.$params['name'].'&#num#='.$params['num'],$params['mobile']);
                // Service::factory('SendSMSService')->tpl_send_sms(862005,'#name#='.$params['name'],$params['mobile']);
                break;

            case 'order_pay_success'://订单支付成功后发送短信
                Service::factory('SendSMSService')->tpl_send_sms(1594484,'#month#='.$params['month'].'&#day#='.$params['day'].'&#address#='.$params['address'].'&#info#='.$params['info'].'&#master#='.$params['master'],$params['mobile']);
                break;
            default:
                return false;
                break;
        }
    }
    
// 862005    


}