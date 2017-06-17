<?php
class SysConfigController extends AdminController{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionList(){
        $cursor = Variable::model()->findAll();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Variable::model()->parse($rows);
        $admin_view_config = Yii::app()->params['admin_view_config'];
        foreach ($parsedRows as $key => $value) {
            if(!array_key_exists($value['key'],$admin_view_config)){
                unset($parsedRows[$key]);
                continue;
            }
            $parsedRows[$key]['name'] = $admin_view_config[$value['key']];
        }
        $parsedRows = array_values($parsedRows);
        echo CommonFn::composeDatagridData($parsedRows, count($parsedRows));
    }

    public function actionSet(){
        $key = Yii::app()->request->getParam('key', '');
        $value = Yii::app()->request->getParam('value', '');
        if($key == ''||$value == ''){
            CommonFn::requestAjax(false, "缺少必须参数");
        }
        $value = str_replace('，', ',',$value);
        $success = Service::factory('VariableService')->setVariable($key,$value);
        CommonFn::requestAjax($success);
    }

    public function actionDelete(){
        $key = Yii::app()->request->getParam('key');
        if (!$key) {
            CommonFn::requestAjax(false, "缺少必须参数");
        }
        $success = Service::factory('VariableService')->delVariable($key);
        CommonFn::requestAjax($success);
    }

    public function actionSetCounter(){
        $key = Yii::app()->request->getParam('key');
        $value = intval(Yii::app()->request->getParam('value'));
        $counter = new ARedisCounter($key);
        $counter->increment($value);
        echo "counter($key):".$counter->getValue();
    }

}
