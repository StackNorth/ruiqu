<?php
/**
 * Created by PhpStorm.
 * User: north
 * Date: 2017/6/16
 * Time: 上午11:11
 */
class Balance extends MongoAr{
    public $_id;
    public $app_kind;
    public $uid;
    public $order_num;
    public $cash_flow_name;
    public $w_order_name;
    public $pay_money;
    public $init_money;
    public $surplus_money;
    public $from;
    public $type;
    public $create_time;
    public $create_time_i;
    public $deal_time;
    public $deal_time_i;
    public $type_name;
    public static $channel_option = array(
        1 => '余额充值',
        2 => '约服务',
        3 => '电商交易',
        4 => '社区【付费、打赏】',
        5 => '提现',

    );

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_ruiqu'));
        parent::__construct($scenario);
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'balance';
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

        $newRow['app_kind'] = CommonFn::get_val_if_isset($row,'app_kind','');
        $newRow['uid'] = CommonFn::get_val_if_isset($row,'uid','');
        $newRow['order_num'] = CommonFn::get_val_if_isset($row,'order_num','');
        $newRow['cash_flow_name'] = CommonFn::get_val_if_isset($row,'cash_flow_name','');
        $newRow['w_order_name'] = CommonFn::get_val_if_isset($row,'w_order_name','');
        $newRow['pay_money'] = CommonFn::get_val_if_isset($row,'pay_money','');
        $newRow['init_money'] = CommonFn::get_val_if_isset($row,'init_money','');
        $newRow['surplus_money'] = CommonFn::get_val_if_isset($row,'surplus_money');
        $newRow['from'] = CommonFn::get_val_if_isset($row,'from','');
        $newRow['type'] = CommonFn::get_val_if_isset($row,'type','');
        $newRow['create_time'] = CommonFn::get_val_if_isset($row,'create_time','');
        $newRow['create_time_i'] = CommonFn::get_val_if_isset($row,'create_time_i','');
        $newRow['deal_time'] = CommonFn::get_val_if_isset($row,'deal_time','');
        $newRow['deal_time_i'] = CommonFn::get_val_if_isset($row,'deal_time_i','');
        $newRow['type_name'] = CommonFn::get_val_if_isset($row,'type_name','');
        if(APPLICATION=='admin'){
            $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
            $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
            $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        }
        return $this->output($newRow,$output);
    }


}