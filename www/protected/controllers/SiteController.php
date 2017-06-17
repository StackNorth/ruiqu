<?php

class SiteController extends AdminController
{
	/**
	 * 后台首页
	 */
	public function actionIndex()
	{
		$admin_info = $this->getAdminInfo();
		$admin_user = Yii::app()->user->getId();
		$data = array('user_name' => '');
        $data['has_fake_user'] = false;
		if ($admin_info){
			$data['user_name'] = $admin_info['name'];
            $data['has_fake_user'] = $admin_info['fake_users'] ? true : false;
		}


		$criteria = new EMongoCriteria();
    	$criteria->status('==', 1);
        $criteria->sort('sort', EMongoCriteria::SORT_ASC);  
    	$cursor = AdminMenuAR::model()->findAll($criteria);
    	$auth = Yii::app()->authManager;
    	$filter_ids = array();
    	$is_super_admin = $this->isSuperAdmin();
    	if (!$is_super_admin){
    		$filter_parent = array();
    		foreach ($cursor as $v){
    			if (in_array($v->_id, $filter_ids) || ($v->auth_item != '' && $auth->checkAccess($v->auth_item, $admin_user))){
    				if ($v->parent != '' && !in_array($v->parent, $filter_ids)){
    					$filter_ids[] = $v->parent;
    					$filter_parent[] = $v->parent;
    				}
    				if (!in_array($v->_id, $filter_ids)){
    					$filter_ids[] = $v->_id;
    				}				
    			} 
    		}
    		//临时处理3级深层目录的情况，后面改为code来优化
    		foreach ($cursor as $v){
    			if (in_array($v->_id, $filter_parent) && $v->parent != ''){
    				$filter_ids[] = $v->parent;
    			}
    		}
    	}
    	$rows = array();
    	$index = array();    	
    	foreach ($cursor as $v){
    		$temp = $v->attributes;
    		$temp['_id'] = (string)$temp['_id'];
    		$temp['parent'] = (string)$temp['parent'];
    		if ($temp['url'] != ''){    			
    			$index[$temp['_id']] = array(
    				'name' => $temp['name'],
    				'url' => Yii::app()->request->baseUrl . $temp['url'],
    				'id' => $temp['_id']
    			);
    		}
    		if ($is_super_admin || in_array($v->_id, $filter_ids)){
    			$rows[] = $temp;
    		}
    	}
    	$data['menu_index'] = $index;
    	$data['menu'] = CommonFn::composeTreeData($rows);

        // 判断服务器(测试版or正式版)
        if (ENVIRONMENT == 'test') {
            $data['site'] = 'test';
        } else if (ENVIRONMENT == 'develop') {
            $data['site'] = 'dev';
        } else {
            $data['site'] = 'admin'; 
        }

		$this->render('index_new', $data);
	}

	/**
	 * 登录页面
	 */
	public function actionLogin()
	{
		$model = new LoginForm();
		if (isset($_POST['LoginForm'])){
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login()){
		    	$this->redirect(Yii::app()->user->returnUrl);
		    }
		}
		$this->renderPartial('login', array('model'=>$model));
	}
	
	/**
	 * 注册页面
	 */
	public function actionRegister(){
		$model = new RegisterForm('register');
        if (isset($_POST['RegisterForm'])){
			$model->attributes = $_POST['RegisterForm'];
            if ($model->validate()){
                $user = $model->register();
				$this->redirect(Yii::app()->homeUrl);
		    }
		}
		$this->renderPartial('register', array('model'=>$model));
	}

	/**
	 * 退出登录
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionError(){
		if ($error=Yii::app()->errorHandler->error){
			if(ENVIRONMENT != 'product'){
				var_dump($error);die();
			}
			if(Yii::app()->request->isAjaxRequest){
				echo $error['message'];
			} else {
				$this->renderPartial('error', $error);
			}	
		}
	}

    public function actionTest(){

        $list = new ARedisList('o2o_after_pay_success567');
        $list->push('abc');
        while ($list->getCount() > 0) {
            try {
                $res = $list->pop();
                var_dump($res);exit;
            } catch (Exception $e) {
                continue;
            }
        }
       /* $redis = new Redis();
        $redis->connect('10.9.160.211', 6379);
        $key = "testkey";
        $tvalue = "testvalue";
        $redis->set($key, $tvalue);
        $nvalue = $redis->get($key);
        print_r($nvalue . "\n");*/
    }
}