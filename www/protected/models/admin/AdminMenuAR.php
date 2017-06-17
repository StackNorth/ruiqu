<?php
/**
 * summary: 管理员菜单
 * author: justin
 * date: 2014.03.04
 */
class AdminMenuAR extends MongoAr
{
    public $_id;
    public $name = '';				//菜单名
    public $url = '';				//对应的链接
    public $sort = 1;				//排序
    public $level = 1;				//等级
    public $parent;					//父Id
    public $auth_item = '';			//对应的权限名称
    public $status = 1;     		//状态
    public static $status_option = array(
									-1 => array('name' => '已删除'),
									1 => array('name' => '正常', 'filter' => true)
								);
	protected $auto_fields = array('auth_item');
    
    public function __construct($scenario='insert'){
		parent::__construct($scenario);
		$this->onBeforeSave = function($event){
        	//根据url规则得到对应的权限字符串
			$model = $event->sender;
			$url = $model->url;
			$route = 'no_route';
			$auth_item = '';
			if (preg_match('/(&|\?)r=([^&]+)/', $url, $matches)){
				$route = $matches[2];
			}
			if (($ca = Yii::app()->createController($route)) !== null){
				list($controller, $action) = $ca;
				$za = new ZAuth();
				$auth_item = $za->getAuthItem($controller, $action);
			}
			$model->auth_item = $auth_item;
		};
	}
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);        
    }

    public function getCollectionName()
    {
        return 'admin_menu';
    }
}