(function($) {
    // значение по умолчанию - ЗЕЛЁНЫЙ
    var defaults = { url: '/profile/lots', context: document.body };

    // актуальные настройки, глобальные
    var options;

    $.fn.profileLots = function(params){
        // при многократном вызове функции настройки будут сохранятся, и замещаться при необходимости
        options = $.extend({}, defaults, options, params);

        var self = this;
        $(self).html('Загрузка...');

        $.ajax({
            url: options.url,
            context: options.context
        }).done(function(msg) {
            $(self).html(msg);
        });

        return this;
    };
})(jQuery);