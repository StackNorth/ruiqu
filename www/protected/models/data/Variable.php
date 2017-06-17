<?php
class Variable extends MongoAr
{
    public $_id;//配置名作为主键
    public $value;


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
        return 'variable';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['key'] = (string)$row['_id'];
        $newRow['value'] = CommonFn::get_val_if_isset($row,'value',0);
        return $this->output($newRow,$output);
    }

}