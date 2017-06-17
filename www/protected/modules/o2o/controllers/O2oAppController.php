<?php 
/**
 * 微信企业号o2o应用控制器
 * 用于存放公共接口
 * @author     2015-12-10
 */
class O2oAppController extends CController {

    public $layout = 'qyindex';

    public function actionIndex() {

    }

    /**
     * 获取普通订单详情接口
     */
    public function actionGetOrderInfo() {
        $id = Yii::app()->request->getParam('id', '');
        $user = intval(Yii::app()->request->getParam('user', 0));

        if ($id == '' || !CommonFn::isMongoId($id)) {
            O2oApp::response(false, '订单未录入', array());
        }

        $_id = new MongoId($id);
        $order = ROrder::get($_id);
        // if (!$order || $order->technician != $user) {
        if (!$order) {
            O2oApp::response(false, '未查询到订单', array());
        }

        $parsedOrder = $order->parseRow($order);
        O2oApp::response(true, '', $parsedOrder);
    }

    /**
     * 获取追加订单详情接口
     */
    public function actionGetAppendInfo() {
        $id = Yii::app()->request->getParam('id', '');

        if ($id == '' || !CommonFn::isMongoId($id)) {
            O2oApp::response(false, '订单未录入', array());
        }

        $_id = new MongoId($id);
        $append = AppendOrder::get($_id);
        if (!$append) {
            O2oApp::response(false, '未查询到订单', array());
        }

        $parsedAppend = $append->parseRow($append);
        O2oApp::response(true, '', $parsedAppend);
    }

    /**
     * 获取订单及评价详情接口
     */
    public function actionGetCommentInfo() {
        $id = Yii::app()->request->getParam('id', '');
        $user = intval(Yii::app()->request->getParam('user', 0));

        if ($id == '' || !CommonFn::isMongoId($id)) {
            O2oApp::response(false, '订单未录入', array());
        }

        $_id = new MongoId($id);
        $order = ROrder::get($_id);
        // if (!$order || $order->technician != $user) {
        if (!$order) {
            O2oApp::response(false, '未查询到订单', array());
        }

        // Comment信息
        $comment = Comment::getByOrder($_id);

        $parsedOrder = $order->parseRow($order);
        $parsedOrder['comment'] = $comment ? $comment->content : '';
        O2oApp::response(true, '', $parsedOrder);
    }

}