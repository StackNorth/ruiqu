<?php 
/**
 * 我的评价控制器
 * @author     2015-12-11
 */
class TechHomeController extends CController {

    public $layout = 'techHomeLayout';

    /**
     * 我的评价首页
     */
    public function actionIndex() {
        // 环境判断，正式环境需通过微信验证product
         if (ENVIRONMENT == 'test') {
        //if (false) {
            if (isset($_COOKIE['weixin_userid']) && isset($_COOKIE['weixin_userid_signature'])) {
                $signature = md5($_COOKIE['weixin_userid'].'wozhua=9527');
                if ($signature == $_COOKIE['weixin_userid_signature']) {
                    $userid = $_COOKIE['weixin_userid'];
                } else {
                    $this->render('error', ['msg' => '未查询到用户']);die;
                }
            } else {
                $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxedb2ce71dfee8aa5&redirect_uri= api.yiguanjia.me%2Findex.php%3Fr%3Do2o%2FtechHome%2FcheckUserid&response_type=code&scope=snsapi_base&state=5e2b4706179f774e94903e1213d2222e#wechat_redirect');
            }
        } else {
            $userid = Yii::app()->request->getParam('weixin_userid', '');
        }

        $tech   = TechInfo::getByUserid($userid);
        $name   = $tech->name;
        $_id    = $tech->_id;
        $desc   = $tech->desc ? $tech->desc : '';
        $avatar = $tech->avatar ? $tech->avatar : Yii::app()->params['defaultUserAvatar'];
        $qiniu_token = ENVIRONMENT == 'product' ?
            // 正式版token
            'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr:'.
            'PhjO5GeGx1VECe1W7AlqUHZrxhg=:'.
            'eyJzY29wZSI6InBpY3MiLCJkZWFkbGluZSI6MTQ3NDQ1MTg0OTAwMDAwMDAwMH0=':
            // 测试版token
            'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr:'.
            'kPiwYRwhAt8ULIMxphH8Hbgs6Mk=:'.
            'eyJzY29wZSI6InRlc3QiLCJkZWFkbGluZSI6MTQ3NDQ0NTg1OTAwfQ==';
        $qiniu_url = ENVIRONMENT == 'product' ? 
            // 正式版url前缀
            'http://odulvej8l.bkt.clouddn.com/':
            // 测试版url前缀
            'http://odujh0tsx.bkt.clouddn.com/';
        $data = array(
            '_id'           => $_id,
            'weixin_userid' => $userid,
            'name'          => $name,
            'desc'          => $desc,
            'avatar'        => $avatar,
            'qiniu_token'   => $qiniu_token,
            'qiniu_url'     => $qiniu_url
        );
        $this->render('index', $data);
    }

    /**
     * 获取userid保存至cookie
     */
    public function actionCheckUserid() {
        $check = O2oApp::checkURI(24);
        if (!$check['success']) {
            $this->render('error', $check);die;
        } else {
            $userid = $check['userid'];
        }
        setcookie('weixin_userid', $userid);
        setcookie('weixin_userid_signature', md5($userid.'wozhua=9527'));
        $this->redirect(['index']);
    }

    public function actionUpdateInfo() {
        $tech_id = intval(Yii::app()->request->getParam('tech_id', ''));
        $desc    = Yii::app()->request->getParam('desc', '');
        $avatar  = Yii::app()->request->getParam('avatar', '');

        $tech = TechInfo::get($tech_id);

        // tech_info存在则更新tech_info
        if($tech){
            $tech->desc   = $desc;
            $tech->avatar = $avatar;

            if ($tech->update(array('desc','avatar'), true)) {
                $data['id']     = $tech->_id;
                $data['name']   = $tech->name;
                $data['desc']   = $tech->desc;
                $data['avatar'] = $tech->avatar;
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
            } else {
                CommonFn::requestAjax(false, '修改失败');
            }
        // 新建一个tech_info
        } else {
            $user = User::get($tech_id);
            if ($user) {
                $tech = new TechInfo();
                $tech->_id           = $user->_id;
                $tech->name          = $user->name;
                $tech->status        = $user->status;
                $tech->desc          = $desc;
                $tech->avatar        = $avatar;
                $tech->scheme        = 'no_scheme';
                $tech->weixin_userid = '';
                $tech->mobile        = '';
                $tech->service_type  = [];

                // 保洁师接单数（状态为已完成的订单）
                $criteria = new EMongoCriteria();
                $criteria->technician('==', $tech->_id);
                $criteria->status('==', 6);
                $rOrders = ROrder::model()->findAll($criteria);
                $tech->order_count = $rOrders->count();

                // 保洁师好评数（分数为5的评价）
                $criteria = new EMongoCriteria();
                $criteria->score('==', 5);
                $criteria->status('==', 1);
                $criteria->technician('==', $tech->_id);
                $comments = Comment::model()->findAll($criteria);
                $tech->favourable_count = $comments->count();

                if ($tech->save()) {
                    $data['id']     = $tech->_id;
                    $data['name']   = $tech->name;
                    $data['desc']   = $tech->desc;
                    $data['avatar'] = $tech->avatar;
                    CommonFn::requestAjax(true, CommonFn::getMessage('message', 'operation_success'), $data);
                }
            } else {
                CommonFn::requestAjax(false, '此保洁师不存在');
            }
        }
    }

}