@extends('layouts.app')

@section('content')
    @if (count($games))
        @foreach($games as $game)
            @include('lots.lot', $game)
        @endforeach
    @else
        <p>Лотов нет.</p>
    @endif
@endsection