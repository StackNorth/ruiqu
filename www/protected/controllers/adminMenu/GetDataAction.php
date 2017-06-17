<?php
/**
 * summary: 获取目录列表
 * author: justin
 * date: 2014.03.04
 */
class GetDataAction extends CAction
{
    public function run(){
        $filter_status = intval(Yii::app()->request->getParam('filter_status', 1));
        $criteria = new EMongoCriteria();
        if ($filter_status < 10){
        	$criteria->status('==', $filter_status);
        }  
        $criteria->sort('sort', EMongoCriteria::SORT_ASC);  
    	$cursor = AdminMenuAR::model()->findAll($criteria);
    	$rows = array();
    	$total = 0;
    	$i = 1;
    	foreach ($cursor as $v){
    		$temp = $v->attributes;
    		$temp['_id'] = (string)$temp['_id'];
    		$temp['id'] = $i ++;
    		if ($temp['parent']){
    			$temp['parent'] = $temp['_parentId'] = (string)$temp['parent'];
    		} else {
    			$temp['parent'] = $temp['_parentId'] = '';
    		}
    		if ($temp['url'] == ''){
    			$temp['state'] = 'closed';
    		}		
			$rows[] = $temp;
			if ($temp['level'] == 1){
				$total ++;
			}
    	}

    	$total = $cursor->count();
    	if ($total > 0){
    		$total = 1;
    	}
    	echo CommonFn::composeDatagridData($rows, $total);
    }
}