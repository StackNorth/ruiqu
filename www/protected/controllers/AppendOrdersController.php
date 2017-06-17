<?php
class AppendOrdersController extends AdminController{

    public function actionIndex()
    {
        $status_option = AppendOrder::$status_option;
        $status = CommonFn::getComboboxData($status_option, 1, true, 100);
        $this->render('index', array(
            'status' => $status
        ));
    }

    public function actionList(){
        $params = CommonFn::getPageParams();
 
        $status = intval(Yii::app()->request->getParam('status', 100));
        $id = Yii::app()->request->getParam('id', '');
        $criteria = new EMongoCriteria($params);
        if ($id != ''){
            $order_id = new MongoId($id);
            $criteria->order('or', $order_id);
            $criteria->_id('or', $order_id);
        }
        if ($status != 100) {
            $criteria->status('==', $status);
        }
        $cursor = AppendOrder::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = AppendOrder::model()->parse($rows);

        echo CommonFn::composeDatagridData($parsedRows, $total);
   
    }

}
