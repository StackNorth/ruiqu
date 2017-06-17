<?php
/**
 *支付service
 */
$path = Yii::getPathOfAlias('application');
require_once($path.'/vendors/pingpp/init.php');
$api_key = Yii::app()->params['pingxx_api_key'];
\Pingpp\Pingpp::setApiKey($api_key);
class PayService extends Service{
    public $app_id = 'app_afjDq1bf9OyT5yLa';
    /**
     * @param $channel 第三方支付渠道,取值范围：
     * alipay:支付宝手机支付
     * alipay_wap:支付宝手机网页支付
     * alipay_qr:支付宝扫码支付
     * apple_pay:Apple Pay
     * bfb:百度钱包移动快捷支付
     * bfb_wap:百度钱包手机网页支付
     * upacp:银联全渠道支付（2015年1月1日后的银联新商户使用。若有疑问，请与 ping++ 或者相关的收单行联系）
     * upacp_wap:银联全渠道手机网页支付（2015年1月1日后的银联新商户使用。若有疑问，请与 ping++ 或者相关的收单行联系）
     * upmp:银联手机支付（限个人工作室和2014年之前的银联老客户使用。若有疑问，请与 ping++ 或者相关的收单行联系）
     * upmp_wap:银联手机网页支付（限个人工作室和2014年之前的银联老客户使用。若有疑问，请与 ping++ 或者相关的收单行联系）
     * wx:微信支付
     * wx_pub:微信公众账号支付
     * wx_pub_qr:微信公众账号扫码支付
     * @param $amount 订单金额
     */
    public function pay($channel,$amount,$order_no,$subject,$body,$openid = ''){
        if (empty($channel) || empty($amount)) {
            return false;
        }
        $channel = strtolower($channel);
        $orderNo = $order_no;
        //$extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
        $extra = array();
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://common.yichenguanjia.com/success',
                    'cancel_url' => 'http://common.yichenguanjia.com/cancel'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'bfb_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code=',
                    'bfb_login' => true
                );
                break;
            case 'upacp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => $openid
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => 'Productid'
                );
                break;
        }
        try {
            $ch = \Pingpp\Charge::create(
                array(
                    "subject"   => $subject,//商品的标题，该参数最长为 32 个 Unicode 字符。
                    "body"      => $body,//商品的描述信息，该参数最长为 128 个 Unicode 字符
                    "amount"    => $amount,
                    "order_no"  => $orderNo,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $channel,
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => $this->app_id)
                )
            );
            return $ch;
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            echo $e->getHttpBody();
            die();
            // return false;
        }
    }

    public function retrieve($charge_id){
        $ch = \Pingpp\Charge::retrieve($charge_id);
        return $ch;
    }
}
?>
