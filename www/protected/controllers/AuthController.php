<?php

class AuthController extends AdminController
{
	/**
	 * 权限管理首页
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
	
	/**
	 * 获取列表
	 */
	public function actionGetItems(){
		$params = CommonFn::getPageParams();
		$sort = Yii::app()->request->getParam('sort', '');
		$order = Yii::app()->request->getParam('order', 'asc');
		$type = Yii::app()->request->getParam('type');
		$auth = Yii::app()->authManager;
		$all_items = $auth->getAuthItems($type);
		$item_ids = array();
		foreach ($all_items as $k => $v){
			$temps = array();
			if ($type > 0){
				$children = $auth->getItemChildren($k);		
				foreach ($children as $m => $n){
					$temps[] = $m;
				}
			}		
			$item_ids[$k] = array('_id' => $k, 'name' => $k, 'desc' => $v->description, 'children' => implode(',', $temps));
		}		
		$total = count($item_ids);
		$more = array();
		foreach ($item_ids as $v){
			$more[$v['name']] = array('value' => $v['name'], 'text' => $v['name'], 'desc' => $v['desc']);
		}
		if ($sort == 'name'){
			if ($order == 'asc'){
				ksort($more);
				ksort($item_ids);
			} else {
				krsort($more);
				krsort($item_ids);
			}
		}		
		$more = array_values($more);
		$item_ids = array_values($item_ids);
		$rows = array_slice($item_ids, $params['offset'], $params['limit']);
		echo CommonFn::composeDatagridData($rows, $total, $more);
	}
	
	/**
	 * 新增条目
	 */
	public function actionInsertItem(){
		$auth = Yii::app()->authManager;
		$name = Yii::app()->request->getParam('name');
		$desc = Yii::app()->request->getParam('desc');
		$children = Yii::app()->request->getParam('children');
		$type = Yii::app()->request->getParam('type');
		if ($type <= 0){
			CommonFn::requestAjax(false, '操作不用自定义');
		}
		$children = array_filter(explode(',', $children));
		if ($auth->getAuthItem($name)){
			CommonFn::requestAjax(false, '名称重复');
		}
		$auth->createAuthItem($name, $type, $description=$desc);
		foreach ($children as $v){
			if ($auth->getAuthItem($v)){
				$auth->addItemChild($name, $v);
			}		
		}
		$auth->save();
		CommonFn::requestAjax();
	}
	
	/**
	 * 更新条目
	 */
	public function actionUpdateItem(){
		$auth = Yii::app()->authManager;
		$_id = Yii::app()->request->getParam('_id');
		$name = Yii::app()->request->getParam('name');
		$desc = Yii::app()->request->getParam('desc');
		$type = Yii::app()->request->getParam('type');
		$children = Yii::app()->request->getParam('children');
		$children = array_filter(explode(',', $children));
		if (!$auth->getAuthItem($_id)){
			CommonFn::requestAjax(false, '原条目不存在');
		}
		$item = $auth->getAuthItem($_id);
		$item->setDescription($desc);
		if ($type > 0){
			$temp = array_keys($auth->getItemChildren($_id));
			foreach ($temp as $v){
				if (!in_array($v, $children)){
					$auth->removeItemChild($_id, $v);
				}
			}
			foreach ($children as $v){
				if ($auth->getAuthItem($v) && !$auth->hasItemChild($_id, $v)){
					$auth->addItemChild($_id, $v);
				}
			}
			if ($name != $_id){
				if ($_id == $auth->super_admin){
					CommonFn::requestAjax(false, '不能修改超级管理员');
				}
				if ($auth->getAuthItem($name)){
					CommonFn::requestAjax(false, '名称已存在');
				}
				$item->setName($name);
			}
		}	
		$auth->save();
		CommonFn::requestAjax();
	}
	
	/**
	 * 删除条目
	 */
	public function actionRemoveItem(){
		$auth = Yii::app()->authManager;
		$_id = Yii::app()->request->getParam('_id');		
		if (!$auth->getAuthItem($_id)){
			CommonFn::requestAjax(false, '原条目不存在');
		}
		if ($_id == $auth->super_admin){
			CommonFn::requestAjax(false, '不能删除超级管理员');
		}
		$auth->removeAuthItem($_id);
		$auth->save();
		CommonFn::requestAjax();
	}
	
	/**
	 * 更新全部的操作
	 */
	public function actionScanOperation(){
		$auth = Yii::app()->authManager;
		$all_controllers = $this->_getControllers();
		$all_actions = array();
		$action_names = array();
		foreach ($all_controllers as $v){
			$data = $this->_getControllerInfo($v, true);
			foreach ($data['action'] as $m => $n){
				$all_actions[] = array('name' => $n, 'desc' => $m);
				$action_names[] = $n;				
			}
		}
		$old_auth_items = $auth->getAuthItems(0);
		foreach ($old_auth_items as $k => $v){
			if (!in_array($k, $action_names)){
				$auth->removeAuthItem($k);
			}		
		}
		foreach ($all_actions as $v){
			if (!$auth->getAuthItem($v['name'])){
				$auth->createAuthItem($v['name'], 0, $v['desc']);
			}
		}
		$auth->save();
		CommonFn::requestAjax();
	}
	
	/**
	 * 获取全部的控制器
	 */
	private function _getControllers() {
    	$contPath = Yii::app()->getControllerPath();

    	$controllers = $this->_scanDir($contPath);

    	//Scan modules
    	$modules = Yii::app()->getModules();
    	$modControllers = array();
    	foreach ($modules as $mod_id => $mod) {
      		$moduleControllersPath = Yii::app()->getModule($mod_id)->controllerPath;
      		$modControllers = $this->_scanDir($moduleControllersPath, $mod_id, "", $modControllers);
    	}
    	return array_merge($controllers, $modControllers);
  	}
	
	/**
	 * 扫描文件夹
	 */
	private function _scanDir($contPath, $module="", $subdir="", $controllers = array()) {
    	$handle = opendir($contPath);
    	$del = "-";
    	while (($file = readdir($handle)) !== false) {
     		$filePath = $contPath . DIRECTORY_SEPARATOR . $file;
      		if (is_file($filePath)) {
        		if (preg_match("/^(.+)Controller.php$/", basename($file))) {
          			if ($this->_extendsController($filePath)) {
            			$controllers[] = (($module) ? $module . $del : "") .
              			(($subdir) ? $subdir . "." : "") .
              			str_replace(".php", "", $file);
          			}
        		}
      		} else if (is_dir($filePath) && $file != "." && $file != "..") {
        		$controllers = $this->_scanDir($filePath, $module, $file, $controllers);
      		}
    	}
    	return $controllers;
  	}
	
	/**
	 * 是否基于验证控制器
	 */
	private function _extendsController($controller) {
    	$c = basename(str_replace(".php", "", $controller));
    	if (!class_exists($c, false)) {
      		include_once $controller;
    	} 
    	$cont = new $c($c);
    	if ($cont instanceof Controller) {
      		return true;
    	}
    	return false;
  	}
	
	
	/**
	 * 获取一个控制器的信息
	 */
	private function _getControllerInfo($controller, $getAll = false, $delete = false) {
	    $del = "-";
	    $actions = array();
	    $allowed = array();
	    $auth = Yii::app()->authManager;
	
	    //Check if it's a module controller
	    if (substr_count($controller, $del)) {
	      	$c = explode($del, $controller);
	      	$controller = $c[1];
	      	$module = $c[0] . $del;
	      	$contPath = Yii::app()->getModule($c[0])->getControllerPath();
	      	$control = $contPath . DIRECTORY_SEPARATOR . str_replace(".", DIRECTORY_SEPARATOR, $controller) . ".php";
	    } else {
	      	$module = "";
	      	$contPath = Yii::app()->getControllerPath();
	      	$control = $contPath . DIRECTORY_SEPARATOR . str_replace(".", DIRECTORY_SEPARATOR, $controller) . ".php";
	    }
		$h = file($control);
		for ($i = 0; $i < count($h); $i++) {
	      	$line = trim($h[$i]);
	      	if (preg_match("/^(.+)function( +)action*/", $line)) {
	        	$posAct = strpos(trim($line), "action");
	        	$posPar = strpos(trim($line), "(");
	        	$action = trim(substr(trim($line),$posAct, $posPar-$posAct));
	        	$patterns[0] = '/\s*/m';
	        	$patterns[1] = '#\((.*)\)#';
	        	$patterns[2] = '/\{/m';
	        	$replacements[2] = '';
	        	$replacements[1] = '';
	        	$replacements[0] = '';
	        	$action = preg_replace($patterns, $replacements, trim($action));
	        	$itemId = $module . str_replace("Controller", "", $controller) .
	        	preg_replace("/action/", "", $action, 1);
	        	if ($action != "actions") {
	          		if ($getAll) {
	            		$actions[$module . $action] = $itemId;
	            		if (in_array($itemId, $this->allowedAccess())) {
	              			$allowed[] = $itemId;
	            		}
	          		} else {
	            		if (in_array($itemId, $this->allowedAccess())) {
	              			$allowed[] = $itemId;
	            		} else {
	              			if ($auth->getAuthItem($itemId) === null && !$delete) {
	                			if (!in_array($itemId, $this->allowedAccess())) {
	                  				$actions[$module . $action] = $itemId;
	                			}
	              			} else if ($auth->getAuthItem($itemId) !== null && $delete) {
				                if (!in_array($itemId, $this->allowedAccess())) {
				                  	$actions[$module . $action] = $itemId;
				                }
	              			}
	            		}
	          		}
	        	} else {
	          		//load controller
	          		if (!class_exists($controller, false)) {
	            		require($control);
	          		}
	          		$tmp = array();
	          		$controller_obj = new $controller($controller, $module);
	          		//Get actions
	          		$controller_actions = $controller_obj->actions();
	          		foreach ($controller_actions as $cAction => $value) {
	           			$itemId = $module . str_replace("Controller", "", $controller) . ucfirst($cAction);
	            		if ($getAll) {
	              			$actions[$cAction] = $itemId;
	              			if (in_array($itemId, $this->allowedAccess())) {
	                			$allowed[] = $itemId;
	              			}
	            		} else {
	              			if (in_array($itemId, $this->allowedAccess())) {
	                			$allowed[] = $itemId;
	              			} else {
	                			if ($auth->getAuthItem($itemId) === null && !$delete) {
	                  				if (!in_array($itemId, $this->allowedAccess())) {
	                    				$actions[$cAction] = $itemId;
	                  				}
	                			} else if ($auth->getAuthItem($itemId) !== null && $delete) {
	                  				if (!in_array($itemId, $this->allowedAccess())) {
	                    				$actions[$cAction] = $itemId;
	                  				}
	                			}
	             	 		}
	            		}
	          		}
	        	}
	      	}
	    }    
	    return array('action' => $actions, 'allow'=> $allowed, 'delete' => $delete);
	}
}