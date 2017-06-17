<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/10/10
 * Time: 13:47
 */
class UeditorUploaderController extends AdminController {

    /**
     * Ueditor上传接口
     */
    public function actionUeditorUploader() {
        $uploader = new UeditorUploader();
        $uploader->handleAction()->printResult();
    }
}