<?php
class UserConfigController extends AdminController{

    public function actionIndex()
    {
        $this->render('index');
    }


    public function actionList(){
        $score_action = Yii::app()->params['score_action'];
        $show_array = array();
        foreach ($score_action as $key => $name) {
            $row['key'] = $key;
            $row['name'] = $name;
            $row['value'] = Service::factory('VariableService')->getVariable($key)?Service::factory('VariableService')->getVariable($key):0;
            $show_array[] = $row;
        }
        echo CommonFn::composeDatagridData($show_array,count($show_array));
    }


}
