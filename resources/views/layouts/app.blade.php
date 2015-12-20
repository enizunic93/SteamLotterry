<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Рулетка, лотерея, goskins, госкинс, csgo, cs:go, скины, кейсы, ксго">
    <meta name="description" content="Нож за 16 рублей? Без проблем! Заходи на goskins.pw и выигрывай!">
    <title>GOSKINS.PW</title>
    <link rel="shortcut icon" href="{{ asset('public/img/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('public/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/modal.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
<div id="fairplay" class="modal">
    <div class="m-title">
        ЧЕСТНАЯ ИГРА - КАК ЭТО РАБОТАЕТ?
        <div class="m-close"><i class="fa fa-times"></i></div>
    </div>
    <div class="m-body">
        Технология «честной игры» на нашем сайте работает таким образом, что победителя выбирает не наша система, а сайт
        <a target="__blank" href="http://random.org">Random.org</a>
        <br>
        <a target="__blank" href="http://random.org">Random.org</a> – самый популярный и авторитетный генератор
        случайных чисел в мире.
        <br>
        Как только набирается необходимое количество пользователей на одном предмете - мы
        посылаем запрос на <a target="__blank" href="http://random.org">random.org</a> с числом, которое является
        количеством всех пользователей,
        после чего <a target="__blank" href="http://random.org">random.org</a> возвращает нашей системе случайное число,
        под которым и
        выбирается обладатель предмета.
        <br>
        После завершения, под выбраным победителем появится такая панель,в которой, нажав на
        кнопку «проверить» вас перебросит на сайт random.org, где вы сможете все проверить сами.
    </div>
</div>
<div id="help" class="modal">
    <div class="m-title">
        ОКАЗЫВАЕМ ПОМОЩЬ В ЛЮБОЕ ВРЕМЯ!
        <div class="m-close"><i class="fa fa-times"></i></div>
    </div>
    <div class="m-body">
        <a target="__blank" class="button" href="http://vk.com/goskinspw" style="width: 100%; text-align: center">СООБЩИТЬ
            О СВОЕЙ ПРОБЛЕМЕ</a>
    </div>
