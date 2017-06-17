<table id="dg" class="easyui-datagrid" 
        url="/index.php?r=userConfig/list"
        toolbar="#toolbar"
        rownumbers="true" fitColumns="true" singleSelect="true">
    <thead>
        <tr>
            <th field="name" width="150">配置名</th>
            <th field="value" width="150">配置值</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newRule()">创建新配置</a> -->
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
            <input name="value" class="easyui-textbox" required="true">
        </div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
<script type="text/javascript">
    function newRule(){
        $('#dlg').dialog('open').dialog('setTitle','New Rule');
        $('#fm').form('clear');
        url = '/index.php?r=sysConfig/set';
    }
    function saveUser(){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return true;
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result.errorMsg){
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function editRule(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('setTitle','编辑配置');
            $('#fm').form('load',row);
            url = '/index.php?r=sysConfig/set';
        }
    }
    
</script>