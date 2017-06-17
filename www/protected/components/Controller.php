<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
   	public $c_time;

	public function __construct($id, $module=null){
		parent::__construct($id, $module);
		$this->c_time = time();
		// $debug = Yii::app()->request->getParam('debug');
		// if ($debug !== null){
		// 	error_reporting(E_ALL);
		// 	ini_set('display_errors', '1');
		// }		
	}

	/**
	 * 获取管理员的信息
	 */
	public function getAdminInfo(){
		$admin_user = Yii::app()->user->getId();
		$criteria = new EMongoCriteria();
		$criteria->_id('==', $admin_user);
    $cursor = User::model()->find($criteria);
    return $cursor->attributes;
	}
	
	/** 
	 * Checks if srbac access is granted for the current user
   	 * @param String $action . The current action
   	 * @return boolean true if access is granted else false
   	 */
    protected function beforeAction($action) {
        if(APPLICATION == 'common'){
          Yii::app()->runController('common/index/index');
          die();
        }
        $za = new ZAuth();
        $access = $za->getAuthItem($this);
        if(substr(str_replace('http://', '', Yii::app()->request->hostInfo) , 0,3)=='api'){
                if(isset($this->module->id)&&$this->module->id=='api'){
                    return true;
                }else{
                    return false;
                }
        }
        if(substr(str_replace('http://', '', Yii::app()->request->hostInfo) , 0,5)=='admin'){
                if(isset($this->module->id)&&$this->module->id=='api'){
                    return false;
                }
        }
       //Always allow access if $access is in the allowedAccess array
       $always_allow = $this->allowedAccess();
       foreach ($always_allow as $k => $v){
       	$always_allow[$k] = strtolower($v);
       }
       if (in_array(strtolower($access), $always_allow)) {
        		return true;
       }
       // Check for access
       if (!Yii::app()->user->checkAccess($access)) {
       	if ($this->isSuperAdmin()){
       		return true;
       	} else {
       		return $this->onUnauthorizedAccess();
       	}
       } else {
        		return true;
       }
  	}
  	
  	/**
	 * 总是允许访问的操作
	 */
	protected function allowedAccess(){
		return array('sitelogin', 'siteregister', 'siteerror', 'sitelogout','admin-sitelogin');
	}
	
	/**
	 * 是否是超级管理员
	 */
	protected function isSuperAdmin(){
		$auth = Yii::app()->getAuthManager();
		$user_id = Yii::app()->user->getId();
		if (!$user_id){
			return false;
		}
		$user_auth = $auth->getAuthAssignment($auth->super_admin, $user_id);
		if ($user_auth){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 未通过验证
	 */
	protected function onUnauthorizedAccess(){
    	if (Yii::app()->user->isGuest) {
        if (Yii::app()->request->isAjaxRequest) {
            CommonFn::requestAjax(false, '请重新登陆');
        } else {
      	  Yii::app()->user->loginRequired();
        }
    	} else {
      		if (Yii::app()->request->isAjaxRequest) {
      			CommonFn::requestAjax(false, '你没有权限！');
      		} else {
      			$za = new ZAuth();
    			$access = $za->getAuthItem($this);
    			//列表管理已完成，登录后可以进入首页
    			if (strtolower($access) == 'siteindex'){
    				return true;
    			}		
        		throw new CHttpException(403, '你没有权限！', 403);
      		}
      		return false;
    	}
  	}
}