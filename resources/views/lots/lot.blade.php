<div class="i-lot b-orange"> <!--Начало лота-->
    <div class="image">
        {{--{{ asset('public/img/item_image.png') }}--}}
        <img src="{{ $game->lot->getItem()->getClearUrl() }}" alt="">
    </div>
    <div class="t-block">
        <p>{{ $game->lot->getItem()->getName() }}</p>
    </div>
    <div class="t-block WearCategory4">
        <p>Закаленное в боях</p>
    </div>
    <div class="t-block">
        <p>Цена в стиме</p>

        <div class="price orange">
            {{ $game->lot->getItem()->getSitePrice() }} <i class="fa fa-rub"></i>
        </div>
    </div>
    <div class="load-wrap">
        <div class="text orange">
            <h1>{{ $game->lot->places }}</h1>

            <h3>МЕСТ<br>ВСЕГО</h3>
        </div>
        <div class="load-bar orange" style="width: 10%">
            <div class="text">
                <h1>{{ $game->lot->places }}</h1>

                <h3>МЕСТ<br>ОСТАЛОСЬ</h3>
            </div>
        </div>
    </div>
    <span class="hovered-i-lot">
        <div class="ticket-info">
            <div class="tickets">
                <p class="places">{{ $game->lot->places }}</p>

                <p class="price">{{ $game->lot->price_per_place }}<i class="fa fa-rub"></i></p>
            </div>
        </div>
        <a href="#" class="buy">
            <p>КУПИТЬ ЗА {{ $game->lot->price_per_place }}<i class="fa fa-rub"></i></p>
        </a>
    </span>
</div>