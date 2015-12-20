(function ($) {
    var setupAnimation = function() {
        var coinImage,
            canvas;

        canvas = document.getElementById("preloader");
        coinImage = new Image();
        coinImage.src = "/public/img/preloader.png";

        createAnimation({
            context: canvas.getContext("2d"),
            image: coinImage,
            numberOfFrames: 20,
            ticksPerFrame: 1
        }).gameLoop();
    };

    var showAnimation = function () {
        setupAnimation();
        $(document.getElementById('preloader')).css('display', 'block').hide().fadeIn('fast');
    };

    var hideAnimation = function (after) {
        var def = function () {
        };
        after = (!after) ? def : after;

        var canvas = document.getElementById("preloader");
        $(canvas).fadeOut("fast", after);
    };

    if ($.support.pjax) {
        $.pjax.defaults.scrollTo = true;

        $("#menu a").click(function () {
            $("#menu a .dot").each(function (a, b) {
                $(b).removeClass('active');
            });

            $(this).find(".dot").addClass('active');
        });

        $(document).pjax('a', '#main', {
            timeout: 50000
        });

        $(document).on('pjax:beforeSend', function (e) {
            showAnimation();
            $("#main").fadeOut('slow');
        });

        $(document).on('pjax:success', function (e) {
            $("#main").fadeIn('slow');
            hideAnimation();
        });
    }

    $(document).ready(function () {
        setupAnimation();
    });
})(jQuery);

(function ($) {
    $('.profile .lots-carousel .lot').each(function () {
        $(this).mouseover(function () {
            var marq = $(this).find('marquee')[0];
            if (marq) {
                marq.start();
            }
        });

        $(this).mouseout(function () {
            var marq = $(this).find('marquee')[0];
            if (marq) {
                marq.stop();
            }
        });
    });

    $('.profile .lots').profileLots({
        context: '#main'
    });

    $('.profile .trade-link').profileTrade();
})(jQuery);

/*Footer*/
var FooterUsed = false;

$('.label').on('click', function () {
    if (FooterUsed === true) {
        FooterUsed = false;
        $('.f-content').css('display', 'none');
        $('.footer').css('height', '0px');
    } else {
        FooterUsed = true;
        $('.footer').css('height', '93px');
        setTimeout("$('.f-content').css('display', 'block')", 350);
        $('body,html').animate({scrollTop: $('.label').offset().top}, 800);
    }
});