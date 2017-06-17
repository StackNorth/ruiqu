<?php
/**
 * 管理员的数据库操作记录模型
 * add by justin
 * 2013.12.30
 */
class DbAction extends MongoAr
{
    public $_id;
    public $db_name;                //数据库名 
    public $c_name;                 //集合名
    public $r_id;                   //数据标识
    public $action;                 //操作
    protected $_need_log = false;
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'db_action';
    }
}