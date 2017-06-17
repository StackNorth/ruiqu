<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 13-12-20
 * Time: 下午4:24
 * To change this template use File | Settings | File Templates.
 */
class ZComponent extends CComponent
{
    public $c_time = 0;
    
    public function __construct(){
        $this->c_time = time(); 
    }
    
    /**
     * 获取单个数据模型信息
     */
    protected function getMultipleModelInfo($model, $condition, $fields=null){          
        $cursor = $model->getCollection()->find($condition, $fields);
        $model_info = array();
        foreach ($cursor as $v){
         $model_info[] = $v;
        }
        return $model_info;
    }

    //判断ID是否存在
    public function idExist($id){
        $obj = null;
        if(CommonFn::isMongoId($id)){
            $_id = new MongoId($id);
            $_obj = $this->get($_id);
            if($_obj&&isset($_obj->attributes)&&!empty($_obj->attributes)){
                $obj = $_obj->attributes;
            }
            return $obj;
        }
    }


    /**
     * 获取多个数据模型信息
     */
    protected function getSingleModelInfo($model, $condition, $fields=array(), $deault=''){
        //var_dump($fields);exit;
        $cursor = $model->getCollection()->findOne($condition, $fields);
        $key = null;
        if (is_array($fields) && count($fields) == 1){
            $keys = array_keys($fields);
            $key = $keys[0];
            if ($cursor){
                $model_info = $cursor[$key];
            } else {
                $model_info = $deault;
            }
        } else {
            $model_info = $cursor;
        }
        return $model_info;
    }

    protected function getList($model,$_ids, $fields=array(), $default=''){
        if (is_array($_ids)){
            $where = array('_id' => array('$in' => $_ids));
            $items = $this->getMultipleModelInfo($model, $where, $fields);
            $list = array();
            if (is_array($fields) && count($fields) == 1){
                $keys = array_keys($fields);
                $key = $keys[0];
                foreach ($items as $v){
                    $list[] = $v[$key];
                }
            } else {
                foreach ($items as $v){
                    $list[] = $v;
                }
            }
        } else {
            $where = array('_id' => $_ids);
            $list = $this->getSingleModelInfo($model, $where, $fields, $default);
        }
        return $list;
    }

    protected function getOne($model,$_id){
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $_id);
        $_moudel = $model->find($criteria);

        return $_moudel;
    }
}