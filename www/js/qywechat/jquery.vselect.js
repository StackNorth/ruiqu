/**
 * Vue-jQuery Select插件
 * 为防止Vue.js与jQuery冲突，该插件未使用Vue.js
 * 
 * author    
 * 2016-01-05
 */
;(function($) {
    $.fn.extend({
        'vselect': function(args) {
            // 默认设置
            var defaults = {
                options: [],
                selected: 0,
                onSelect: function(value, index) {
                    console.log(value, index);
                },
                style: 'warning',
                maxheight: '640px',
                btnWidth: '100%',
                btnSize: 'xl',
            }

            if (typeof(vselect_options) == 'undefined') {
                vselect_options = $.extend(defaults, args);
            } else {
                vselect_options = $.extend(vselect_options, args);
            }

            // 内部方法
            vselect_privateFunction = {
                selected: function(value, index) {
                    vselect_options.onSelect(value, index);
                }
            }

            // 生成选择框
            var _html  = '<select class="v_select">';
            var options = vselect_options.options;
            for(key in options) {
                if (key == vselect_options.selected) {
                    _html += '<option value="'+options[key]['value']+'" index="'+key+'" selected>';
                } else {
                    _html += '<option value="'+options[key]['value']+'" index="'+key+'">';
                }
                _html += options[key]['text'];
                _html += '</option>';
            }
            _html += '</select>';
            this.html(_html);

            // 改变select值时的方法
            this.find('.v_select').change(function() {
                var value = $(this).val();
                var index = $(this).children('option[value="'+value+'"]').index();
                vselect_privateFunction.selected(value, index);
            });

            // AmazeUI
            this.find('.v_select').selected({
                btnWidth: vselect_options.btnWidth,
                btnSize: vselect_options.btnSize,
                btnStyle: vselect_options.style,
                maxHeight: vselect_options.maxHeight
            });
        },

        'getVIndex' : function() {
            if (typeof(vselect_options) != 'undefined') {
                return vselect_options.selected;
            } else {
                return 0;
            }
        },

        'setVIndex' : function(val) {
            if (typeof(vselect_options) != 'undefined') {
                return vselect_options.selected = val;
            } else {
                return false;
            }
        }
    });
})(jQuery);