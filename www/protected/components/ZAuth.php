<?php
/**
 * summary: 权限组件
 * author: justin
 * date: 2014.03.04
 */
class ZAuth extends ZComponent
{
    /**
     * 返回操作对应的权限名称
     */
    public function getAuthItem($controller, $action = ''){
        $del = "-";
        $mod = $controller->module !== null ? $controller->module->id . $del: '';
        $act = $action;
        if ($action == ''){
        	if ($controller->action !== null){
        		$act = $controller->action->id;
        	} else {
        		$act = $controller->defaultAction;
        	}
        }
    	$contrArr = explode($del, $controller->id);
    	$contrArr[sizeof($contrArr) - 1] = ucfirst($contrArr[sizeof($contrArr) - 1]);
    	$con = implode(".", $contrArr);
    	$auth_item = $mod . $con . ucfirst($act);
        return $auth_item;
    }
    
}