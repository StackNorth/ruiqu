<?php
/**
 * UEditor上传
 */
$path = Yii::getPathOfAlias('application')."/vendors/qiniu";
require_once($path."/rs.php");
require_once($path."/io.php");
require_once($path."/http.php");
require_once($path."/auth_digest.php");

class UeditorUploader {

    public  $action;
    private $config;
    private $result;

    /**
     * 构造方法
     * 从ueditor/config.json获取ueditor上传配置
     */
    public function __construct() {
        // ueditor配置
        $this->config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(dirname(__FILE__)."/ueditor_config.json")), true);
        $this->action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'config';
    }

    /**
     * 处理控制器请求
     */
    public function handleAction() {
        switch ($this->action) {
            /* 获取配置 */
            case 'config':
                $this->result = json_encode($this->config);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $this->result = $this->actionUpload();
                break;

            // /* 列出图片 */
            // case 'listimage':
            //     $this->result = $this->actionList();
            //     break;
            // /* 列出文件 */
            // case 'listfile':
            //     $this->result = $this->actionList();
            //     break;
            // /* 抓取远程文件 */
            // case 'catchimage':
            //     $this->result = $this->actionCrawler();
            //     break;
            default:
                $this->result = json_encode([
                    'state' => '请求地址出错'
                ]);
                break;
        }
        return $this;
    }

    /**
     * 向前端打印结果
     */
    public function printResult() {
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
            if (preg_match("/^[\w_]+$/", $callback)) {
                echo htmlspecialchars($callback) . '(' . $this->result . ')';
            } else {
                echo json_encode([
                    'state'=> 'callback参数不合法'
                ]);
            }
        } else {
            echo $this->result;
        }
    }

    public function actionUpload() {
        switch ($this->action) {
            case 'uploadimage':
                $fieldName = $this->config['imageFieldName'];
                $bucket = Yii::app()->params['qiniuConfig']['pics'];
                break;
            case 'uploadvideo':
                $fieldName = $this->config['videoFieldName'];
                $bucket = Yii::app()->params['qiniuConfig']['video'];
                break;
            default:
                $fieldName = $this->config['fileFieldName'];
                $bucket = 'null';
                break;
        }

        if ($bucket == 'null') {
            return json_encode(['state' => '上传文件出错']);
        }

        $file = $_FILES[$fieldName];
        // 命名
        $saveName = md5(time() + rand());
        $saveKey = $saveName.'.'.pathinfo($file['name'], PATHINFO_EXTENSION);
        // 临时存储路径
        $savePath = dirname($file['tmp_name']).'/'.$saveKey;
        if (!file_exists($savePath)) {
            move_uploaded_file($file['tmp_name'], $savePath);
        }

        $status = $this->uploadToQiniu($savePath, $saveKey, $bucket);
        if (!$status) {
            unlink($savePath);
            return json_encode(['state' => '上传文件到七牛失败']);
        } else {
            unlink($savePath);
            $url = 'http://'.$bucket.'.qiniudn.com/'.$saveKey;
            $fileInfo = [
                'state'    => 'SUCCESS',
                'url'      => $url,
                'title'    => $saveName,
                'original' => $file['name'],
                'type'     => $file['type'],
                'size'     => $file['size'],
            ];

            return json_encode($fileInfo);
        }
    }

    public function uploadToQiniu($file, $upname, $bucket) {
        $qiniu_config = Yii::app()->params['qiniuConfig'];
        $accessKey = $qiniu_config['ak'];
        $secretKey = $qiniu_config['sk'];
        Qiniu_SetKeys($accessKey, $secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret,$err) = Qiniu_PutFile($upToken,$upname,$file,$putExtra);
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
    }

}
?>