<?php
/**
 * Created by PhpStorm.
 * User: songyongming
 * Date: 15/1/20
 * Time: 22:32
 */

class CommonWeixin
{

    public static function get_sign($url = ''){
        $path = Yii::getPathOfAlias('application');
        require_once($path."/vendors/weixin/WeiXinSdk.php");
		$wxConfig = Yii::app()->params['wxConfig'];
        $jssdk = new WeiXinSdk($wxConfig['appId'], $wxConfig['appSecret']);
        $signPackage = $jssdk->GetSignPackage($url);
        return $signPackage;
    }
	
	public static function getAccessInfo($code) {
		$wxConfig = Yii::app()->params['wxConfig'];
		$accessTokenURI = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $wxConfig['appId'] . '&secret=' . $wxConfig['appSecret'] . '&code=' . $code . '&grant_type=authorization_code';
		$res = json_decode(self::httpGet($accessTokenURI), true);
		
		return $res;
	}

	public static function getFollowList() {
		$wxConfig = Yii::app()->params['wxConfig'];
		$accessTokenURI = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$wxConfig['appId'].'&secret='.$wxConfig['appSecret'];
		$res = json_decode(self::httpGet($accessTokenURI), true);
		$token = $res['access_token'];
		$follow_list = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$token;
		$res = json_decode(self::httpGet($follow_list), true);
		return $res['data']['openid'];
	}
    public static function get_sign1($url = ''){
        $path = Yii::getPathOfAlias('application');
        require_once($path."/vendors/weixin/WeiXinSdk.php");
        $wxConfig = Yii::app()->params['xyhWxConfig'];
        $jssdk = new WeiXinSdk($wxConfig['appId'], $wxConfig['appSecret']);
        $signPackage = $jssdk->GetSignPackage($url);
        return $signPackage;
    }

    public static function getAccessInfo1($code) {
        $wxConfig = Yii::app()->params['xyhWxConfig'];
        $accessTokenURI = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $wxConfig['appId'] . '&secret=' . $wxConfig['appSecret'] . '&code=' . $code . '&grant_type=authorization_code';
        $res = json_decode(self::httpGet($accessTokenURI), true);
        return $res;
    }

    public static function getFollowList1() {
        $wxConfig = Yii::app()->params['xyhWxConfig'];
        $accessTokenURI = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$wxConfig['appId'].'&secret='.$wxConfig['appSecret'];
        $res = json_decode(self::httpGet($accessTokenURI), true);
        $token = $res['access_token'];
        $follow_list = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$token;
        $res = json_decode(self::httpGet($follow_list), true);
        return $res['data']['openid'];
    }






    public static function getUserInfo($accessToken, $openId) {
        $userInfoURI = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openId;
        $res = json_decode(self::httpGet($userInfoURI), true);

        return $res;
    }

	private static function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}