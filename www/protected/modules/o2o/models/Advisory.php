<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/11/9
 * Time: 17:05
 * 咨询模型
 */
class Advisory extends MongoAr{
    public $_id;
    public $user_name;
    public $mobile;
    public $area;
    public $homeType;
    public $num;
    public $sex;
    public $type;//咨询类型
    public $tech_content;//服务内容
    public $status;//0 待处理 1 已处理
    public $time;//创建时间

    public static $status_option = array(
        1 => '待处理',
        2 => '已处理'
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
        return 'advisory';
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

        $newRow['user_name'] = CommonFn::get_val_if_isset($row,'user_name','');
        $newRow['mobile'] = CommonFn::get_val_if_isset($row,'mobile','');
        $newRow['area'] = CommonFn::get_val_if_isset($row,'area','');
        $newRow['homeType'] = CommonFn::get_val_if_isset($row,'homeType','');
        $newRow['num'] = CommonFn::get_val_if_isset($row,'num','');
        $newRow['sex'] = CommonFn::get_val_if_isset($row,'sex','');
        $newRow['tech_content'] = CommonFn::get_val_if_isset($row,'tech_content','');
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status');
        $newRow['time'] = CommonFn::get_val_if_isset($row,'time','');
        $newRow['type'] = CommonFn::get_val_if_isset($row,'type','');
        if(APPLICATION=='admin'){
            $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
            $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
            $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        }
        return $this->output($newRow,$output);
    }


}