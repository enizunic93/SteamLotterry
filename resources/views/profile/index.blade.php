@extends('layouts.app')

@section('content')
    <h4>Твой профиль</h4>

    {{--<div class="panel-body">--}}
        {{--<!-- Display Validation Errors -->--}}
        {{--@include('common.errors')--}}

                {{--<!-- New Task Form -->--}}
        {{--<form action="/task" method="POST" class="form-horizontal">--}}
            {{--{{ csrf_field() }}--}}

                    {{--<!-- Task Name -->--}}
            {{--<div class="form-group">--}}
                {{--<label for="task-name" class="col-sm-3 control-label">Task</label>--}}

                {{--<div class="col-sm-6">--}}
                    {{--<input type="text" name="name" id="task-name" class="form-control">--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<!-- Add Task Button -->--}}
            {{--<div class="form-group">--}}
                {{--<div class="col-sm-offset-3 col-sm-6">--}}
                    {{--<button type="submit" class="btn btn-default">--}}
                        {{--<i class="fa fa-plus"></i> Add Task--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</form>--}}
    {{--</div>--}}

    @if (isset($web_inventory))
        <div class="row">
            <div class="col-md-12">
                <h2>Твой шмот на сайте</h2>
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th>Пикча</th>
                        <th>Цена (руб)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($web_inventory as $webItem)
                        <tr>
                            <td><img src="{{ $webItem->getItem()->getClearUrl() }}200fx200f" alt=""></td>
                            <td>
                                @if ($webItem->getItem()->getSitePrice() > 0)
                                    {{ $webItem->getItem()->getSitePrice() }} руб
                                @else
                                    Бесценно
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if (isset($inventory))
        <div class="row">
            <div class="col-md-12">
                <h2>Твой шмот из дотана</h2>
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th>Имя:</th>
                        <th>Пикча:</th>
                        <th>Айди:</th>
                        <th>Цена:</th>
                        <th>Тип:</th>
                        <th>Герой:</th>
                        <th>Ссылка на цену:</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($inventory as $item)
                        <tr>
                            <td>{{ $item->getMarketName() }}</td>
                            <td><img src="{{ $item->getClearUrl() }}200fx200f" alt=""></td>
                            <td>
                                {{ $item->getClassId() }}
                            </td>
                            <td>
                                @if ($item->getSitePrice() > 0)
                                    {{ $item->getSitePrice() }} руб
                                @else
                                    Бесценно
                                @endif
                            </td>
                            <td>{{ $item->getType() }}</td>
                            <td>{{ $item->getHero() }}</td>
                            <td><a href="http://anonymouse.org/cgi-bin/anon-www.cgi/http://steamcommunity.com/market/priceoverview/?currency=5&appid=570&market_hash_name={{ urlencode($item->getMarketName()) }}">ТЫК)) ТЫГЫДЫК) ТЫК</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection