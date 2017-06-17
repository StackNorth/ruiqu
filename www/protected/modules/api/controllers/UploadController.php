<?php
/**
 * UploadController 文件上传相关api接口
 */
class UploadController extends ApiBaseController{
    public function beforeAction($action){
        $weixin_use = array('wx2Qiniu','gettoken','test');
        if(Yii::app()->getRequest()->getParam("request_from") == 'weixin' && in_array($action->id,$weixin_use)){
            return true;
        }
        return $this->verify();
    }
    
   //动态获得七牛上传的token
    public function actionGettoken(){
        $bucket = Yii::app()->request->getParam('bucket',"");
        if($bucket=="wozhua_video"){
            $bucket = 'wozhua-video';
        }
        if(empty($bucket)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $path = Yii::getPathOfAlias('application');
        require_once($path."/vendors/qiniu/rs.php");
        $qiniu_config = Yii::app()->params['qiniuConfig'];
        $accessKey = $qiniu_config['ak'];
        $secretKey = $qiniu_config['sk'];
        Qiniu_SetKeys($accessKey, $secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        $upToken = $putPolicy->Token(null);
        CommonFn::requestAjax(true,'',array("token" =>$upToken));
    }


    public function actionWx2Qiniu(){
        $path = Yii::getPathOfAlias('application');
        require_once($path."/vendors/weixin/WeiXinSdk.php");
        $jssdk = new WeiXinSdk("wx113e0169cfe3d09f", "2d4a1e58262cdc79730025c4be246e74");
        $access_token = $jssdk->getAccessToken();
        $media_id = Yii::app()->request->getParam('media_id',"");
        if(empty($media_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $wx_img = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        $file = '/data/'.md5(time()+rand());
        $res = CommonFn::getImageByUrl($wx_img,$file);
        if($res!==true){
            CommonFn::requestAjax(false,'failt');
        }
        $imageinfo = getimagesize($file);
        $extname = substr($imageinfo['mime'],strpos($imageinfo['mime'],'/')+1);
        if(empty($extname)){
            unlink($file);
            CommonFn::requestAjax(false,'failt');
        }
        $new_pic = time().'.'.$extname;
        $status = CommonFn::upFiletoQiniu($file,$new_pic,Yii::app()->params['qiniuConfig']['pics']);
        if(!$status){
            unlink($file);
            CommonFn::requestAjax(false,'failt');
        }else{
            unlink($file);
            $qiniu_url = 'http://'.Yii::app()->params['qiniuConfig']['pics'].'.qiniudn.com/'.$new_pic;
            CommonFn::requestAjax(true,'success',array("qiniu_img" =>$qiniu_url));
        }
    }

}