<?php
/**
 * mongodb模型自定义基类
 */
class MongoAr extends EMongoDocument
{
    protected $_rmongoDb;
    protected $_scenario_error = '';    //数据库操作的错误信息
    protected $_action_info = '';    //数据库操作的提示信息
    protected $_need_log = true;        //是否需要记录
    public $_need_ruser_log = false; //是否需要记录前台用户操作
    protected $_action_log = '';        //操作记录
    public static $c_time = 0;
    
    public function __construct($scenario='insert'){
        parent::__construct($scenario);
        self::$c_time = time();
        $this->onAfterSave = function($event){
            $model = $event->sender;
            if (Yii::app()->params['app'] == 'console' || !$model->getNeedLog()){
                return false;
            }
            $action_user = Yii::app()->user->getId();
            $user_type = '';
            if (!$action_user){
                $ruser = Yii::app()->request->getParam('user_id','');
                if(!empty($ruser) && $this->_need_ruser_log){
                    $ruser = CommonFn::getObj($ruser,'ZUser');
                    if($ruser){
                        $action_user = $ruser->_id;
                        $user_type = 'ruser';
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }           
            $db_name = $model->getMongoDBComponent()->dbName;
            $c_name = $model->getCollectionName();
            $scenario = $model->getScenario();
            $_id = $model->_id;
            //echo($_id);exit;
            $action_log = $model->getActionLog();
            $result = DbAction::model()->getCollection()->findAndModify(
                array('db_name' => $db_name, 'c_name' => $c_name, 'r_id' => $_id),
                array('$push' => array('action' => array('user' => $action_user,'user_type'=>$user_type ,'time' => time(), 'scenario' => $scenario, 'action_log' => $action_log))), 
                null, 
                array('new' => true, 'upsert' => true)
            );
        };
    }

    public function setActionLog($log){
        $this->_action_log = $log;
    }
    
    public function getActionLog(){
        return $this->_action_log;
    }


    //格式化输出
    public function parse($obj,$muti=true,$outputArr=array()){
        if($muti){
            $newRows = array();
            foreach ($obj as $k => $v){
                $newRows[$k] = $this->parseRow($v,$outputArr);
            }
            return $newRows;
        }else{
            return $this->parseRow($obj,$outputArr);
        }
    }

    public function parseRow($row,$outputArr=array()){
        return $this->output($row,$outputArr);
    }

    public function output($row,$outputArr = array()){
        $newRow = array();
        if(is_array($outputArr) && !empty($outputArr)){
            foreach($row as $k=>$v){
                if(in_array($k,$outputArr)){
                    $newRow[$k] = $v;
                }
            }
        }else{
            $newRow = $row;
        }
        return $newRow;
    }
    
    public function getCollectionName(){
        return '';
    }
    
    /**
     * 获取自增的id
     */
    public function get_new_id(){
        $collection = $this->getCollectionName();
        if ($collection == ''){
            return 0;
        }
        $cursor = AutoIncrement::model()->getCollection()->findAndModify(array('_id' => $collection), array('$inc' => array('currentIdValue' => 1)), array('currentIdValue' => 1), array('new' => true, 'upsert' => true));
        $_id = $cursor['currentIdValue'];
        return $_id;
    }
    
    /**
     * 获取自增的id
     */
    public function getNewId($step=1){
        $collection = $this->getCollectionName();
        if ($collection == ''){
            return 0;
        }
        $ai_model = AutoIncrement::model();
        $ai_model->setMongoDBComponent($this->getMongoDBComponent());
        $cursor = $ai_model->getCollection()->findAndModify(array('_id' => $collection), array('$inc' => array('currentIdValue' => $step)), array('currentIdValue' => 1), array('new' => true, 'upsert' => true));
        $_id = $cursor['currentIdValue'];
        return $_id;
    }
    
    /**
     * 重写获取数据库组件函数，使每个模型的数据库独立
     */
    public function getMongoDBComponent()
    {
        if($this->_rmongoDb===null)
            $this->_rmongoDb = Yii::app()->getComponent('mongodb');

        return $this->_rmongoDb;
    }
    
    /**
     * 重写设置数据库组件函数，使每个模型的数据库独立
     */
    public function setMongoDBComponent(EMongoDB $component)
    {
        $this->_rmongoDb = $component;
    }
    
    /**
     * 设置错误信息
     */
    public function setScenarioError($message){
        $this->_scenario_error = $message;
    }
    
    /**
     * 获取错误信息
     */
    public function getScenarioError(){
        return $this->_scenario_error;
    }

    /**
     * 设置提示信息
     */
    public function setActionInfo($message){
        $this->_action_info = $message;
    }
    
    /**
     * 获取提示信息
     */
    public function getActionInfo(){
        return $this->_action_info;
    }
    
    /**
     * 是否需要操作记录
     */
    public function getNeedLog(){
        return $this->_need_log;
    }
}