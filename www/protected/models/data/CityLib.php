<?php
class CityLib extends MongoAr
{
    public $_id;

    public $name;//地名

    public $parent_province_id = 0; //归属省

    public $parent_city_id = 0; //归属市

    public $parent_area_id = 0; //归属县,预留


    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_data'));
        parent::__construct($scenario);
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'city_lib';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['city_code'] = $row['_id'];

        $newRow['name'] = CommonFn::get_val_if_isset($row,'name','');
        $newRow['parent_province_id'] = CommonFn::get_val_if_isset($row,'parent_province_id',0);
        $newRow['parent_city_id'] = CommonFn::get_val_if_isset($row,'parent_city_id',0);
        $newRow['parent_area_id'] = CommonFn::get_val_if_isset($row,'parent_area_id',0);

        // if($newRow['parent_city_id']){
        //     $newRow['level'] = 3;
        // }elseif ($newRow['parent_province_id']) {
        //     $newRow['level'] = 2;
        // }else{
        //     $newRow['level'] = 1;
        // }
        if(APPLICATION=='api'||APPLICATION=='common'){
            unset($newRow['parent_province_id']);
            unset($newRow['parent_city_id']);
            unset($newRow['parent_area_id']);
        }
        return $this->output($newRow,$output);
    }
}