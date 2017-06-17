<?php 
/**
 * 系统静态资源配置
 */
class StaticSourceController extends AdminController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionList() {
        $cursor = StaticSource::model()->findAll();
        $count = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = StaticSource::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $count);
    }

    public function actionEdit() {
        $id      = Yii::app()->request->getParam('id', '');
        $key     = Yii::app()->request->getParam('key', '');
        $title   = Yii::app()->request->getParam('title', '');
        $content = Yii::app()->request->getParam('editorValue', '');
        $remark  = Yii::app()->request->getParam('remark', '');

        if (!$key) {
            CommonFn::requestAjax(true, '请检查Key');
        }

        $temp = StaticSource::getByKey($key);
        if (!$id) {
            $static = new StaticSource();
            if ($temp) {
                CommonFn::requestAjax(false, '键值已存在');
            }
        } else {
            $static = StaticSource::get(new MongoId($id));
            if ($temp && $temp->_id != $static->_id) {
                CommonFn::requestAjax(false, '键值已存在');
            }
        }

        
        $static->key = $key;
        $static->title = $title;
        $static->content = $content;
        $static->remark = $remark;
        $static->save();

        CommonFn::requestAjax(true, '', []);
    }

}