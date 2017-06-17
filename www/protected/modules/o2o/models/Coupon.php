<?php
/**
 * User: charlie
 * 代金券
 */
class Coupon extends MongoAr
{
    public $_id;

    public $value;//代金券面额

    public $name;//代金券别名

    public $pic=array();//代金券的图片

    public $min_price;//满XX元才可以使用

    public $status=0;//代金券状态 0=>暂停使用  1=>正常使用   -1=>已删除

    public $memo;//备注

    public $alias_name;//别名

    public $type;//代金券的适用类型     array(1=>array("name"=>"宠物洗澡"),2=>array("name"=>"宠物剪毛"),3=>array("name"=>"宠物美容"))

    public $workday_limit=0;  // 工作日非工作日限制 0: 无限制; 1: 仅限工作日使用; 2: 仅限周末使用

    public $time_limit_start;   // 时间限制 0-24的数字
    public $time_limit_end;     // 时间限制 0-24的数字

    public $time;   // 代金券创建的时间

    public static $status_option = array(
        0 => array('name' => '暂停使用'),
        1 => array('name' => '正常使用'),
        -1 => array('name' => '已删除')
    );

    public static $workday_limit_option = array(
        0 => array('name' => '无限制'),
        1 => array('name' => '仅限工作日'),
        2 => array('name' => '仅限周末'),
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
        return 'coupons';
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

        $newRow['value'] = CommonFn::get_val_if_isset($row,'value',10);
        $newRow['name'] = CommonFn::get_val_if_isset($row,'name','');
        $newRow['alias_name'] = CommonFn::get_val_if_isset($row,'alias_name','');
        $newRow['memo'] = CommonFn::get_val_if_isset($row,'memo','');
        $newRow['min_price'] = CommonFn::get_val_if_isset($row,'min_price',100);

        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);

        $newRow['type'] = CommonFn::get_val_if_isset($row,'type',0);
        if($newRow['type'] == 0){
            $newRow['type_str'] = '全部';
        }else{
            $newRow['type_str'] = Yii::app()->params['o2o_service'][$newRow['type']]['name'];
        }

        $newRow['workday_limit'] = CommonFn::get_val_if_isset($row, 'workday_limit', 0);
        $newRow['workday_limit_str'] = self::$workday_limit_option[$newRow['workday_limit']]['name'];

        $newRow['time_limit_start'] = CommonFn::get_val_if_isset($row, 'time_limit_start', '');
        $newRow['time_limit_end'] = CommonFn::get_val_if_isset($row, 'time_limit_end', '');
        $newRow['time_limit_str'] = '';
        if($newRow['workday_limit']==1){
            $newRow['time_limit_str'] = '工作日';
        }elseif($newRow['workday_limit'] == 2){
            $newRow['time_limit_str'] = '周末';
        }
        if($newRow['time_limit_start'] && $newRow['time_limit_end']){
            $newRow['time_limit_str'] = $newRow['time_limit_str'].' '.$newRow['time_limit_start'].'点-'.$newRow['time_limit_end'].'点';
        }
        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        if(APPLICATION=='api'){
            //unset($newRow['status']);
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
            unset($newRow['memo']);
        }

        return $this->output($newRow,$output);
    }

}