<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/9/26
 * Time: 11:57
 * 用户余额
 */
class BalanceLog extends MongoAr
{
    public $_id;

    public $user;//用户 mongoid

    public $time;//时间

    public $memo;//说明


    public $type;//操作类型   'recharge  充值;admin_recharge  后台充值;order  下订单;send  赠送;other  其他'

    public $amount;//金额  可以为负数

    public static $type_option = array(
        'recharge' => '充值',
        'admin_recharge' => '后台充值',
        'order' => '下订单',
        'send' => '赠送',
        'other' => '其他',
    );

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
        return 'balance_log';
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

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];

        $newRow['memo'] = CommonFn::get_val_if_isset($row,'memo','');

        $newRow['type'] = CommonFn::get_val_if_isset($row,'type','other');
        $newRow['type_str'] = self::$type_option[$newRow['type']];

        $newRow['time'] = CommonFn::get_val_if_isset($row,'time',time());

        $newRow['time_str'] = CommonFn::sgmdate("Y年n月d日", $newRow['time'],1);

        $newRow['amount'] = CommonFn::get_val_if_isset($row,'amount',0);

        $user = array();
        $t_user = new ZUser();
        if(isset($row['user'])){
            $_user = $t_user->get($row['user']);
            $user = RUser::model()->parseRow($_user->attributes,array('id','user_name'));
        }
        $newRow['user'] = $user;


        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");


        return $this->output($newRow,$output);
    }
}