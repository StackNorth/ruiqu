<?php 
/**
 * 我的订单控制器
 * @author     2015-12-15
 */
class MyOrderController extends CController {

    public $layout = 'qyindex';

    public function actionIndex() {
        // 环境判断
         if (ENVIRONMENT == 'product') {
        // if (false) {
            if (isset($_COOKIE['weixin_userid']) && isset($_COOKIE['weixin_userid_signature'])) {
                $signature = md5($_COOKIE['weixin_userid'].'wozhua=9527');

                if ($signature == $_COOKIE['weixin_userid_signature']) {
                    $userid = $_COOKIE['weixin_userid'];
                } else {
                    $this->render('error', ['msg' => '未查询到用户']);die;
                }
            } else {
                $reditect = "admin.yichenguanjia.com/index.php?r=o2o/myOrder/checkUserid";
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
            'userid' => $userid,
            'username' => $username,
            'user' => $user,
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
        $userid = Yii::app()->request->getParam('userid', 0);
        $start = strtotime($start);
        $end = $end == 0 ? strtotime('+1 month', $start) : $end;

        $o2oApp = new O2oApp($userid);

        $orderData = $o2oApp->getOrder($start, $end, true);

        echo json_encode($orderData);
    }

    public function actionInfo() {
        $order = Yii::app()->request->getParam('order', '');
        $user  = Yii::app()->request->getParam('user', '');

        $this->layout = 'qyinfo';
        $data = array(
            'order' => $order,
            'user'  => $user
        );

        $this->render('info', $data);
    }
    /**
     * 保洁师确认订单
     */
    public function actionTechConfirmOrder() {
        $order = Yii::app()->request->getParam('order', '');
        $user  = intval(Yii::app()->request->getParam('user', 0));

        if (!CommonFn::isMongoId($order) || $user == 0) {
            O2oApp::response(false, '订单或用户不存在', []);
        }

        $order_obj = ROrder::get(new MongoId($order));
        $flag = false;
        foreach($order_obj->technicians as $data){
            if ($user == $data['technician_id']) {
                $flag = true;break;
            }
        }
        if (!$order_obj || !$flag) {
            O2oApp::response(false, '用户或订单ID错误', []);
        }

        $order_obj->status = 3;
        $success = $order_obj->save();
        O2oApp::response($success, '保存失败，请重试', []);
    }
    /**
     * 保洁师确认出发
     */
    public function actionTechSetout() {
        $order = Yii::app()->request->getParam('order', '');
        $user  = intval(Yii::app()->request->getParam('user', 0));

        if (!CommonFn::isMongoId($order) || $user == 0) {
            O2oApp::response(false, '订单或用户不存在', []);
        }

        $order_obj = ROrder::get(new MongoId($order));
        $flag = false;
        foreach($order_obj->technicians as $data){
            if ($user == $data['technician_id']) {
                $flag = true;break;
            }
        }
        if (!$order_obj || !$flag) {
            O2oApp::response(false, '用户或订单ID错误', []);
        }

        $order_obj->status = 4;
        $success = $order_obj->save();
        O2oApp::response($success, '保存失败，请重试', []);
    }

    /**
     * 保洁师确认上门
     */
    public function actionTechCome() {
        $order = Yii::app()->request->getParam('order', '');
        $user  = intval(Yii::app()->request->getParam('user', 0));

        if (!CommonFn::isMongoId($order) || $user == 0) {
            O2oApp::response(false, '订单或用户不存在', []);
        }

        $order_obj = ROrder::get(new MongoId($order));
        $flag = false;
        foreach($order_obj->technicians as $data){
            if ($user == $data['technician_id']) {
                $flag = true;break;
            }
        }
        if (!$order_obj || !$flag) {
            O2oApp::response(false, '用户或订单ID错误', []);
        }

        $order_obj->status = 5;
        $success = $order_obj->save();
        O2oApp::response($success, '保存失败，请重试', []);
    }


    /**
     * 用户签字保洁师确认完成
     */
    public function actionTechComplete() {
        $order = Yii::app()->request->getParam('order', '');
        $user  = intval(Yii::app()->request->getParam('user', 0));
        $url = Yii::app()->request->getParam('o2oImage', '');
        if (!CommonFn::isMongoId($order) || $user == 0) {
            O2oApp::response(false, '订单或用户不存在', []);
        }

        $order_obj = ROrder::get(new MongoId($order));
        $flag = false;
        foreach($order_obj->technicians as $data){
            if ($user == $data['technician_id']) {
                $flag = true;break;
            }
        }
        if (!$order_obj || !$flag) {
            O2oApp::response(false, '用户或订单ID错误', []);
        }
        $url = get_object_vars(json_decode($url));
        $order_obj->signUrl = 'http://olas3bg3b.bkt.clouddn.com/'.$url['hash'];
        $order_obj->status = 6;
        $success = $order_obj->save();
        O2oApp::response($success, '保存失败，请重试', []);
    }


}