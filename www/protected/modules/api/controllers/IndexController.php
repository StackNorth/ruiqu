<?php
/**
 * 应用首页接口，后面可能根据不同版本独立
 */
class IndexController extends ApiBaseController {

    public function beforeAction($action){
        // $weixin_use = array('staticSource');
        // if(Yii::app()->getRequest()->getParam("request_from") == 'weixin' && in_array($action->id,$weixin_use)){
        //     return true;
        // }

        //todo user first login
        return $this->verify();
    }



}
