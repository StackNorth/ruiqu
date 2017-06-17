<?php
/**
 * o2o模块公共基类
 */
class O2oBaseController extends Controller{
    protected function beforeAction($action) {
        return true;
    }

    public function shiHuiCheckSign(){
        header('Content-type: application/json');
        $request=array();
        if(is_array($_GET)){
            foreach($_GET as $k=>$v){
                $request[$k]=$v;
            }
        }
        if(is_array($_POST)){
            foreach($_POST as $k=>$v){
                $request[$k]=$v;
            }
        }
        $temp_args=array();
        $sign='';
        if(is_array($request)){
            foreach($request as $_key => $_value) {
                if($_key!='sign' && $_key!='r'){
                    $temp_args[$_key]=$_value;
                }elseif($_key == 'sign'){
                    $sign = $_value;
                }
            }
        }
        if($sign){
            if(isset($temp_args)&&!empty($temp_args)){
                ksort($temp_args);
            }
            $arg_str = '';
            foreach($temp_args as $k=>$v){
                $arg_str .= $k.$v;
            }

            $arg_str .= Yii::app()->params['key'];

            $new_sign= strtoupper(md5($arg_str));

            if($new_sign!=$sign){
                echo '{
                    "code": "10001",
                    "msg": "false",
                   
                }';
                die();
            }else{
                return true;
            }
        }else{
            echo '{
                "code": "10001",
                "msg": "false",
                
            }';
            die();
        }
    }
}