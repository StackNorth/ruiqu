<?php

/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/11/9
 * Time: 17:55
 */
class AdvisoryController extends O2oBaseController
{

	public function actionIndex()
	{
		$status_option = CommonFn::getComboboxData(Advisory::$status_option, 1, true, 100);
		$this->render('index', [
			'status_option' => $status_option
		]);
	}

	public function actionList()
	{
		$pageParams = CommonFn::getPageParams();

		$id = intval(Yii::app()->request->getParam('id'));
		$search = Yii::app()->request->getParam('search', '');
		$status = intval(Yii::app()->request->getParam('status', 100));

		$criteria = new EMongoCriteria($pageParams);
		// id筛选
		if ($id) {
			$criteria->_id('==', new MongoId($id));
		}
		// 状态筛选
		if ($status != 100) {
			$criteria->status('==', $status);
		}

		$cursor = Advisory::model()->findAll($criteria);
		$rows = CommonFn::getRowsFromCursor($cursor);
		$parsedRows = Advisory::model()->parse($rows);
		$total = $cursor->count();

		echo CommonFn::composeDatagridData($parsedRows, $total);

	}

	public function actionEdit()
	{
		$status = intval(Yii::app()->request->getParam('status', 100));
		$id = Yii::app()->request->getParam('id');
		if (!$id) {
			CommonFn::requestAjax(false, '');
		}
		if ($status == 100) {
			CommonFn::requestAjax(false, '请选择状态');
		}
		$advisory = Advisory::model()->get(new MongoId($id));
		$advisory->status = $status;
		$success = $advisory->save();
		if ($success) {
			CommonFn::requestAjax(true, '修改成功');
		}

	}


	//名宿保洁
	public function actionSinglesDay()
	{

		$name = Yii::app()->request->getParam('user_name', '');
		$area = str_replace('string:', "", Yii::app()->request->getParam('area', ''));
		$homeType = str_replace('string:', "", Yii::app()->request->getParam('homeType', ''));
		$num = str_replace('string:', "", Yii::app()->request->getParam('num', ''));
		$mobile = Yii::app()->request->getParam('mobile');
		$tech_content = Yii::app()->request->getParam('tech_content', '');
		if ($name) {
			$advisory = new Advisory();
			$advisory->user_name = $name;
			$advisory->area = $area;
			$advisory->homeType = $homeType;
			$advisory->mobile = $mobile;
			$advisory->num = $num;
			$advisory->area = $area;
			$advisory->type = '名宿保洁';
			$advisory->time = time();
			$advisory->status = 1;
			$advisory->tech_content = $tech_content;
			if ($advisory->save()) {
				echo '您的咨询已发送成功,请等待我们的管家与您联系,温馨提示，一般是一个工作日内，日间致电给您，请留意。';
				exit;
			}
		}

		$this->render('SinglesDay');
	}

	//企业服务
	public function actionEnterprise()
	{
		$name = Yii::app()->request->getParam('user_name', '');
		$area = str_replace('string:', "", Yii::app()->request->getParam('area', ''));
		$homeType = str_replace('string:', "", Yii::app()->request->getParam('homeType', ''));
		$num = str_replace('string:', "", Yii::app()->request->getParam('num', ''));
		$mobile = Yii::app()->request->getParam('mobile');
		$tech_content = str_replace('string:', "", Yii::app()->request->getParam('tech_content', ''));
		if ($name) {
			$advisory = new Advisory();
			$advisory->user_name = $name;
			$advisory->area = $area;
			$advisory->homeType = $homeType;
			$advisory->mobile = $mobile;
			$advisory->num = $num;
			$advisory->area = $area;
			$advisory->time = time();
			$advisory->type = '企业服务';
			$advisory->status = 1;
			$advisory->tech_content = $tech_content;
			if ($advisory->save()) {
				echo '您的咨询已发送成功,请等待我们的管家与您联系,温馨提示，一般是一个工作日内，日间致电给您，请留意。';
				exit;
			}
		}
		$this->render('enterprise');
	}

	//享月会联系
	public function actionXyhIm()
	{
		$this->render('xyhIm');
	}
//享月会联系
	public function actionMonth()
	{
		$this->render('month');
	}

	//联系壹橙管家
	public function actionIm()
	{
		$this->render('im');
	}

}