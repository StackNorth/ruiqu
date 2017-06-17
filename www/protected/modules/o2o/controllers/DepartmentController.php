<?php 
/**
 * 企业号部门管理控制器
 * @author zhouxcuchen 2015-11-30
 */
class DepartmentController extends AdminController {

    /**
     * 后台管理部门列表首页
     */
    public function actionIndex() {
        
    }

    /**
     * 后台Ajax请求获取部门列表
     */
    public function actionGetDepartmentList() {
        $option = WechatConfig::getIns()->getLinkOption();
        $secret = WechatConfig::getIns()->getSecret('admin_dev');
        $wechat = new QyWechat($option);
        if ($wechat->checkAuth($option['appid'], $secret)) {
            $result = $wechat->getDepartment();
            if ($result['errmsg'] == 'ok') {
                $department = $result['department'];
                $departmentList = array();
                foreach ($department as $key => $row) {
                    $departmentList[$row['id']] = array(
                        'name'     => $row['name'],
                        'id'       => $row['id'],
                        'parentid' => $row['parentid'],
                    );
                }
                $data = array(
                    'code'    => 0,
                    'msg'     => 'success',
                    'content' => $departmentList
                );
            } else {
                $data = array(
                    'code' => 1,
                    'msg' => $result['errmsg'],
                    'content' => array(),
                );
            }
        } else {
            $data = array(
                'code' => 1,
                'msg' => '微信验证失败',
                'content' => array(),
            );
        }

        echo json_encode($data);
    }

}