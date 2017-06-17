<?php 
/**
 * 我的评价控制器
 * @author     2015-12-11
 */
class MyCommentController extends CController {

    public $layout = 'qyindex';

    /**
     * 我的评价首页
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
                $reditect = "admin.yichenguanjia.com/index.php?r=o2o/myComment/checkUserid";
                $reditect = urlencode($reditect);
                $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2b458d9de41d06221&redirect_uri='.$reditect.'&response_type=code&scope=snsapi_base&state=5e2b4706179f774e94903e1213d2222e#wechat_redirect');
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

    /**
     * 获取评价列表接口
     */
    public function actionList() {
        $start  = Yii::app()->request->getParam('start', 0);
        $end    = Yii::app()->request->getParam('end', 0);
        $userid = Yii::app()->request->getParam('userid', '');

        $start = strtotime($start);
        $end = $end == 0 ? strtotime('+1 month', $start) : $end;

        $o2oApp = new O2oApp($userid);
        $commentData = $o2oApp->getComment($start, $end, true);
        echo json_encode($commentData);
    }

    public function actionInfo() {
        $order = Yii::app()->request->getParam('order', '');
        $user = Yii::app()->request->getParam('user', '');
        $this->layout = 'qyinfo';
        $data = array();
        $data['order'] = $order;
        $data['user'] = $user;

        $this->render('info', $data);
    }

    

}