<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/9/26
 * Time: 11:57
 * 会员充值日志
 */
class RechargeLog extends MongoAr
{
    public $_id;

    public $user;//充值用户 mongoid

    public $time;//充值时间

    public $recharge;//充值卡  mongoid

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'recharge_log';
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