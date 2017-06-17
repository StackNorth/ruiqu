<?php
class CommentController extends AdminController{

    public function actionIndex()
    {
        $status_option = Comment::$status_option;
        $type_option = Yii::app()->params['o2o_service'];
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);
        //var_dump($status);die();
        $type = CommonFn::getComboboxData($type_option, 100, true, 100);



        //var_dump($type);die();
        $this->render('index', array(
            'type' => $type,
            'status' => $status
            
        ));

    }


        

    //获得评价列表
    public function actionList(){
//var_dump($_GET);die();
        $params = CommonFn::getPageParams();
 
        $status = intval(Yii::app()->request->getParam('status', 100));
        $id = Yii::app()->request->getParam('id', '');
        $type = intval(Yii::app()->request->getParam('type',100));
        $weight = intval(Yii::app()->request->getParam('weight',100));
        $score = intval(Yii::app()->request->getParam('score',100));
        $search = Yii::app()->request->getParam('search', '');
        $search_type = intval(Yii::app()->request->getParam('search_type', 1));
        $criteria = new EMongoCriteria($params);
        if ($search != ''){
            $user = RUser::getUserByName($search);
            if($user){
                $criteria->user('==',$user->_id);
            }
        }
        if ($id != ''){
            $comment_id = new MongoId($id);
            $criteria->_id('==', $comment_id);
        }
        if($score !=100){
            $criteria->score('==', $score);
        }
        if ($type != 100){
            $criteria->type('==', $type);
            
        }
        if ($weight !=100){
            $criteria->weight('==', $weight);
        }
        if ($status != 100){
            $criteria->status('==', $status);
        }

       
      
      
        $cursor = Comment::model()->findAll($criteria);
//var_dump($cursor);die();
       
        $total = $cursor->count();
//var_dump($total);die();
        $rows = CommonFn::getRowsFromCursor($cursor);

        $parsedRows = Comment::model()->parse($rows);
        //var_dump($parsedRows);
//var_dump($parsedRows);die();

        echo CommonFn::composeDatagridData($parsedRows, $total);
   
    }

    public function actionUpdate(){
        $id = Yii::app()->request->getParam('id', '');
        $status = intval(Yii::app()->request->getParam('status', 1));
        $weight = intval(Yii::app()->request->getParam('weight', 100));
        $reply  = Yii::app()->request->getParam('reply', '');
        

        if($status == 100){
            CommonFn::requestAjax(false, '必须指定评价状态！');
        }

        $criteria = new EMongoCriteria();
        $mongo_id = new MongoId($id);
        $criteria->_id = $mongo_id;
        $comment = Comment::model()->find($criteria);
        
        $comment->status = $status;
       // $post->pics =   $pics;
        $comment->weight = $weight;    
        $comment->reply  = $reply;
        $arr_post = array('status','weight', 'reply');
        $success = $comment->save(true,$arr_post);
        CommonFn::requestAjax($success, '', array());
        
    }

    //批量修改评价状态
    public function actionSetStatus(){
        $ids = Yii::app()->request->getParam('ids',"");
        $status = intval(Yii::app()->request->getParam('status',100));

        if ($status == 100){
            CommonFn::requestAjax(false,"必须设置状态值");
        }
        $status=$status>1?1:$status;
        $id_array = explode(",",$ids);

        if(!count($id_array) || !$ids){
            CommonFn::requestAjax(false,"请选择待修改的评价");
        }

        $criteria = new EMongoCriteria();
        foreach($id_array as $id){
            $mongo_id = new MongoId($id);

            $criteria->_id('==', $mongo_id);
            $post = Comment::model()->find($criteria);
            if($post){
                if($post->status===$status){
                    continue;
                }
                $old_status = $post->status;
                $post->status = $status;
                $post->update(array( 'status'),true);
              
            }
        }
        CommonFn::requestAjax();
    }


}
