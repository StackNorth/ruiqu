;
(function ($) {
    var defaults = {
        onselected:function(value){console.log(value);},
        value:{}    //初始化的选项信息
    };

    var options = null;
    var privateVal = {
        time:9527
    }

    var privateFunction = {
        createDom:function(){
            privateVal.time = new Date().getTime();
            var _html = '<input type="hidden" name="'+options.input_name+'" />\
                        <div>\
                            <div style="float: left;margin-right: 10px;"><input id="serviceType'+privateVal.time+'" style="margin-right: 10px;" /></div><div style="float: left;"><input id="group_'+privateVal.time+'" style="margin-right: 10px;" /></div><div style="clear: both;"></div>\
                        </div>';
            $('#'+options.container).html(_html);
        },
        initCombobox:function(){
            var cat = $('#serviceType'+privateVal.time).combobox({
                url:site_root+'/index.php?r=serviceType/all',
                editable:false,
                valueField:'type',
                textField:'type',
               
            });
            
        }
    }

    var methods = {
        init: function(args) {
            options = $.extend(defaults, args);
            privateFunction.createDom();
            privateFunction.initCombobox();
            
        }
    };

    $.fn.group_selector = function() {
        var method = arguments[0];
        if(methods[method]) {
            method = methods[method];
        } else if( typeof(method) == 'object' || !method ) {
            method = methods.init;
        } else {
            return this;
        }
        var args = arguments[1];
        return method.call(this,args);
    }

})(jQuery);