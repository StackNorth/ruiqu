<?php
class IndexController extends O2oBaseController {

    public function actionIndex() {
        $signPackage = CommonWeixin::get_sign();
        $current_uri = Yii::app()->request->baseUrl . '/o2o/index/index';
        $appToken = 'wz';
        $this->renderpartial('index', array(
                'signPackage' => $signPackage,
                'appToken' => $appToken,
                'current_uri' => $current_uri,
            ));
    }
}
