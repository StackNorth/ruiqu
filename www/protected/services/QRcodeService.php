<?php
/**
*生成二维码
*/
$path = Yii::getPathOfAlias('application');
require_once($path."/vendors/qrcode/qrlib.php");
class QRcodeService extends Service{
    public function CreatQrcode($data,$imagefile,$imagesize = 8){ 
        $errorCorrectionLevel = 'L';
        QRcode::png($data,$imagefile,$errorCorrectionLevel,$imagesize,2);
    }
}
?>