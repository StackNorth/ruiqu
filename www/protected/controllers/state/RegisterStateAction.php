<?php

class RegisterStateAction extends CAction
{
    public function run(){
        $controller = $this->getController();
        $begin_time = intval(Yii::app()->request->getParam('begin_time'));
        $end_time = intval(Yii::app()->request->getParam('end_time'));

        $criteria = new EMongoCriteria();

        //$criteria->create_time('==', new MongoId());
        //$records = SmsRecord::model()->find($criteria);

        $records = SmsRecord::model()->find();

        $total = count($records);
        $rows = CommonFn::getRowsFromCursor($records);

        echo CommonFn::composeDatagridData($rows, $total);
    }
}