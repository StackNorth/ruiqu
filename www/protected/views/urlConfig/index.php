<table id="dg" class="easyui-datagrid" 
        url="/index.php?r=urlConfig/list"
        toolbar="#toolbar"
        rownumbers="true" fitColumns="true" singleSelect="true">
    <thead>
        <tr>
            <th field="key" width="150">配置名</th>
            <th field="value" width="150">配置值</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newRule()">创建新配置</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editRule()">编辑配置</a>
    <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delRule()">删除配置</a> -->
</div>

<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons">
    <form id="fm" method="post" novalidate>
        <div class="fitem">
            <label>配置名:</label>
            <input name="key" class="easyui-textbox" required="true">
        </div>
        <div class="fitem">
            <label>配置值:</label>
            <textarea name="value" class="textbox-text" autocomplete="off" placeholder="" style="width:300px;height:100px" required="true"></textarea>
            <!-- <input name="value" class="easyui-textbox" data-options="multiline:true" style="width:300px;height:100px" required="true"> -->        
        </div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveRule()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
<script type="text/javascript">
function newRule(){
    $('#dlg').dialog('open').dialog('setTitle','新建配置');
    $('#fm').form('clear');
    url = '/index.php?r=urlConfig/edit';
}

function editRule(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $('#dlg').dialog('open').dialog('setTitle','编辑配置');
        $('#fm').form('load',row);
        url = '/index.php?r=urlConfig/edit';
    }
}

function saveRule(){
    $('#fm').form('submit',{
        url: url,
        onSubmit: function(){
            var isValid = $(this).form('validate');
            if (!isValid){
                $.messager.progress('close');
            }
            return isValid;
        },
        success: function(res){
            $.messager.progress('close');
            var res = JSON.parse(res);
            if (res.success) {
                $.messager.show({
                    title : '提示',
                    msg : '操作成功',
                    timeout : 3500,
                    showType : 'slide'
                });
                $('#dlg').dialog('close');
                $('#dg').datagrid('reload');
            } else {
                $.messager.show({
                    title : '提示',
                    msg : res.message,
                    timeoutout : 3500,
                    showType : 'slide'
                });
            };
        }
    });
}  
</script>