<?php
class SlideController extends AdminController{

    public function actionIndex()
    {
        $status_option = Slide::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $this->render('index', array(
            'status' => $status
        ));
    }

    public function actionList()
    {
        $params = CommonFn::getPageParams();
        $status = intval(Yii::app()->request->getParam('status', 100));
        $criteria = new EMongoCriteria($params);


        if($status!=100){
            $criteria->status('==', $status);
        }
        $criteria->sort('order', EMongoCriteria::SORT_DESC);

        $cursor = Slide::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $total = $cursor->count();

        $parsedRows = Slide::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');
        $title = Yii::app()->request->getParam('title', '');
        $pic = Yii::app()->request->getParam('pic', '');
        $order = intval(Yii::app()->request->getParam('order',1));
        $status = intval(Yii::app()->request->getParam('status', 1));
        $type = Yii::app()->request->getParam('type', '');
        $obj = Yii::app()->request->getParam('obj', '');
        $city_info = Yii::app()->request->getParam('city_info', array());
        $start_time = intval(Yii::app()->request->getParam('start_time', 0));
        $end_time = intval(Yii::app()->request->getParam('end_time', 0));


        $cityArray = array();
        $zCity = new ZCityInfo();
        if(!$zCity->checkCity($city_info,$cityArray)){
            CommonFn::requestAjax(false, '请检查城市信息是否正确！');
        }

        if($status == 100){
            CommonFn::requestAjax(false, '必须指定状态！');
        }


        if(!$id){
            $slide = new Slide();
            $op = 'insert';
        }else{
            $op = 'update';
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $slide = Slide::model()->find($criteria);
            if (empty($slide)){
                CommonFn::requestAjax(false, '不存在');
            }
        }

        if($start_time && $end_time && $start_time >= $end_time){
            CommonFn::requestAjax(false, '起始时间、结束时间选择有误');
        }

        if ($title == '' || mb_strlen($title, 'utf-8') < 2 ||mb_strlen($title, 'utf-8') > 40){
            CommonFn::requestAjax(false, '标题至少2个字,最多40个字');
        }

        if(!CommonFn::checkPicFormat($pic)){
            CommonFn::requestAjax(false, '图片地址错了');
        }

        if(!$type){
            CommonFn::requestAjax(false, '类型必选啊');
        }

        $status=$status>1?1:$status;

        if(!$type || $type == "100"){
            CommonFn::requestAjax(false, '必须指定类型！');
        }

        switch ($type) {
            case 'topic':
                $_type = 'Topic';
                $error = '帖子不存在';
                break;
            case 'group':
                $_type = 'Group';
                $error = '圈子不存在';
                break;
            case 'subject':
                $_type = 'Subject';
                $error = '话题不存在';
                break;
            case 'url':
                $_type = 'Url';
                $error = 'url不合法';
                break;
            default:
                CommonFn::requestAjax(false, '参数错误');
                break;
        }

        if(isset($_type) && $_type && $_type != 'Url'){
            $_obj = $_type::get(new MongoId($obj));
            $slide->obj = $_obj->_id;
        } else {
            if(!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$obj)){
                CommonFn::requestAjax(false, 'url填错了！');
            }else{
                $_obj = array("url"=>$obj);
                $slide->obj = $_obj;
            }
        }

        $slide->title = $title;
        $slide->pic = $pic;
        $slide->type = $type;
        $slide->order = $order;
        $slide->status = $status;
        $slide->start_time = $start_time;
        $slide->end_time = $end_time;

        $slide->city_info = $cityArray;
        // var_dump($slide->city_info);die();

        $arr_add = array('title','pic','type','order','status', 'obj','city_info','start_time','end_time');

        $success = $slide->save(true,$arr_add);

        CommonFn::requestAjax($success, '', array());
    }
}