<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 13-11-30
 * Time: 下午3:06
 * To change this template use File | Settings | File Templates.
 */
class UserController extends AdminController
{
    /**
     * 管理员管理
     */
    public function actionAdmin(){
        $res = array();
        $roles = $this->getRoles();
        $res['role'] = $roles;
        $res['status'] = CommonFn::getComboboxData(User::$status_option, 1, true, 10);
        //$res['type'] = CommonFn::getComboboxData(User::$type_option, 1, true, 10);
        $criteria = new EMongoCriteria();
        $criteria->status('==', 1);

        $this->render('admin', $res);
    }


    public function actiondblog(){
        $this->render('dblog');
    }

    public function actionFakes(){

        $criteria = new EMongoCriteria();
        $criteria->_id('==',Yii::app()->user->id);
        $admin_user = User::model()->find($criteria);
        $fake_users = CommonFn::get_val_if_isset($admin_user,'fake_users',array());
        $z_user = new ZUser();
        $fake_users_arr = $z_user->getUserInfo($fake_users);
        $fakes = RUser::model()->parse($fake_users_arr,true,array('id','user_name'));
        $_fakes = array();
        $_fakes[] = array("id"=>"100","user_name"=> "全部","selected"=>true);
        foreach($fakes as $fake){
            $_fakes[] = $fake;
        }
        echo(json_encode($_fakes));exit;
    }
    /**
     *  获取管理员的列表
     */
    public function actionGetUser(){
        $auth = Yii::app()->authManager;
        $params = CommonFn::getPageParams();
		$filter_type = intval(Yii::app()->request->getParam('filter_type', 1));
		$filter_status = intval(Yii::app()->request->getParam('filter_status', 1));
		$search = Yii::app()->request->getParam('search', '');
        $criteria = new EMongoCriteria($params);
        if ($filter_type < 10){
        	$criteria->type('==', $filter_type);
        }
        if ($filter_status < 10){
        	$criteria->status('==', $filter_status);
        }
        if ($search != ''){
        	if (is_numeric($search)){
        		$criteria->_id('or', intval($search));
        	}
        	$search = new MongoRegex('/' . $search . '/');
        	$criteria->email('or', $search);
        	$criteria->name('or', $search);
        }


        $cursor = User::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);

        $parsedRows = User::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
        // $codes = array();
        // $admin_ids = array();
        // foreach ($rows as $k => $v){
        // 	$admin_ids[] = $v['_id'];
        //     $roles = array_keys($auth->getAuthAssignments($v['_id']));
        //     $rows[$k]['reg_time'] = date("Y-m-d H:i", $v['reg_time']);

        //     if ($v['last_login'] == 0){
        //         $rows[$k]['last_login'] = '';
        //     } else {
        //         $rows[$k]['last_login'] = date("Y-m-d H:i", $v['last_login']);
        //     }
        //     $rows[$k]['role'] = implode(',', $roles);
        // }
        // //$zs = new ZService();


        // echo CommonFn::composeDatagridData($rows, $total);
    }

    /**
     *  更新管理员
     */
    public function actionUpdateUser(){
        $auth = Yii::app()->authManager;
        $_id = intval(Yii::app()->request->getParam('_id', 0));
        $type = intval(Yii::app()->request->getParam('type', 1));
        $status = intval(Yii::app()->request->getParam('status', 1));
        $name = Yii::app()->request->getParam('name', '');
        $email = Yii::app()->request->getParam('email', '');

        $role = Yii::app()->request->getParam('new_role');
        $roles = array_filter(explode(',', $role));

        $modify_password = Yii::app()->request->getParam('modify_password', 0);
        $new_password = Yii::app()->request->getParam('new_password', '');
        $confirm_new_password = Yii::app()->request->getParam('confirm_new_password', '');
        if ($modify_password == 1 && $new_password != $confirm_new_password){
			CommonFn::requestAjax(false, '两次输入的密码不同');
		}

        $last_admin = false;
        if ($auth->isAssigned($auth->super_admin, $_id)){
            $last_admin = $auth->checkLastSuperAdmin();
        }
        if ($last_admin && $status != 1){
            CommonFn::requestAjax(false, '最后一个超级管理员不能修改状态');
        }

        if (empty($email) || !preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email ) ){
            CommonFn::requestAjax(false, '邮箱填写错误');
        }

        $criteria = new EMongoCriteria();
        $criteria->_id('==', $_id);
        $user = User::model()->find($criteria);

        $user->status = $status;

        $user->type = $type;
        $user->email = $email;
        $user->name = $name;

        // 如果角色为保洁师
        if (in_array('保洁师', $roles)) {
            // 用户信息与保洁师信息同步
            $techInfo = TechInfo::get($_id);
            if (!$techInfo) {
                $techInfo = new TechInfo();
                $techInfo->_id = $_id;
            }
            $techInfo->name = $name;
            $techInfo->status = $status;
            $techInfo->save();
        }

        // 修改密码
        if ($modify_password == 1){
            $user->pass = md5($new_password);
        }
        $user->save();
        //新增管理区域划分
        $criteria = new EMongoCriteria();
        $criteria->type('==', 2);
        $criteria->user('==', $_id);

        $old_roles = array_keys($auth->getAuthAssignments($_id));
        foreach ($old_roles as $v){
            if (!in_array($v, $roles)){
                if ($v == $auth->super_admin && $last_admin){
                    CommonFn::requestAjax(false, '最后一个超级管理员不能取消超级管理员角色');
                }
                $auth->revoke($v, $_id);
            }
        }
        foreach ($roles as $v){
            if (!$auth->isAssigned($v, $_id)){
                $auth->assign($v, $_id);
            }
        }

        $auth->save();
        CommonFn::requestAjax();
    }

    /**
     *  获取DB log列表
     */
    public function actionGetDBLogList()
    {
        $params['db_name'] = "backend";
        $criteria = new EMongoCriteria($params);
        $dbLog = DbAction::model()->findAll($criteria);
        CommonFn::showList($dbLog,"dblog");

        //echo CommonFn::composeDatagridData($dbLog,count($dbLog));
    }

    /**
     * 获取全部角色
     */
    public function getRoles(){
        $auth = Yii::app()->authManager;
        $all_task = $auth->getAuthItems(2);
        $rows = array();
        foreach ($all_task as $k => $v){
            $rows[] = array('value' => $k, 'text' => $k);
        }
        return $rows;
    }
    
    public function actions()
    {
        return array(
            'multipleSetStatus' => array(
            	'class' => 'application.controllers.multipleSet.PostDataAction',
            	'model' => User::model(),
            	'field' => 'status'
            ),
            'multipleSetType' => array(
            	'class' => 'application.controllers.multipleSet.PostDataAction',
            	'model' => User::model(),
            	'field' => 'type'
            )
        );
    }
}