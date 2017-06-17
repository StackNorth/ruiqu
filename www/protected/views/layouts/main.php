<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="nofollow">
    <meta name="robots" content="noarchive">
    <title></title>
    <link rel="shortcut icon" href="http://olasdjcaw.bkt.clouddn.com/icon.jpg" />
    <link id="easyuiTheme" rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/jquery-easyui-1.3.6/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/jquery-easyui-1.3.6/themes/icon.css?v=20160222">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/common/style.css?v=201406221314">
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/jquery-easyui-1.3.6/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/jquery-easyui-1.3.6/jquery.easyui.min.js?v=201602031010"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/jquery-easyui-1.3.6/easyloader.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/jquery-easyui-1.3.6/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/extension/jquery-easyui-edatagrid/jquery.edatagrid.js?v=201402131314"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/easyui/extension/extend.js?v=201405191314"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js?v=201602031011"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/promise.min.js?v=201401021314"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinycon.min.js?v=201401021314"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=B349f0b32ef6e78b2e678f45cb9fddaf"></script>
    <script type="text/javascript">
        function change_theme(themeName){
            set_theme(themeName);
            $.cookie('easyui_theme', themeName, {
                expires: 7,
                path: '/'
            });
        }
        function isNumber( s )
        {
            var regu = "^[0-9]+$";
            var re = new RegExp(regu);
            if (s.search(re) != - 1) {
                return true;
            }
            else {
                return false;
            }
        }
        function set_theme(themeName){
            var jq_easyuiTheme = $('#easyuiTheme');
            var url = jq_easyuiTheme.attr('href');
            var href = url.substring(0, url.indexOf('themes')) + 'themes/' + themeName + '/easyui.css';
            jq_easyuiTheme.attr('href', href);
            var jq_iframe = $('iframe');
            if (jq_iframe.length > 0) {
                for (var i = 0; i < jq_iframe.length; i++) {
                    var ifr = jq_iframe[i];
                    $(ifr).contents().find('#easyuiTheme').attr('href', href);
                }
            }
        }
        function show_news(news){
            Tinycon.setOptions({
                width: 7,
                height: 9,
                font: '8px arial',
                colour: '#ffffff',
                background: '#357FC6',
                fallback: true
            });
            Tinycon.setBubble(news);
        }
        //获得页面请求的url参数
        function get_para(){
            if(window.location.href.indexOf('&') != -1){
                return '&'+window.location.href.slice(window.location.href.indexOf('&') + 1);
            }else{
                return '';
            }
        }
        //关闭最左侧的边栏
        function hide_nav(){
            //console.log($(window.parent.document).find('body').find('.layout-panel-west'));
            //$(window.parent.document).find('body').layout('collapse','west');
            var parent = $(window.parent.document);
            if(!parent.find('.panel-tool-expand').length){
                parent.find('.layout-button-left').click();
            }
        }
        function unixtime(d){
            var time = new Date(d);
            return(time.getTime());
        }
        //对齐标签
        $(function(){
            if ($.cookie('easyui_theme')){
                set_theme($.cookie('easyui_theme'));
            }
            setTimeout(function(){
                $('.combo-f,.validatebox-text').prev('span').addClass('easyui-align-center');
                $('.validatebox-text').addClass('easyui-align-center');
            }, 200);
            var page_param = get_param_obj();
			if (!!page_param['r']){
				storage_prefix = page_param['r'] + '_';
			}
        });
        var site_root = '<?php echo('http://'.$_SERVER['HTTP_HOST']); ?>';

        var user_id = '<?php echo Yii::app()->user->getId(); ?>';
    </script>

    <style>
        /* datagrid toolbat */
        .datagrid-toolbar {padding: 3px;}
        /* linkbutton对齐 */
        a.l-btn {vertical-align: middle;}
        /* label对齐 */
        .easyui-align-center {vertical-align: middle;}
        /* accordion */
        .accordion-body {padding: 5px;}
        /* searchbox */
        .searchbox {vertical-align: middle;}
        .right {float: right;}
        ul {margin: 0;padding: 0;}
        ul li {list-style: none;}
    </style>
</head>
<body>
<div id="map_container"></div>
<?php echo $content; ?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/position_selector.js?v=201505243209"></script>
</body>
</html>
