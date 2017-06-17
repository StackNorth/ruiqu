<!DOCTYPE html>
<!--HTML5 doctype-->
<html>

<head>
    <title>壹橙管家</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <script type="text/javascript">
    (function(){
        var codeURI = '<?php echo $codeURI; ?>';
        var appURI = '<?php echo $appURI; ?>';

        var wxUserID = localStorage.getItem('wxUserID');
        if (wxUserID) {
            location.href = appURI+'&userId='+wxUserID;
        } else {
            location.href = codeURI;
        }
    })();
    </script>
</body>

</html>
