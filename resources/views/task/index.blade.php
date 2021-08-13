@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('flash::message')

            <h1 class="display-10">Задачи</h1>

            <div class="d-flex">
                <div>
                    <form method="GET" action="/tasks" accept-charset="UTF-8" class="form-inline">
                        <select class="form-control mr-2" name="filter[status_id]">
                            <option selected="selected" value="">Статус</option>
                            @foreach ($taskStatuses as $status)
                                @if (
                                    isset($_REQUEST['filter']['status_id']) &&
                                    ($_REQUEST['filter']['status_id'] !== '') &&
                                    ($status->id == $_REQUEST['filter']['status_id'])
                                    )
                                    <option selected="selected" value={{ $status->id }}>{{ $status->name }}</option>
                                @else
                                    <option value={{ $status->id }}>{{ $status->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-control mr-2" name="filter[created_by_id]">
                            <option selected="selected" value="">Автор</option>
                            @foreach ($users as $user)
                                @if (
                                    isset($_REQUEST['filter']['created_by_id']) &&
                                    ($_REQUEST['filter']['created_by_id'] !== '') &&
                                    ($user->id == $_REQUEST['filter']['created_by_id'])
                                    )
                                    <option selected="selected" value={{ $user->id }}>{{ $user->name }}</option>
                                @else
                                    <option value={{ $user->id }}>{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-control mr-2" name="filter[assigned_to_id]">
                            <option selected="selected" value="">Исполнитель</option>
                            @foreach ($users as $user)
                                @if (
                                    isset($_REQUEST['filter']['assigned_to_id']) &&
                                    ($_REQUEST['filter']['assigned_to_id'] !== '') &&
                                    ($user->id == $_REQUEST['filter']['assigned_to_id'])
                                    )
                                    <option selected="selected" value={{ $user->id }}>{{ $user->name }}</option>
                                @else
                                    <option value={{ $user->id }}>{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <input class="btn btn-outline-primary mr-2" type="submit" value="Применить">
                    </form>
                </div>
                    @guest
                    @else
                        <a href="/tasks/create" class="btn btn-primary">Создать задачу</a>
                    @endguest
            </div>
            <br>

            <table class="table table-success table-striped table-sm mx-auto">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Автор</th>
                    <th scope="col">Исполнитель</th>
                    <th scope="col">Дата создания</th>
                    @guest
                    @else
                    <th scope="col">Действия</th>
                    @endguest
                </tr>
                </thead>
                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ Str::limit($task->status->name, 20) }}</td>
                        <td><a href="{{ route('tasks.show', $task->id) }}">{{ Str::limit($task->name, 200) }}</a></td>
                        <td>{{ $task->creator->name }}</td>
                        <td>{{ $task->executor->name }}</td>
                        <td>{{ $task->created_at }}</td>
                        @guest
                        @else
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}">Изменить</a>
                            @can('delete', $task)
                                <a class="text-danger" href="{{ route('tasks.destroy', $task->id) }}" data-confirm="Вы уверены?" data-method="delete" rel="nofollow">Удалить</a>
                                {{--<form action="/tasks/{{ $task->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" value="{{ $task->id }}" name="id">

                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                                </form>--}}
                            @endcan
                        </td>
                        @endguest
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $tasks->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
