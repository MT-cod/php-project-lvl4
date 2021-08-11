@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('flash::message')

            <h1 class="display-10">Статусы</h1>

            @guest
            @else
                <a href="/task_statuses/create" class="btn btn-primary">Создать статус</a>
                <br><br>
            @endguest

            <table class="table table-success table-striped table-sm mx-auto">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Дата создания</th>
                    @guest
                    @else
                    <th scope="col">Действия</th>
                    @endguest
                </tr>
                </thead>
                <tbody>
                @foreach ($taskStatuses as $status)
                    <tr>
                        <td>{{ $status->id }}</td>
                        <td>{{ Str::limit($status->name, 200) }}</td>
                        <td>{{ $status->created_at }}</td>
                        @guest
                        @else
                        <td>
                            <a href="{{ route('task_statuses.edit', $status->id) }}">Изменить</a>
                            <form action="/task_statuses/{{ $status->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value="{{ $status->id }}" name="id">
                                <button type="submit">Удалить</button>
                            </form>
                        </td>
                        @endguest
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $taskStatuses->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection