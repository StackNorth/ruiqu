<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/10
 * Time: 9:22
 */
class IndexController extends TechBaseController
{
    /**
     * 首页
     */
    public function actionIndex() {
        $status_option = CommonFn::getComboboxData(Tech::$status_option, 100, true, 100);

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
      /*  // 提成方案筛选
        if ($scheme != 100) {
            $criteria->scheme('==', Commision::$scheme_option[$scheme]['alias']);
        }*/
        // 搜索
        if ($search) {
            // 搜索ID
            if (!preg_match('/\D/', $search)) {
                $criteria->_id('==', intval($search));
                // 搜索姓名或微信ID
            } else {
                $criteria->name('or', new MongoRegex('/'.$search.'/'));
            }
        }

        $cursor = Tech::model()->findAll($criteria);

        $rows = CommonFn::getRowsFromCursor($cursor);

        $parsedRows = Tech::model()->parse($rows);
        $total = $cursor->count();

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    /**
     * 编辑保洁师基本信息
     */
    public function actionEdit() {
        $_id               = Yii::app()->request->getParam('_id', 0);
       /* $name              = Yii::app()->request->getParam('name', '');
        $avatar            = Yii::app()->request->getParam('avatar', '');*/
        $status            = intval(Yii::app()->request->getParam('status', 1));

        //$service_type      = Yii::app()->request->getParam('service_type', []);
        $desc              = Yii::app()->request->getParam('desc', '');
        //$img_upper_body    = Yii::app()->request->getParam('img_upper_body','');
        //$img_handheld_card = Yii::app()->request->getParam('img_handheld_card','');
        //$img_card_front    = Yii::app()->request->getParam('img_card_front','');
        //$img_card_back     = Yii::app()->request->getParam('img_card_back','');

        // intval service_typea
        /*foreach ($service_type as &$value) {
            $value = intval($value);
        }*/

        $tech = Tech::get($_id);
        /*$user = User::get($_id);*/

        if (!$tech ) {
            CommonFn::requestAjax(false, '保洁师信息不存在');
        }


        // Tech更新
        //$tech->name              = $name;
        $tech->status            = $status;
        //$tech->avatar            = $avatar;
        //$tech->service_type      = $service_type;
        $tech->desc              = $desc;
        //$tech->img_upper_body    = $img_upper_body;
        //$tech->img_handheld_card = $img_handheld_card;
        //$tech->img_card_front    = $img_card_front;
        //$tech->img_card_back     = $img_card_back;


        // user更新
        /*$user->status = $status;
        $user->name   = $name;*/

        $success_tech = $tech->save();
//        $success_user = $user->save();
        //CommonFn::requestAjax($success_tech && $success_user, '', []);
        CommonFn::requestAjax($success_tech, '', []);
    }

}