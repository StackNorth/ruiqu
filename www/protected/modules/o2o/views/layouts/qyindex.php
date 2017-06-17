<!DOCTYPE>
<html>
<head>
    <title></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
    <!-- vue 框架 -->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qywechat/vue.js"></script>
    <!-- jquery 框架 -->
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.js"></script>
    <!-- amaze 框架 -->
    <link rel="stylesheet" type="text/css" href="http://cdn.amazeui.org/amazeui/2.5.0/css/amazeui.css">
    <link rel="stylesheet" type="text/css" href="http://cdn.amazeui.org/amazeui/2.5.0/css/amazeui.min.css">
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.5.0/js/amazeui.js"></script>
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.5.0/js/amazeui.min.js"></script>
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.5.0/js/amazeui.ie8polyfill.js"></script>
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.5.0/js/amazeui.ie8polyfill.min.js"></script>
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.5.0/js/amazeui.widgets.helper.js"></script>
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.5.0/js/amazeui.widgets.helper.min.js"></script>
    <!-- 自定义样式 -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/qywechat/style.css?v=20151215">
    <!-- 插件 -->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qywechat/jquery.vtable.js?v=20151215"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qywechat/jquery.valert.js?v=20160106"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qywechat/jquery.vloading.js?v=20151215"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/qywechat/jquery.vselect.js?v=20151215"></script>
</head>
<body>
<?php echo $content; ?>
</body>
</html>