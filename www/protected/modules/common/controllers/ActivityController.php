<?php

/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/10/9
 * Time: 14:44
 */
class ActivityController extends O2oBaseController
{
  /**
   * 双十一给所有用户发券
   * 57fe0aaa9f5160b1048b456c
   */
  public function actionDoubleEleven()
  {
    $coupon_id = "5816a413a84ea0023f8b46ba";
    $coupon_id = new MongoId($coupon_id);
    $start_time = date_create("2016-11-01")->format('U');                //发放优惠券可用开始时间 2016.11.11
    $end_time = $start_time + 86400 * 30;   //发放优惠券过期时间 一个月
    $cursor = RUser::model()->findAll();

    $rows = CommonFn::getRowsFromCursor($cursor);
    $flag = 0;
    foreach ($rows as $value) {
      if (CommonFn::isMongoId($value['_id'])) {
        $userId = $value['_id'];
        Service::factory('CouponService')->giveCoupon($userId, $coupon_id, $start_time, $end_time);//发放代金券
        $flag += 1;
      }
    }
    echo "总共发送" . $flag . "个用户";
  }

  //扫码跳转11元订单页面，回掉微信接口，注册用户信息，然后生成订单
  public function actionScanCoupon()
  {
    //$this->redirect('http://common.yiguanjia.me/index.php?r=o2o/web/index');
    var_dump(intval(date_create("2016-11-11")->format('U')));
    var_dump(time());
  }


  //扫码获取代金券
  public function actionGetCoupon()
  {
    $signPackage = CommonWeixin::get_sign();
    $coupon_id = Yii::app()->request->getParam('coupon_id', '');//获取代金券id
    $code = Yii::app()->getRequest()->getParam("code");
    $state = Yii::app()->getRequest()->getParam("state");
    $userId = Yii::app()->getRequest()->getParam("userId", '');

    $appToken = '';
    if ($code && $state) {
      $accessInfo = CommonWeixin::getAccessInfo($code);

      if (!isset($accessInfo['errcode']) && $state == 'yiguanjia') {

        $appToken = md5(substr($accessInfo['openid'], 2));
        //微信校验通过，登录（注册），分发token
        $userInfo = CommonWeixin::getUserInfo($accessInfo['access_token'], $accessInfo['openid']);
        if (!isset($accessInfo['errcode'])) {

          //检查是否有注册，没有就注册
          $criteria = new EMongoCriteria();
          $criteria->unionid('==', $accessInfo['unionid']);
          $user = RUser::model()->find($criteria);
          if ($user) {
            $userId = $user->_id;
            if (!isset($user->wx_pub_openid) || empty($user->wx_pub_openid)) {
              $user->wx_pub_openid = $accessInfo['openid'];
              $user->wx_have_follow = 1;
              $user->update(array('wx_pub_openid', 'wx_have_follow'), true);
            }
          } else {
            $userAr = new RUser();
            $userAr->user_name = $userInfo['nickname'];
            $userAr->avatar = $userInfo['headimgurl'];
            $userAr->wx_pub_openid = $userInfo['openid'];
            $userAr->unionid = $userInfo['unionid'];
            $userAr->sex = $userInfo['sex'];
            $userAr->register_time = time();
            $userAr->channel = 'wxpub';
            $userAr->wx_have_follow = 1;
            $u_criteria = new EMongoCriteria();
            $u_criteria->user_name('==', $userInfo['nickname']);
            $olduser = RUser::model()->find($u_criteria);
            if ($olduser) {
              $user_new_neme = $userAr->user_name . '_' . substr(time(), -7);
              $userAr->user_name = $user_new_neme;
            }

            $result = $userAr->save();
            if ($result) {
              //异步同步微信头像到七牛
              if (!empty($userAr->unionid) && (strpos($userAr->avatar, 'qiniu') === false)) {
                $list = new ARedisList('after_user_reg');
                $user_id = (string)$userAr->_id;
                $list->push($user_id);
              }
              $userId = (string)$userAr->_id;
            } else {
              var_dump($userAr);
              exit;
            }
          }


        } else {
          echo $accessInfo['errcode'];
          die();
        }


      }
    }
    $coupon_id = new MongoId($coupon_id);
    $start_time = time();                 //发放优惠券可用开始时间
    $end_time = $start_time + 86400 * 30;   //发放优惠券过期时间

    $flag = 0;
    if ($userId != '') {
      $userId = new MongoId($userId);
      $criteria = new EMongoCriteria();
      //$criteria->coupon('==',$coupon_id);
      $criteria->user('==', $userId);
      $user_coupon = UserCoupon::model()->find($criteria);


      if (!$user_coupon) {
        Service::factory('CouponService')->giveCoupon($userId, $coupon_id, $start_time, $end_time);//发放代金券
      }
      $flag = 1;
    }
    if ($flag) {
      $this->renderpartial('index');
    } else {
      $this->renderpartial('getCoupon', array(
        'version' => '2015111601',
        'signPackage' => $signPackage,
        'userId' => $userId,
        'appToken' => $appToken,
        'coupon_id' => $coupon_id,
      ));
    }

  }

  //检查微信登录页
  public function actionWxIndex()
  {
    $wxConfig = Yii::app()->params['wxConfig'];
    $coupon_id = Yii::app()->request->getParam('coupon_id', '');
    $redirectURI = 'http://' . $_SERVER['HTTP_HOST'] . Yii::app()->request->baseUrl . '/index.php?r=/common/activity/getCoupon&coupon_id=' . $coupon_id;
    $appURI = Yii::app()->request->baseUrl . '/index.php?r=common/activity/getCoupon&coupon_id=' . $coupon_id;

    $scope = 'snsapi_userinfo';
    $state = 'yiguanjia';
    $codeURI = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wxConfig['appId'] . '&redirect_uri=' . urlencode($redirectURI) . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
    $this->renderpartial('wxIndex', array(
      'codeURI' => $codeURI,
      'appURI' => $appURI
    ));
  }

}