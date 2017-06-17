<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/10
 * Time: 9:22
 */
class YuyueController extends AdminController
{
    /**
     * 首页
     */
    public function actionIndex() {
        $status_option = CommonFn::getComboboxData(Yuyue::$status_option, 100, true, 100);

        $this->render('index', [
            'status_option' => $status_option
        ]);
    }

    /**
     * 列表
     */
    public function actionList() {
        $pageParams = CommonFn::getPageParams();

        $id     = Yii::app()->request->getParam('id','');
        $search = Yii::app()->request->getParam('search', '');
        $status = intval(Yii::app()->request->getParam('status', 100));

        $criteria = new EMongoCriteria($pageParams);
        // id筛选
        if ($id) {
            $criteria->_id('==', $id);
        }
        // 状态筛选
        if ($status != 100) {
            $criteria->status('==', $status);
        }
      
        if ($search) {
            // 搜索ID
            if (!preg_match('/\D/', $search)) {
                $criteria->_id('==', intval($search));
                // 搜索姓名或微信ID
            } else {
                $criteria->name('or', new MongoRegex('/'.$search.'/'));
            }
        }

        $cursor = Yuyue::model()->findAll($criteria);

        $rows = CommonFn::getRowsFromCursor($cursor);

        $parsedRows = Yuyue::model()->parse($rows);
        $total = $cursor->count();

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    /**
     * 编辑保洁师基本信息
     */
    public function actionEdit() {
        $_id               = Yii::app()->request->getParam('_id', 0);
        
        $status            = intval(Yii::app()->request->getParam('status', 1));   

        $yuyue = Yuyue::get($_id);
        if (!$yuyue ) {
            CommonFn::requestAjax(false, '信息不存在');
        }
        $yuyue->status            = $status;
        
        $success = $yuyue->save();
        CommonFn::requestAjax($success, '', []);
    }

}