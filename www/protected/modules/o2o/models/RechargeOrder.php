<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/9/26
 * Time: 11:57
 * 充值卡 订单模型
 */
class RechargeOrder extends MongoAr
{
    public $_id;

    public $user;//充值用户 mongoid

    public $time;//充值时间

    public $recharge;//充值卡  mongoid

    public $charge_id;//ping++的chargeId,charge_id即为支付单号
    public $pay_channel;//支付渠道

    public $price;  //订单金额
    public $status=0;//订单状态 0=>待支付  1=>已支付

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static $status_option = array(
        0 => array('name' => '待支付'),
        1 => array('name' => '已支付'),
    );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'recharge_order';
    }

    public static function get($_id) {
        if(CommonFn::isMongoId($_id)){
            $criteria = new EMongoCriteria();
            $criteria->_id('==', $_id);
            $model = self::model()->find($criteria);
            return $model;
        }else{
            return false;
        }
    }
}
