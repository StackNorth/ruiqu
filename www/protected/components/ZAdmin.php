<?php
/**
 * summary: 管理员组件
 * author: justin
 * date: 2014.01.03
 */
class ZAdmin extends ZComponent
{
    /**
     * 返回管理员名称
     */
    public function getAdminNames($_ids){
        $criteria = new EMongoCriteria();
        $criteria->_id('in', $_ids);
        $cursor = User::model()->findAll($criteria);
        $admin_names = array();
		foreach ($cursor as $v){
			$admin_names[$v->_id] = $v->name;
		}
        return $admin_names;
    }
    
    /**
     * 根据名称返回管理员信息
     */
    public function getAdminFromName($name){
    	$criteria = new EMongoCriteria();
        $criteria->name('==', $name);
        $cursor = User::model()->find($criteria);
        return $cursor;
    }
    
    /**
     * 返回拥有某一权限的管理员列表
     */
    public function getAuthUser($name){
        $auth = Yii::app()->authManager;
        $task_user_ids = $auth->getAuthUser($name);
        $criteria = new EMongoCriteria();
        $criteria->_id('in', $task_user_ids);
        $cursor = User::model()->findAll($criteria);
        $data = array();
        foreach ($cursor as $v){
        	$data[$v->_id] = $v->attributes;
        }
        return $data;
    }
    
    /**
	 *  获取管理员信息
	 */
	public function getAdminInfo($_ids, $fields=null, $default=''){
		if (is_array($_ids)){
			$where = array('_id' => array('$in' => $_ids));	
			$items = $this->getMultipleModelInfo(User::model(), $where, $fields);
			$info = array();
			if (is_array($fields) && count($fields) == 1){
				$keys = array_keys($fields);
				$key = $keys[0];
				foreach ($items as $v){
					$info[(string)$v['_id']] = $v[$key];
				}
			} else {
				foreach ($items as $v){
					$info[(string)$v['_id']] = $v;
				}
			}			
		} else {
			$where = array('_id' => $_ids);	
			$info = $this->getSingleModelInfo(User::model(), $where, $fields, $default);
		}		
		return $info;
	}
}