</div>
<div class="push-wrapper">
    <div class="push-block push-blue">
        <div class="push-c push-c-blue">
					<span class="push-i push-i-blue">
						<p>?</p>
					</span>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
    </div>
    <div class="push-block push-red">
        <div class="push-c push-c-red">
					<span class="push-i push-i-red">
						<p>!</p>
					</span>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
    </div>
    <div class="push-block push-orange">
        <div class="push-c push-c-orange">
					<span class="push-i push-i-orange">
						<p>W</p>
					</span>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
    </div>
    <div class="push-block push-green">
        <div class="push-c push-c-green">
					<span class="push-i push-i-green">
						<p>+</p>
					</span>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="header">
        <a href="http://vk.com/goskinspw" class="banner"></a>

        <div id="menu" class="menu">
            <span class="menu-line"></span>
            <a href="/" id="index" class="item" data-pjax="true">
                <p>ГЛАВНАЯ</p>
                <i class="dot{{ Request::is('/') ? ' active' : null }}"></i>
                <i class="batch" data-icon="&#xF162"></i>
            </a>
            <a href="/history" id="history" class="item" data-pjax="true">
                <p>ИСТОРИЯ</p>
                <i class="dot{{ Request::is('history') ? ' active' : null }}"></i>
                <i class="batch" data-icon="&#xF0D1"></i>
            </a>
            <a href="#" id="fair_play" class="item" data-reveal-id="fairplay" data-animation="fade">
                <p>ЧЕСТНАЯ ИГРА</p>
                <i class="dot"></i>
                <i class="batch" data-icon="&#xF075"></i>
            </a>
            <a href="#" id="support" class="item" data-reveal-id="help" data-animation="fade">
                <p>ПОДДЕРЖКА</p>
                <i class="dot"></i>
                <i class="batch" data-icon="&#xF077"></i>
            </a>
        </div>
    </div>
    @if (Auth::guest())
        <div class="profile inactive">
            <a href="/auth/login" class="button">Для доступа к профилю требуется авторизация</a>
        </div>
    @else
        <div class="profile">
            <div class="profile-left">
                <div class="avatar">
                    <img src="@if (!empty(Auth::user()->avatar)) {{ Auth::user()->avatar }} @else asset('public/img/standart_avatar.jpg') @endif"
                         alt="">
                </div>
                <div class="info">
                    <div class="nick">
                        <a href="#" title="{{ Auth::user()->name }}">
                            <marquee behavior="scroll" scrolldelay="5"
                                     scrollamount="15">{{ Auth::user()->name }}</marquee>
                        </a>

                        <div class="p-block orange">{{ Auth::user()->balance }}<i class="fa fa-rub"></i></div>
                        <div class="p-block red">!</div>
                        <div class="p-block blue">Panel</div>
                    </div>
                    <div id="settings">
                        <form id="id_balance" class="balance">
                            <input type="text" placeholder="Сумма пополнения">
                            <input class="button" type="submit" value="Пополнить">
                        </form>
                        <form id="id_trade-link" class="trade-link" action="{{ route('profile.update.tradeUrl') }}"
                              method="POST">
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="text" placeholder="Ваша трейд-ссылка">
                            <a target="_blank"
                               href="http://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url"
                               class="button blue">?</a>
                            <input class="button" type="submit" value="Сохранить">
                        </form>
                    </div>
                </div>
            </div>
            <div class="profile-right">
                <div class="capsule">
                    <div class="button">Лоты с вашим участием</div>
                    <div class="button inactive">История ваших выигрышей</div>
                </div>
                <div class="lots-carousel">
                    <div class="carousel-button green">
                        <i class="fa fa-chevron-left"></i>
                    </div>
                    <div class="lots"></div>
                    {{--@if (count(Auth::user()->games()))--}}
                    {{--<div class="lots">--}}
                    {{--@foreach (Auth::user()->games() as $game)--}}
                    {{--<div class="lot">--}}
                    {{--<img src="{{ $game->lot->getItem()->getClearUrl() }}" alt="">--}}
                    {{--@if (!is_null($game->winner))--}}
                    {{--<div class="tooltip blue arrow-top">--}}
                    {{--<div class="avatar">--}}
                    {{--<img src="{{ $game->lot->getItem()->getClearUrl() }}" alt="">--}}
                    {{--</div>--}}
                    {{--<div class="info">--}}
                    {{--<p>Победитель:</p>--}}

                    {{--<h2>--}}
                    {{--<marquee behavior="scroll"--}}
                    {{--scrollamount="10">{{ $game->winner->name }}</marquee>--}}
                    {{--</h2>--}}
                    {{--<p>Сумма выигрыша:</p>--}}

                    {{--<h2>{{ $game->lot->getItem()->getSitePrice() }}<i class="fa fa-rub"></i>--}}
                    {{--</h2>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--@endif--}}
                    {{--</div>--}}
                    {{--@endforeach--}}
                    {{--</div>--}}
                    {{--@endif--}}
                    <div class="carousel-button green">
                        <i class="fa fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="content" id="pjax-container">
        <canvas id="preloader"></canvas>
        <div id="main">
            @yield('content')
        </div>
    </div>
</div>
<div id="label" class="label">ИНФОРМАЦИЯ</div>
<div id="footer" class="footer">
    <div class="f-content">
        <a href="#" class="logo"></a>

        <p>Powered by Steam, a registered trademark of Valve Corporation.<br>GOSKINS.PW - 2015</p>
    </div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://pjax.heroku.com/jquery.pjax.js"></script>
<script src="{{ asset('public/js/preloader.js') }}"></script>
<script src="{{ asset('public/js/lots.profile.jquery.js') }}"></script>
<script src="{{ asset('public/js/trade.profile.jquery.js') }}"></script>
<script src="{{ asset('public/js/main.js') }}"></script>
<script src="{{ asset('public/js/modal.js') }}"></script>
</body>
</html>