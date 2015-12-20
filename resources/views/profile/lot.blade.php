<div class="lot">
    <img src="{{ $game->lot->getItem()->getClearUrl() }}" alt="">
    @if (!is_null($game->winner))
        <div class="tooltip blue arrow-top">
            <div class="avatar">
                <img src="{{ $game->lot->getItem()->getClearUrl() }}" alt="">
            </div>
            <div class="info">
                <p>Победитель:</p>

                <h2>
                    <marquee behavior="scroll"
                             scrollamount="10">{{ $game->winner->name }}</marquee>
                </h2>
                <p>Сумма выигрыша:</p>

                <h2>{{ $game->lot->getItem()->getSitePrice() }}<i class="fa fa-rub"></i>
                </h2>
            </div>
        </div>
    @endif
</div>