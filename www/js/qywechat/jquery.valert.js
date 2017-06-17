/**
 * Vue-jQuery-Amaze AlertMessage插件
 *
 * author    
 * 2015-12-14
 */
;(function($) {
    $.extend({
        valert: function(string) {
            if (typeof(v_alert) == 'undefined') {
                var _html  = '<div id="valert" class="am-modal am-modal-alert" tabindex="-1">';
                    _html += '<div class="am-modal-dialog">';
                    _html += '<div class="am-modal-hd">{{message}}</div>';
                    _html += '<div class="am-modal-bd"></div>';
                    _html += '<div class="am-modal-footer">';
                    _html += '<span class="am-modal-btn">确定</span>';
                    _html += '</div</div></div>';
                $('body').append(_html);

                jq_valert = $('#valert');

                v_alert = new Vue({
                    el: '#valert',
                    data: {
                        message: 'Unknow'
                    }
                });
            }

            v_alert.message = string;
            jq_valert.modal();
        }
    });
})(jQuery);