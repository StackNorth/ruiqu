<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 13-11-29
 * Time: 下午4:47
 * To change this template use File | Settings | File Templates.
 */
class AutoIncrement extends MongoAr
{
    public $_id;
    public $currentIdValue;
    protected $_need_log = false;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'auto_increment';
    }

}