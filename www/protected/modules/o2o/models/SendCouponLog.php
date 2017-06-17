<?php
/**
 * User: charlie
 * 用户的代金券
 */
class SendCouponLog extends MongoAr
{
    public $_id;

    public $user_id;//发放用户的id
    public $device_id;//发放用户的device_id
    public $batch;//最后一次发放优惠券的批次
    public $last_batch_time;//上次发放优惠券的时间

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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
    

    public function getCollectionName()
    {
        return 'send_coupon_log';
    }

 
}