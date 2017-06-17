<?php 
/**
 * 系统URL配置
 * @author     2015-10-15
 */
class UrlConfigController extends AdminController {

    public function actionIndex () {
        $this->render('index');
    }

    public function actionList () {
        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoRegex('/^pu/');
        $cursor = Variable::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Variable::model()->parse($rows);

        $parsedRows = array_values($parsedRows);
        echo CommonFn::composeDatagridData($parsedRows, count($parsedRows));
    }

    public function actionEdit () {
        $key = Yii::app()->request->getParam('key');
        $value = Yii::app()->request->getParam('value');

        if (!$key || !$value) {
            CommonFn::requestAjax(false, '缺少必须参数');
        }

        $regex = '/^pu/';
        if (empty(preg_grep($regex, array($key)))) {
            CommonFn::requestAjax(false, '配置名不符合规范');
        }

        $success = Service::factory('VariableService')->setVariable($key, $value);
        CommonFn::requestAjax($success, '', array());
    }

}
?>