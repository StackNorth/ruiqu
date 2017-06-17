<?php
/**
 * summary: 管理员目录管理
 * author: justin
 * date: 2014.03.04
 */
class AdminMenuController extends AdminController
{
	public function actionIndex()
	{
		$status_option = CommonFn::getScenarioOption(AdminMenuAR::$status_option, 'filter');
		$status = CommonFn::getComboboxData($status_option, 1, true, 10);			
		$this->render('index', array('status' => $status));
	}
	
	
	public function actions()
	{
		return array(
			'getRows' => 'application.controllers.adminMenu.GetDataAction',
            'insertRow' => array(
            	'class' => 'application.controllers.adminMenu.PostDataAction',
            	'scenario' => 'insert'
            ),
            'updateRow' => array(
            	'class' => 'application.controllers.adminMenu.PostDataAction',
            	'scenario' => 'update'
            ),
            'deleteRow' => array(
            	'class' => 'application.controllers.adminMenu.PostDataAction',
            	'scenario' => 'delete'
            )
		);
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
}