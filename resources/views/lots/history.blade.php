@extends('layouts.app')

@section('content')
    <div class="history">
        <table class="table">
            <tbody>
            <tr>
                <th>Лот</th>
                <th>Дата</th>
                <th>Предмет</th>
                <th>Победитель</th>
                <th>История игры</th>
            </tr>
            @if (count($history))
                @foreach ($history as $story)
                    <tr>
                        <td>#{{ $story->id }}</td>
                        <td>{{ date('H:i:s d.m', strtotime($story->game->end_at)) }}</td>
                        <td>
                            {{ $story->game->lot->getItem()->getName() }}</td>
                        <td>
                            @if (!is_null($story->game->winner))
                                <a href="{{ $story->game->winner->getSteamLink() }}" class="nick" target="_blank">{{ $story->game->winner->name }}</a>
                            @else
                                <p style="font-style:italic;color:#D35353">Пользователь удалён</p>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="button"><i class="batch">&#xF02E</i> Смотреть</a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection