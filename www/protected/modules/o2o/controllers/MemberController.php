<?php 
/**
 * 微信成员管理控制器
 * @author     2015-11-30
 */
class MemberController extends AdminController {

    public function actionIndex() {

    }

    public function actionList() {
        // $criteria = new EMongoCriteria();
        // $criteria->is_qywx_member('==', 0);
        // $cursor = User::model()->findAll($criteria);
        // $rows = CommonFn::getRowsFromCursor($cursor);
    }

    public function actionEditMember() {
        $id         = Yii::app()->request->getParam('_id', -1);
        $userid     = Yii::app()->request->getParam('userid', '');
        $name       = Yii::app()->request->getParam('wx_name', '');
        $department = Yii::app()->request->getParam('department', array());
        $mobile     = Yii::app()->request->getParam('mobile', '');
        $position   = Yii::app()->request->getParam('position', '');
        $weixinid   = Yii::app()->request->getParam('weixinid', '');
        $email      = Yii::app()->request->getParam('email', '');
        $gender     = intval(Yii::app()->request->getParam('gender', 0));

        $userObj = User::get(intval($id));

        if ($id == -1) {
            CommonFn::requestAjax(false, '请选择用户');
        }

        foreach ($department as $key => $value) {
            $department[$key] = intval($value);
        }
        if (empty($department)) {
            $department[0] = 1;
        }
        // 请求微信企业号，添加成员
        $user_data = array(
            'userid'     => $userid,
            'name'       => $name,
            'department' => $department,
            'position'   => $position,
            'mobile'     => $mobile,
            'gender'     => $gender,
            'email'      => $email,
            'weixinid'   => $weixinid,
        );
        if ($gender == 0) {
            unset($user_data['gender']);
        }
        $option = WechatConfig::getIns()->getLinkOption();
        $secret = WechatConfig::getIns()->getSecret('admin_dev');
        $wechat = new QyWechat($option);
        if ($wechat->checkAuth($option['appid'], $secret)) {
            // 检查用户是否存在
            $userInfo = $wechat->getUserInfo($userid);
            if ($userInfo == false) {
                $result = $wechat->createUser($user_data);
                if ($result['errmsg'] != 'created') {
                    CommonFn::requestAjax(false, $result['errmsg']);
                }
            } else {
                $result = $wechat->updateUser($user_data);
                if ($result['errmsg'] != 'updated') {
                    CommonFn::requestAjax(false, $result['errmsg']);
                }
            }
        } else {
            CommonFn::requestAjax(false, '微信验证失败');
        }

        // 后台修改用户信息
        $userObj->is_member = 1;
        $userObj->userid = $userid;
        $wx_info = array(
            'name'       => $name,
            'department' => $department,
            'position'   => $position,
            'mobile'     => $mobile,
            'weixinid'   => $weixinid,
            'gender'     => $gender,
        );
        $userObj->wx_info = $wx_info;

        if ($gender == 0) {
            $wx_info['gender'] = isset($userObj->wx_info['gender']) ? $userObj->wx_info['gender'] : 0;
        }

        $success = $userObj->save(true, array('is_member', 'userid', 'wx_info'));

        CommonFn::requestAjax($success, '', array());
    }

}