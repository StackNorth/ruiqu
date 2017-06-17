<?php
/**
 * RechargeController o2o充值卡接口
 */
class  RechargeController extends O2oBaseController{
    //充值卡 支付

    public function actionTest(){
        //582fbcc49f5160b4048b56b8
        /*$user = RUser::model()->get(new MongoId('582fbcc49f5160b4048b56b8'));
        $num = intval(ceil((rand(100000, 999999))) / 10000);
        $data = '{
                        "touser": "' . $user->wx_pub_openid . '",
                        "msgtype": "text",
                        "text": {
                        "content": "' . '您好，您充值的1000元我们已收到。活动A抽奖号为' . $num . '，请直接微信留言您的联系方式，以便中奖后我们尽快联系您哦。感谢您的参与，壹橙管家祝您生活愉快。' . '"
                        }
                  }';
        var_dump(CommonFn::sendWxMessage($data));*/


    }


    public function actionPayRecharge(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $pay_channel = Yii::app()->getRequest()->getParam("pay_channel");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        if(!$user_id || !$pay_channel || !$order_id || !CommonFn::isMongoId($order_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = RUser::get(new MongoId($user_id));
        $order = RechargeOrder::get(new MongoId($order_id));
        if(!$order || !$user_obj){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order && (string)$order->user != $user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if($order->charge_id){
            CommonFn::requestAjax(false,'此订单已支付过',array('have_pay'=>1));
        }

        $str = '壹橙管家充值卡';
        $amount = ceil($order->price*1000)/10;
        $result = Service::factory('PayService')->Pay($pay_channel,$amount,'char'.(string)$order->_id,$str,$str,$user_obj->wx_pub_openid);
        if($result === false){
            CommonFn::requestAjax(false,'支付遇到点问题了，请稍候再试');
        }else{
            CommonFn::requestAjax(true,'success',json_decode($result),200,array());
        }
    }

    //充值卡订单创建
    public function actionAddRechargeorder(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $recharge_id = Yii::app()->getRequest()->getParam("recharge_id");

        if(!$user_id||!$recharge_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user_obj = CommonFn::apigetObJ($user_id,'ZUser',CommonFn::getMessage('user','id_not_exist'),array(),201);

        $recharge = Recharge::get(new MongoId($recharge_id));
        if(!$recharge){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }

        $order = new RechargeOrder();
        $order->time = time();

        $order->price = $recharge->denomination;
        $order->recharge = $recharge->_id;

        $order->user = $user_obj->_id;

        if($order->save()){
            $data = RechargeOrder::model()->parseRow($order);
            CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
        }else{
            CommonFn::requestAjax(true,'未知错误,请稍候再试',array());
        }
    }

    //获取充值卡列表
    public function actionRechargeList(){
        $criteria = new EMongoCriteria();

        $criteria->status('==', 1);

        $criteria->sort('order', EMongoCriteria::SORT_DESC);

        $cursor = Recharge::model()->findAll($criteria);
        $rows = CommonFn::getRows($cursor);
        $model = new Recharge();
        $res = $model->parse($rows);
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$res);
    }
}