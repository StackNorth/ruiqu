<?php
/**
 * o2o模块公共基类
 */
class TechBaseController extends Controller{
    protected function beforeAction($action) {
        return true;
    }
}