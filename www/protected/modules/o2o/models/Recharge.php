<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/9/26
 * Time: 11:57
 * 会员充值卡模型
 */
class Recharge extends MongoAr
{
    public $_id;

    public $denomination;//充值面额

    public $coupons = array();//赠送的优惠券

    public $cash_back;//返现的金额

    public $desc;//介绍

    public $status=1;//充值卡状态  1 正常使用   0 暂停使用   -1 已删除
    public $order;//排序权重

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static $status_option = array(
        1 => array('name' => '正常'),
        0 => array('name' => '暂停'),
        -1 => array('name' => '删除')
    );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'recharge';
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
        $newRow['denomination'] = CommonFn::get_val_if_isset($row,'denomination',0);
        $newRow['cash_back'] = CommonFn::get_val_if_isset($row,'cash_back',0);
        $newRow['desc'] = CommonFn::get_val_if_isset($row,'desc','');
        $newRow['order'] = CommonFn::get_val_if_isset($row,'order',1);

        $coupons = array();
        if(isset($row['coupons'])&&is_array($row['coupons'])&&!empty($row['coupons'])){
            $where = array('_id' => array('$in' => array_values($row['coupons'])));
            $cursor = Coupon::model()->getCollection()->find($where, array('_id','name'));
            foreach ($cursor as $v){
                $_id = (array)$v['_id'];
                unset($v['_id']);
                $v['id'] = $_id['$id'];
                $coupons[] = $v;
            }
        }

        $newRow['coupons'] = $coupons;
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);
        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");
        if  (APPLICATION=='api'||APPLICATION=='common'){
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
        }
        return $this->output($newRow,$output);
    }


}
