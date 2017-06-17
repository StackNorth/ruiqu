<?php
/**
 * User: charlie
 * 服务点
 */
class Station extends MongoAr
{
    public $_id;
    public $status=0;//服务点状态 0=>待营业  1=>正常营业 -1=>暂停营业  -2=>已删除

    public $name;//服务点名字

    public $start_time=9;//开始服务时间  如9   每天9点开始
    public $end_time=20;//结束服务时间    如20    每天20点停止接受订单

    //public $city_info = array();          //所属的城市信息    "province"=>"上海","city"=>"上海","area"=>"浦东"  "business"=>"世纪公园"  或者   "province"=>"江苏","city"=>"苏州","area"="昆山"
    //public $address;//服务点详细地址
    //public $position=array();   //坐标
    public $address = array(); //地址信息   包含   province  city  area business position detail

    public $beauticians_count=1;  //保洁师数量
    public $coverage = array();//服务范围

    public $types = array();//该服务点  支持的服务项目  

    public static $status_option = array(
        0 => array('name' => '待营业'),
        1 => array('name' => '正常营业'),
        -1 => array('name' => '暂停营业'),
        -2 => array('name' => '已删除'),
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
        return 'stations';
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

        $newRow['name'] = CommonFn::get_val_if_isset($row,'name','');

        $newRow['start_time'] = CommonFn::get_val_if_isset($row,'start_time',9);
        $newRow['end_time'] = CommonFn::get_val_if_isset($row,'end_time',21);

        $newRow['address'] = CommonFn::get_val_if_isset($row,'address',array("province"=>"","city"=>"","area"=>"","detail"=>"","business"=>"","position"=>""));
        if(!isset($newRow['address']['province'])){
            $newRow['address']['province'] = '';
        }
        if(!isset($newRow['address']['city'])){
            $newRow['address']['city'] = '';
        }
        if(!isset($newRow['address']['area'])){
            $newRow['address']['area'] = '';
        }
        if(!isset($newRow['address']['business'])){
            $newRow['address']['business'] = '';
        }
        if(!isset($newRow['address']['detail'])){
            $newRow['address']['detail'] = '';
        }
        if(!isset($newRow['address']['position'])){
            $newRow['address']['position'] = array(121,31);
        }


        $newRow['coverage'] = CommonFn::get_val_if_isset($row,'coverage',array());

        $newRow['types'] = CommonFn::get_val_if_isset($row,'types',array());

        $newRow['beauticians_count'] = CommonFn::get_val_if_isset($row,'beauticians_count',1);

        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);

        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        if(APPLICATION=='api'){
            //unset($newRow['status']);
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
        }

        return $this->output($newRow,$output);
    }

}