
    <!DOCTYPE html>
    <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Error <?php echo $data['code']; ?></title>
        <style type="text/css">
        /*<![CDATA[*/
        body {font-family:"Verdana";font-weight:normal;color:black;background-color:white;}
        h1 { font-family:"Verdana";font-weight:normal;font-size:18pt;color:red }
        h2 { font-family:"Verdana";font-weight:normal;font-size:14pt;color:maroon }
        h3 {font-family:"Verdana";font-weight:bold;font-size:11pt}
        p {font-family:"Verdana";font-weight:normal;color:black;font-size:9pt;margin-top: -5px}
        .version {color: gray;font-size:8pt;border-top:1px solid #aaaaaa;}
        /*]]>*/
        </style>
        </head>
        <body>
            <h1>此错误 <?php echo '已记录'.$data['code']; ?></h1>
            <h2><?php echo nl2br(CHtml::encode($data['message'])); ?></h2>
            <p>如果您发现此错误，请与<?php echo $data['admin']; ?>联系</p>
            <div class="version">
            <?php echo date('Y-m-d H:i:s',$data['time']) .' '. $data['version']; ?>
            </div>
        </body>
    </html>