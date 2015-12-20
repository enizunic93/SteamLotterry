(function ($) {
    // значение по умолчанию - ЗЕЛЁНЫЙ
    var defaults = {};

    // актуальные настройки, глобальные
    var options;

    var methods = {
        // инициализация плагина
        init: function (params) {
            // актуальные настройки, будут индивидуальными при каждом запуске
            var options = $.extend({}, defaults, params);

            return this.each(function() {
                $(this).submit(function (e) {
                    var $form = $(this);
                    var ajaxData = {
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        data: $form.serialize()
                    };
                    $.ajax(ajaxData).done(function(data) {
                        console.log(data);
                    }).fail(function(data) {
                    });

                    e.preventDefault();
                });
            });
        }
    };


    $.fn.profileTrade = function (method) {
        // немного магии
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            // если ничего не получилось
            $.error('Метод "' + method + '" не найден в плагине jQuery.mySimplePlugin');
        }
    };
    //TODO: kek
        //var ajaxData = {};
        //ajaxData = $.extend({}, {
        //    url: $(self).val()
        //}, ajaxData, params.data);
        //
        //$.ajax({
        //    url: options.url,
        //    context: options.context,
        //    data: ajaxData
        //}).done(function (msg) {
        //    $(self).html(msg);
        //});
})(jQuery);