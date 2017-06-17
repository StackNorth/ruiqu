<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ueditor/ueditor.all.js?v=1"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ueditor/lang/zh-cn/zh-cn.js"></script>
<table id="dg" class="easyui-datagrid" url="/index.php?r=staticSource/list" toolbar="#toolbar" rownumber="true" fitColumns="true" singleSelect="true">
    <thead>
        <tr>
            <th field="key" width="150">资源名称</th>
            <th field="title" width="150">标题</th>
            <th field="content_short" width="150">内容</th>
            <th field="remark" width="150">备注</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:;" class="easyui-linkbutton" iconCls="icon-add" onclick="newSource();return false;">新资源</a>
    <a href="javascript:;" class="easyui-linkbutton" iconCls="icon-edit" onclick="editSource();return false;">修改</a>
</div>

<div id="dialog" class="easyui-dialog" style="width:800px;height:600px;padding:10px 20px;" closed="true" buttons="#dialog-buttons">
    <form id="form" method="post" novalidate>
        <div class="fitem">
            <input name="id" hidden="true">
            <label>资源名称</label>
            <input name="key" class="easyui-textbox" required="true">
            <label>标题</label>
            <input name="title" class="easyui-textbox" required="true">
            <label>备注</label>
            <input name="remark" style="width:250px;">
        </div>
        <div class="fitem">
            <script type="text/plain" id="ueditor_container" style="width:720px;height:420px;"></script>
        </div>
    </form>
</div>
<div id="dialog-buttons">
    <a href="javascript:;" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveSource();">保存</a>
    <a href="javascript:;" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dialog').dialog('close');">取消</a>
</div>

<script type="text/javascript">
$(function(){

    // 初始化UEditor
    UE.getEditor('ueditor_container', {
        serverUrl: '/index.php?r=ueditorUploader/UeditorUploader',
        toolbars: [
            [
                'anchor', //锚点
                'undo', //撤销
                'redo', //重做
                'bold', //加粗
                'indent', //首行缩进
                'snapscreen', //截图
                'italic', //斜体
                'underline', //下划线
                'strikethrough', //删除线
                'subscript', //下标
                'fontborder', //字符边框
                'superscript', //上标
                'formatmatch', //格式刷
                'source', //源代码
                'blockquote', //引用
                'pasteplain', //纯文本粘贴模式
                'selectall', //全选
                'print', //打印
                'preview', //预览
                'horizontal', //分隔线
                'removeformat', //清除格式
                'time', //时间
                'date', //日期
                'unlink', //取消链接
                'insertrow', //前插入行
                'insertcol', //前插入列
                'mergeright', //右合并单元格
                'mergedown', //下合并单元格
                'deleterow', //删除行
                'deletecol', //删除列
                'splittorows', //拆分成行
                'splittocols', //拆分成列
                'splittocells', //完全拆分单元格
                'deletecaption', //删除表格标题
                'inserttitle', //插入标题
                'mergecells', //合并多个单元格
                'deletetable', //删除表格
                'cleardoc', //清空文档
                'insertparagraphbeforetable', //"表格前插入行"
                'insertcode', //代码语言
                'fontfamily', //字体
                'fontsize', //字号
                'paragraph', //段落格式
                'simpleupload', //单图上传
                'insertimage', //多图上传
                'edittable', //表格属性
                'edittd', //单元格属性
                'link', //超链接
                'emotion', //表情
                'spechars', //特殊字符
                'searchreplace', //查询替换
                'map', //Baidu地图
                'gmap', //Google地图
                'insertvideo', //视频
                'help', //帮助
                'justifyleft', //居左对齐
                'justifyright', //居右对齐
                'justifycenter', //居中对齐
                'justifyjustify', //两端对齐
                'forecolor', //字体颜色
                'backcolor', //背景色
                'insertorderedlist', //有序列表
                'insertunorderedlist', //无序列表
                'fullscreen', //全屏
                'directionalityltr', //从左向右输入
                'directionalityrtl', //从右向左输入
                'rowspacingtop', //段前距
                'rowspacingbottom', //段后距
                'pagebreak', //分页
                'insertframe', //插入Iframe
                'imagenone', //默认
                'imageleft', //左浮动
                'imageright', //右浮动
                'attachment', //附件
                'imagecenter', //居中
                'wordimage', //图片转存
                'lineheight', //行间距
                'edittip ', //编辑提示
                'customstyle', //自定义标题
                'autotypeset', //自动排版
                'webapp', //百度应用
                'touppercase', //字母大写
                'tolowercase', //字母小写
                'background', //背景
                'template', //模板
                'scrawl', //涂鸦
                'music', //音乐
                'inserttable', //插入表格
                'drafts', // 从草稿箱加载
                'charts', // 图表
            ]
        ],
        zIndex: 9999
    });

});

function editSource() {
    var row = $('#dg').datagrid('getSelected');
    if (row) {
        var content = row.content;
        UE.getEditor('ueditor_container').setContent(content, false);
        $('#form').form('load', row);
        $('#dialog').dialog('open').dialog('setTitle', '编辑内容');
    }
}

function newSource() {
    $('#form').form('clear');
    UE.getEditor('ueditor_container').setContent('', false);
    $('#dialog').dialog('open').dialog('setTitle', '新建资源');
}

function saveSource() {
    $('#form').form('submit', {
        url: '/index.php?r=staticSource/edit',
        onSubmit: function() {
            return true;
        },
        success: function (result) {
            var data = JSON.parse(result);
            if (data.success) {
                $('#dialog').dialog('close');
                $('#dg').datagrid('reload');
            } else {
                $.messager.show({
                    title: '保存失败',
                    msg: data.msg
                });
            }
        }
    });
}
</script>