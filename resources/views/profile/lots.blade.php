@if (count(Auth::user()->games()))
    @foreach (Auth::user()->games() as $game)
        @include('profile.lot', $game)
    @endforeach
@endif