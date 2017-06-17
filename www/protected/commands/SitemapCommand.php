<?php
/**
 * Created by PhpStorm.
 * User: north
 * Date: 2017/6/17
 * Time: 上午10:28
 */
class SitemapCommand extends CConsoleCommand
{
    public function actionTest($type) {
        $criteria = new EMongoCriteria();
        $criteria->uid = $type;
        $rquser = RqUser::model()->find($criteria);
        $rquser->psot = 0;
        $rquser->save();
    }
}