<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/12/15
 * Time: 13:13
 */


Yii::import('application.vendor.qiniu.src.Qiniu.*',1);
class  TestController extends AdminController {


    public function actionIndex()
    {
        $accessKey = 'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr';
        $secretKey = 'mLtD4GhBjQt_llcgx4rKlhAts9j8iJ0Qa5VmNyi2';
        $auth = new Qiniu\Auth($accessKey, $secretKey);
        var_dump($secretKey);exit;
        $bucket = 'video';
        $key = 'af_055bd00624e2981480363953002.mp4';
        $pfop = new PersistentFop($auth, $bucket);

        $fops = "?vframe/jpg/offset/1";

        list($id, $error) = $pfop->execute($key, $fops);
        echo "2";
        var_dump($id);
        $this->assertNull($error);
        list($status, $error) = PersistentFop::status($id);
        $this->assertNotNull($status);
        $this->assertNull($error);
    }

    public function actionIndex1(){
        $accessKey = 'H1mZo5YAluX1n3Ic_gWFzQFAty0DedVK24gYWbvq';
        $secretKey = 'Kod0wn_TWOAzFe4WJedhvVHu2CzRC_R3GtpRTPiV';
        $auth = new Qiniu\Auth($accessKey, $secretKey);

        //要转码的文件所在的空间和文件名
        $bucket = 'video';
        $key = 'af_055bd00624e2981480363953002.mp4';

        //转码是使用的队列名称
        $pipeline = '';
        $pfop = new PersistentFop($auth, $bucket, $pipeline);

        //要进行转码的转码操作
        $fops = "vframe/jpg/offset/1";

        list($id, $err) = $pfop->execute($key, $fops);
        echo "\n====> pfop avthumb result: \n";
        if ($err != null) {
            var_dump($err);
        } else {
            echo "PersistentFop Id: $id\n";
        }

        //查询转码的进度和状态
        list($ret, $err) = $pfop->status($id);
        echo "\n====> pfop avthumb status: \n";
        if ($err != null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }
}