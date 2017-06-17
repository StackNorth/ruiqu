/**
 * Vue-jQuery-Amaze Loading插件
 *
 *     2015-12-15
 */
;(function($) {
    $.extend({
        vloading: function(opt) {
            if (typeof(v_loading) == 'undefined') {
                var _html  = '<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="vloading">';
                    _html += '<div class="am-modal-dialog">';
                    _html += '<div class="am-modal-hd">正在载入</div>';
                    _html += '<div class="am-modal-bd">';
                    _html += '<span class="am-icon-spinner am-icon-spin"></span>';
                    _html += '</div></div></div>';
                $('body').append(_html);

                v_loading = $('#vloading');
            }

            if (opt == 'open' || typeof(opt) == 'undefined') {
                v_loading.modal('open');
            } else if(opt == 'close') {
                v_loading.modal('close');
            } else {
                return true;
            }
        }
    })
})(jQuery);