<?php
/**
 * summary: MongoDB管理
 * author: lee
 * date: 2014.05.09
 */
class DBRecordController extends AdminController
{

	public function actionGetRows(){
		$search = Yii::app()->request->getParam('search', '');
		$params = CommonFn::getPageParams();
		$criteria = new EMongoCriteria($params);
		$criteria->status('>', -3);
		if ($search != ''){
			$criteria->name('==', new MongoRegex('/'.$search.'/'));
		}
        $cursor = User::model()->findAll($criteria);
		$total = $cursor->count();
		$rows = CommonFn::getRowsFromCursor($cursor);
		foreach ($rows as $k => $v){
			$rows[$k]['_id'] = (string)$v['_id'];
		}
		echo CommonFn::composeDatagridData($rows, $total);
	}

    public function actionGetDetailRows(){
        $user_id = (int)Yii::app()->request->getParam('user_id', '');
        //$filter_cat = intval(Yii::app()->request->getParam('filter_cat', 0));
        $filter_start_time = Yii::app()->request->getParam('filter_start_time', '');
        $filter_end_time = Yii::app()->request->getParam('filter_end_time', '');
        $params = CommonFn::getPageParams();
        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);
        $criteria->action("elemmatch",array('user'=>$user_id));
        $cursor = DbAction::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $total = $cursor->count();
        foreach($rows as $k=>$v)
        {

            switch($v["c_name"])
            {
                case "topics":
                    $mongo_id = new MongoId($v["r_id"]);
                    $criteria = new EMongoCriteria();
                    $criteria->_id('==', $mongo_id);
                    $topic = Topic::model()->find($criteria);
                    if(isset($topic->title))
                    {
                        $tmpTitle = $topic->title;
                    }
                   else
                   {
                       $delTopic = DeleteTopicsAr::model()->find($criteria);
                       $tmpTitle = $delTopic->title;
                       if(isset($tmpTitle))
                       {
                           $tmpTitle = "null";
                       }
                   }
                break;
            }
            $rows[$k]['title']=$tmpTitle;
        }
        echo CommonFn::composeDatagridData($rows, $total);


       // echo CommonFn::composeDatagridData($rows, $total);

        /*
        $criteria = new EMongoCriteria($params);
        //$criteria->db_name("==","area_org");
        //$criteria->action("elemmatch",array('user'=>15));
        //$cursor = DbAction::model()->findAll($criteria);
        $cursor = User::model()->findAll($criteria);
        CommonFn::showList($cursor);
        */



       /*
        if ($user_id != ''){
            $criteria->user('==', new MongoId($user_id));
        }
        if ($filter_cat > 0){
            $criteria->cat('==', $filter_cat);
        }

        if ($filter_start_time != ''){
            $criteria->create_time('>=', strtotime($filter_start_time));
        }
        if ($filter_end_time != ''){
            $criteria->create_time('<=', strtotime($filter_end_time));
        }
        $cursor = ScoreRecord::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $cat_option = ScoreRecord::$cat_option;
        foreach ($rows as $k => $v){
            $rows[$k]['create_time'] = date("Y-m-d H:i", $v['create_time']);
            $rows[$k]['cat_name'] = $cat_option[$v['cat']]['name'];
        }
       */
        //echo CommonFn::composeDatagridData($rows, $total);
    }



}