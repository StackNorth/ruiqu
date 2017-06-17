<?php
/**
 * summary: 更新目录列表
 * author: justin
 * date: 2014.03.04
 */
class PostDataAction extends CAction
{
	public $scenario = '';
	
    public function run(){
        $status = intval(Yii::app()->request->getParam('status', 1));
    	$level = intval(Yii::app()->request->getParam('level', 1));
    	$name = Yii::app()->request->getParam('name', '');
    	$url = Yii::app()->request->getParam('url', '');
    	$sort = intval(Yii::app()->request->getParam('sort', 1));
    	$_id = Yii::app()->request->getParam('_id', '');
    	$parent = Yii::app()->request->getParam('parent', '');
    	$controller = $this->getController();
    	$success = false;
    	$message = '';
    	$data = array();
    	switch($this->scenario){
    		case 'insert':
    			$parent_id = '';
    			if ($parent != ''){
    				$parent_id = new MongoId($parent);
    			}
    			$am = new AdminMenuAR();
    			$am->status = $status;
    			$am->name = $name;
    			$am->parent = $parent_id;
    			$am->level = $level;
    			$am->url = $url;
    			$am->sort = $sort;
    			$success = $am->save();
    			$message = $am->getScenarioError();
    			$data['_id'] = (string)$am->_id;
    			break;
    		case 'update':
    			$criteria = new EMongoCriteria();
    			$criteria->_id('==', new MongoId($_id));
    			$am = AdminMenuAR::model()->find($criteria);
    			$am->url = $url;
    			$am->status = $status;
    			$am->name = $name;
    			$am->sort = $sort;
    			$success = $am->save(true, array('status', 'name', 'url', 'sort'), true);
    			$message = $am->getScenarioError();
    			break;
    		case 'delete':
    			$criteria = new EMongoCriteria();
    			$criteria->_id('==', new MongoId($_id));
    			$am = AdminMenuAR::model()->find($criteria);
    			$am->status = -1;   			
    			$success = $am->save(true, array('status'), true);
    			break;
    		default:
    			break;
    	}
    	CommonFn::requestAjax($success, $message, $data);
    }
}