@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('flash::message')

            <h1 class="display-10">Задачи</h1>

            @guest
            @else
                <a href="/tasks/create" class="btn btn-primary">Создать задачу</a>
                <br><br>
            @endguest

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
                        <td>{{ Str::limit($task->status_name, 20) }}</td>
                        <td><a href="{{ route('tasks.show', $task->id) }}">{{ Str::limit($task->name, 200) }}</a></td>
                        <td>{{ $task->creator_name }}</td>
                        <td>{{ $task->executor_name }}</td>
                        <td>{{ $task->created_at }}</td>
                        @guest
                        @else
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}">Изменить</a>
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
