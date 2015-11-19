@extends('layouts.app')

@section('content')

    <div class="panel-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
        <form action="/task" method="POST" class="form-horizontal">
            {{ csrf_field() }}

                    <!-- Task Name -->
            <div class="form-group">
                <label for="task-name" class="col-sm-3 control-label">Task</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="task-name" class="form-control">
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> Add Task
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if (isset($inventory))
            <h2>Шмот</h2>
            <hr>
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th>Вещь:</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($inventory as $item)
                    <tr>
                        <td>
                            {{ dump($item) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    @endif
@endsection