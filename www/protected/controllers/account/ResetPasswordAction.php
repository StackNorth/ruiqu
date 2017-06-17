<?php
/**
 * summary: 重置用户密码
 * author: justin
 * date: 2014.02.12
 */
class ResetPasswordAction extends CAction
{
    public function run(){
        $_id = Yii::app()->request->getParam('_id', '');
        $cat = Yii::app()->request->getParam('cat', '');
        $controller = $this->getController();
        if ($cat == 'property'){
        	$criteria = new EMongoCriteria();
	    	$user_id = new MongoId($_id);
	    	$criteria->_id('==', $user_id);
	    	$user = RUser::model()->find($criteria);
	    	$user->password = md5($controller->default_password);
	    	$success = $user->save(true, array('password'), true);
	    	$message = $user->getScenarioError();
	    	CommonFn::requestAjax($success, $message);
        } else if ($cat == 'stores'){
        	$criteria = new EMongoCriteria();
    		$user_id = new MongoId($_id);
    		$criteria->_id('==', $user_id);
    		$stores = Stores::model()->find($criteria);
    		$stores->password = md5($controller->default_password);
	    	$success = $stores->save(true, array('password'), true);
	    	$message = $stores->getScenarioError();
	    	CommonFn::requestAjax($success, $message);
        }       
    }
}