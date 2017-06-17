<?php
class WebController extends O2oBaseController {

	public function actionIndex() {

		$signPackage = CommonWeixin::get_sign();
		$home_page = Yii::app()->getRequest()->getParam("home_page",'');
		$code = Yii::app()->getRequest()->getParam("code");
		$state = Yii::app()->getRequest()->getParam("state");

		$userId = '';
		$appToken = '';
		if($code && $state){
			$accessInfo = CommonWeixin::getAccessInfo($code);
			$userId = '';
			if (!isset($accessInfo['errcode']) && $state == 'yiguanjia') {
				$appToken = md5(substr($accessInfo['openid'],2));
				//微信校验通过，登录（注册），分发token
				$userInfo = CommonWeixin::getUserInfo($accessInfo['access_token'], $accessInfo['openid']);
				if (!isset($accessInfo['errcode'])) {
					//检查是否有注册，没有就注册
					$criteria = new EMongoCriteria();
					$user = null;
					if(isset($accessInfo['unionid'])&&$accessInfo['unionid']){
						$criteria->unionid('==', $accessInfo['unionid']);
						$user = RUser::model()->find($criteria);
					}
					if ($user) {
						$userId = $user->_id;
						if(!isset($user->wx_pub_openid) || empty($user->wx_pub_openid)){
							$user->wx_pub_openid = $accessInfo['openid'];
							$user->wx_have_follow = 1;
							$user->update(array('wx_pub_openid','wx_have_follow'),true);
						}
					}else{
						$userAr  = new RUser();
						$userAr->user_name = $userInfo['nickname'];
						$userAr->avatar = $userInfo['headimgurl'];
						$userAr->wx_pub_openid = $userInfo['openid'];
						if(isset($accessInfo['unionid'])&&$accessInfo['unionid']){
							$userAr->unionid = $userInfo['unionid'];
						}
						$userAr->sex = $userInfo['sex'];
						$userAr->register_time = time();
						$userAr->channel = 'wxpub';
						$userAr->wx_have_follow = 1;
						$u_criteria = new EMongoCriteria();
						$u_criteria->user_name('==',$userInfo['nickname']);
						$olduser = RUser::model()->find($u_criteria);
						if($olduser){
							$user_new_neme = $userAr->user_name.'_'.substr(time(),-7);
							$userAr->user_name = $user_new_neme;
						}

						$result = $userAr->save();
						if($result){
							//异步同步微信头像到七牛
							if (!empty($userAr->unionid) && (strpos($userAr->avatar, 'qiniu') === false)) {
								$list = new ARedisList('after_user_reg');
								$user_id = (string)$userAr->_id;
								$list->push($user_id);
							}
							$userId = (string)$userAr->_id;

						}else{
							var_dump($userAr);exit;
						}
					}
				}else{
					echo $accessInfo['errcode'];
					die();
				}
			}

		}

		if($home_page){
			$this->renderpartial($home_page.'Index', array(
				'version' => '2015082505',
				'debug' => 'false',
				'signPackage' => $signPackage,
				'userId' => $userId,
				'appToken' => $appToken,
			));
		}else{
			$this->renderpartial('index', array(
				'version' => '2015111601',
				'debug' => 'false',
				'signPackage' => $signPackage,
				'userId' => $userId,
				'appToken' => $appToken,
			));
		}
	}

	//检查微信登录页
	public function actionWxIndex() {
		$wxConfig = Yii::app()->params['wxConfig'];
		$home_page = Yii::app()->getRequest()->getParam("home_page",'');
		$redirectURI = 'http://' . $_SERVER['HTTP_HOST'] . Yii::app()->request->baseUrl . '/o2o/web/index';
		if($home_page){
			$appURI = Yii::app()->request->baseUrl . '/o2o/web/index'.'&home_page='.$home_page;
			$redirectURI = $redirectURI.'/'.$home_page;
		}else{
			$appURI = Yii::app()->request->baseUrl . '/o2o/web/index';
		}
		$scope = 'snsapi_userinfo';
		$state = 'yiguanjia';
		$codeURI = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wxConfig['appId'] . '&redirect_uri=' . urlencode($redirectURI) . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
		$this->renderpartial('wxIndex', array(
			'codeURI' => $codeURI,
			'appURI' => $appURI
		));
	}

}
