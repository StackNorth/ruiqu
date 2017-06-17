<div region="west" border="false" style="width: 650px;">
        <div id="tb_content">
            <form id="date_form" method="post">
                        <div class="box" style="float:left">
                            <div class="f_label">
                                <span>开始日期:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input id="start_time_str" type="text" >
                                <input type="hidden" name="start_time" id="start_time"  />
                            </div>
                        </div>
                        <div class="box" style="float:left">
                            <div class="f_label">
                                <span>结束日期:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input id="stop_time_str" type="text" >
                                <input type="hidden" name="stop_time" id="stop_time"  />
                            </div>
                        </div>
                        <div class="box" style="float:left">
                            <div class="f_label">
                                <span>关键字:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input name="keyword" type="text" >
                            </div>
                        </div>
                        <div class="box" style="float:left">
                            <div class="f_label">
                                <span>分值:</span>
                            </div>
                            <div class="box_flex f_content">
                                <input name="score" type="text" >
                            </div>
                        </div>
                <input type="submit" value="发放" />
            </form>
        </div>
</div>
    
<script type="text/javascript">
$(function(){
    $('#start_time_str').datebox({
        required: false,
        onSelect: function(date){
            var currentDate = new Date();
            $('#start_time').val(date.getTime()/1000);
        }
    });
    $('#stop_time_str').datebox({
        required: false,
        onSelect: function(date){
            var currentDate = new Date();
            $('#stop_time').val(date.getTime()/1000);
        }
    });
});
</script>