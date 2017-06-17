<?php
class ProductController extends AdminController{

    public function actionIndex()
    {
        $status_option = Product::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $type = CommonFn::getComboboxData(Yii::app()->params['o2o_service'], 100, true, 100);


        $this->render('index', array(
            'status' => $status,
            'type'=>$type
        ));
    }

    public function actionGet(){
        $id = Yii::app()->request->getParam('id', '');
        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoId($id);
        $product = Product::model()->find($criteria);

        $data = $product->parseRow($product->attributes);
        echo json_encode($data);exit;
    }

    public function actionList(){
        $params = CommonFn::getPageParams();

        $id = Yii::app()->request->getParam('id', '');
        $status = intval(Yii::app()->request->getParam('status', 100));

        $criteria = new EMongoCriteria($params);


        if($status!=100){
            $criteria->status('==', $status);
        }

        if ($id != ''){
            $product_id = new MongoId($id);
            $criteria->_id('==', $product_id);
        }


        $cursor = Product::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Product::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');

        $status = intval(Yii::app()->request->getParam('status', 1));
        $order = intval(Yii::app()->request->getParam('order',1));

        $pics =  Yii::app()->request->getParam('pics',array());

        $extra =  Yii::app()->request->getParam('extra',array());
        $extra = json_decode($extra);

        $name = Yii::app()->request->getParam('name','');
        $desc = Yii::app()->request->getParam('desc','');


        $price = Yii::app()->request->getParam('price',0);

        $type = Yii::app()->request->getParam('type', 0);

        if( !$type || !$name ||!$desc){
            CommonFn::requestAjax(false, '服务/名称/简介类型不能为空');
        }

        if($status == 100|| $type == 100){
            CommonFn::requestAjax(false, '必须指定状态和服务类型！');
        }

        if(mb_strlen($desc,'utf-8')>4000 || mb_strlen($desc,'utf-8')==0 ||is_null(json_decode($desc))){
            CommonFn::requestAjax(false, '请输入4000字以内的Json格式的图文介绍');
        }


        $new_pics = array();
        foreach($pics as $picStr){
            $pic =  json_decode($picStr,true);
            if(isset($pic) && $pic){
                $new_pics[] = $pic;
            }
        }
        $pics = $new_pics;

        if(count($pics)>0)
        {
            foreach($pics as $k=>$v)
            {
                if(CommonFn::checkPicFormat($v['url'])){
                    $pics[$k]=$v;
                }
            }

        }

        $status=$status>1?1:$status;

        if(!$id){
            $product = new Product();
        }else{
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $product = Product::model()->find($criteria);
            if (empty($product)){
                CommonFn::requestAjax(false, '产品/服务 不存在');
            }
        }
        $product->name = $name;
        $product->desc = $desc;
        $product->status = $status;
        $product->order = $order;
        $product->price = $price;
        $product->type = $type;
        $product->pics = $pics;
        $product->extra = $extra;

        $arr_addProduct = array('status','order','price','name','desc','pics','type','extra');

        $success = $product->save(true,$arr_addProduct);


        CommonFn::requestAjax($success, '', array());
    }

}
