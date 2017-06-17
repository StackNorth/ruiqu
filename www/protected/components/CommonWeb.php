<?php
/**
 * Created by PhpStorm.
 * User: songyongming
 * Date: 14/12/2
 * Time: 22:32
 */

class CommonWeb
{
    //分页
    public static function pager($total_items, $items_per_page=20,$current_page=1,  $url,$client_type){
        //echo($url);exit;
        if($client_type == 'pc'){
           return self::pager_pc($total_items, $items_per_page,$current_page,  $url);
        }else{
           return self::pager_mobile($total_items, $items_per_page,$current_page,  $url);
        }
    }

    //PC版的分页
    public static function pager_pc($total_items, $items_per_page=20,$current_page=1,  $url){
        //echo($url);exit;
        if ($total_items <= $items_per_page) {
            return '';
        }
        if($items_per_page==0){
            return '';
        }
        $pages_str='<ul class="am-pagination am-pagination-centered">';

        $max_pages = ceil($total_items / $items_per_page );

        //上一页
		if($current_page>1){
            $page=$current_page-1;
            if($page == 1){
                $pages_str.='<li><a href="'.str_replace("/%d%/", '', $url).'">&laquo;</a></li>';
            }else{
                $pages_str.='<li><a href="'.str_replace("%d%", $page, $url).'">&laquo;</a></li>';
            }

        }

        //第一页
		if($current_page>1){
            $page_str='<li><a href="'.str_replace("/%d%/", '', $url).'">1</a></li>';
            $pages_str.=$page_str;
        }else{
            $pages_str.='<li class="am-active"><a href="#">1</a></li>';
        }

        //显示当前两页的前两页到后两页
		if($max_pages-$current_page<3){
            $prev_2_page=$max_pages-5;
        }else{
            $prev_2_page=$current_page-2;
        }


		if($prev_2_page<2){
            $prev_2_page=2;
        }
		$next_2_page=$prev_2_page+5;
		if($next_2_page>$max_pages) $next_2_page=$max_pages;


		for($i=$prev_2_page; $i<=$next_2_page; $i++){
            if($i==$current_page){
                $pages_str.='<li class="am-active"><a href="#">'.$i.'</a></li>';
            }else{
                $pages_str.='<li><a href="'.str_replace("%d%", $i, $url).'">'.$i.'</a></li>';
            }
        }

		//下一页
		if($current_page<$max_pages){
            $page=$current_page+1;
            $pages_str.='<li><a href="'.str_replace("%d%", $page, $url).'">&raquo;</a></li>';
        }

        $pages_str.'</ul>';
        return $pages_str;
    }

    //手机版的分页
    public static function pager_mobile($total_items, $items_per_page=20,$current_page=1,  $url){
        if ($total_items <= $items_per_page) {
            return '';
        }
        if($items_per_page==0){
            return '';
        }
        $pages_str='<ul data-am-widget="pagination" class="am-pagination am-pagination-select">';

        $max_pages = ceil($total_items / $items_per_page );

        //上一页
        if($current_page>1){
            $page=$current_page-1;
            $pages_str.='<li class="am-pagination-prev "><a href="'.str_replace("%d%", $page, $url).'">上一页</a></li>';
        }

        $pages_str.='<li class="am-pagination-select"><select>';

        for($i=1; $i<=$max_pages; $i++){
            if($i==$current_page){
                $pages_str.='<option value="#" class="">'.$i.'/'.$max_pages.'</option>';
            }
        }

        $pages_str.= '</select></li>';

//下一页
        if($current_page<$max_pages){
            $page=$current_page+1;
            $pages_str.='<li class="am-pagination-next "><a href="'.str_replace("%d%", $page, $url).'">下一页</a></li>';
        }

        $pages_str.'</ul>';
        return $pages_str;
    }


    
    //判断终端类型
    //返回设备类型
    public static function checkmobile() {
        $mobile = array();
        static $touchbrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
            'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
            'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
            'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
            'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
            'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
            'benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
        static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
            'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
            'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');

        static $pad_list = array('ipad');

        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        }else{
            $useragent = '';
        }

        if(CommonFn::dstrpos($useragent, array('windows'))) {
            return 'pc';
        }
        
        if(CommonFn::dstrpos($useragent, $pad_list)) {
            return 'pad';
        }
        if(($v = CommonFn::dstrpos($useragent, $touchbrowser_list, true))){
            if(strpos($useragent, 'iphone') || strpos($useragent, 'ipod')){
                return 'ios';
            }else{
                return 'android';
            }
        }
        if(($v = CommonFn::dstrpos($useragent, $wmlbrowser_list))) {
            return 'android';
        }

        return 'pc';
    }

    

    public static function header_redirect($url, $code=301){
        header('Location: '.$url, true, $code);
        return false;
    }

    

}