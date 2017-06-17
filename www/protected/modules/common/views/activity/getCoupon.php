<!DOCTYPE html>
<html>
<head>
    <title>壹橙管家</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">



    <script type="text/javascript" src="https://dn-bughd-web.qbox.me/bughd.min.js" crossOrigin="anonymous"></script>
</head>

<body>
<!-- loading -->
<div class="spa-fullscreen"></div>
<div class="spa-loader">
    <div class="spa-loader-animate">
        <div class="bg"></div>
        <span class="ball"></span>
        <span class="ball"></span>

    </div>
</div>

<script type="text/javascript" id="wxMain" data-sign="<?php echo @htmlentities(json_encode($signPackage)); ?>"></script>
<script type="text/javascript">
    (function(){
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            <?php
            if(!empty($userId) && !empty($appToken)){
                echo '
            var wxUserID = localStorage.setItem("wxUserID",  "'.$userId.'");
            var appToken = localStorage.setItem("appToken", "'.$appToken.'");
          ';
            }
            ?>
            var userID = localStorage.getItem('wxUserID');

            if (userID){localStorage.setItem('wxUserID','');}
            location.href = '<?php echo Yii::app()->request->baseUrl . 'index.php?r=/common/activity/wxIndex&coupon_id=' . $coupon_id; ?>';

            document.write('<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"><\/script>');
        }
    })();
</script>

</body>

</html>
