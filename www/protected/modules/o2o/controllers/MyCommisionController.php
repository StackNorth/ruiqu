<?php 
/**
 * 企业微信号"我的提成"控制器
 * @author     2015-12-09
 */
class MyCommisionController extends CController {

    public $layout = 'qyindex';

    /**
     * 我的提成首页，默认显示本月数据
     */
    public function actionIndex() {
        // 环境判断
         if (ENVIRONMENT == 'product') {
        //if (false) {
            if (isset($_COOKIE['weixin_userid']) && isset($_COOKIE['weixin_userid_signature'])) {
                $signature = md5($_COOKIE['weixin_userid'].'wozhua=9527');
                if ($signature == $_COOKIE['weixin_userid_signature']) {
                    $userid = $_COOKIE['weixin_userid'];
                } else {
                    $this->render('error', ['msg' => '未查询到用户']);die;
                }
            } else {
                $reditect = "admin.yichenguanjia.com/index.php?r=o2o/myCommision/checkUserid";
                $reditect = urlencode($reditect);
                $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2b458d9de41d0622&redirect_uri='.$reditect.'&response_type=code&scope=snsapi_base&state=5e2b4706179f774e94903e1213d2222e#wechat_redirect');
                }
        } else {
            $userid = Yii::app()->request->getParam('weixin_userid', '');
        }

        $tech = TechInfo::getByUserid($userid);
        if (!$tech) {
            $this->render('error', ['msg' => '未查询到用户']);die;
        }
        $username = $tech->name;
        $user = $tech->_id;
        $timelist = O2oApp::getTimeList();

        $info = array(
            'userid'   => $userid,
            'username' => $username,
            'user'     => $user,
            'timelist' => $timelist,
        );
        $data = array_merge($info);
        $this->render('index', $data);
    }

    /**
     * 获取userid保存至cookie
     */
    public function actionCheckUserid() {
        $check = O2oApp::checkURI(1);
        if (!$check['success']) {
            $this->render('error', $check);die;
        } else {
            $userid = $check['userid'];
        }
        setcookie('weixin_userid', $userid);
        setcookie('weixin_userid_signature', md5($userid.'wozhua=9527'));
        $this->redirect(['index']);
    }

    public function actionList() {
        $start  = Yii::app()->request->getParam('start', 0);
        $end    = Yii::app()->request->getParam('end', 0);
        $userid = Yii::app()->request->getParam('userid', '');

        $start = strtotime($start);
        $end = $end == 0 ? strtotime('+1 month', $start) : $end;

        $o2oApp = new O2oApp($userid);
        $commisionData = $o2oApp->getCommision($start, $end, true);
        echo json_encode($commisionData);
    }

    public function actionInfo() {
        $order = Yii::app()->request->getParam('order', '');
        $user  = Yii::app()->request->getParam('user', '');
        $type  = intval(Yii::app()->request->getParam('type', 0));

        $this->layout = 'qyinfo';
        $data = array(
            'order' => $order,
            'user'  => $user
        );

        if ($type == 0) {
            $this->render('info_order', $data);
        } else {
            $this->render('info_append', $data);
        }
    }

}