/**
 * 自动填充选择器
 * 页面内添加<input class="*_selector">
 * 已关闭cool auto-suggest插件内的错误提示（源代码136、140行）
 */
;
(function ($) {
    var user_selector = $('.user_selector');
    user_selector.coolautosuggest({
        url: 'index.php?r=material/selectUser&user=',
        showDescription: true
    });

    var material_selector = $('.material_selector');
    material_selector.coolautosuggest({
        url: 'index.php?r=material/selectMaterial&material=',
        showDescription: false
    });

    var station_selector = $('.station_selector');
    station_selector.coolautosuggest({
        url: 'index.php?r=stock/selectStation&station=',
        showDescription: false
    });
})(jQuery);