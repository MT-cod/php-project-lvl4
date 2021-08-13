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
                            <a class="text-danger" href="{{ route('task_statuses.destroy', $status->id) }}" data-confirm="Вы уверены?" data-method="delete" rel="nofollow">Удалить</a>
                            {{--ВАРИАНТ ПОДТВЕРЖДЕНИЯ ДЕЙСТВИЯ БУТСТРАПОМ
                            <form action="/task_statuses/{{ $status->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value="{{ $status->id }}" name="id">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>--}}
                            {{--ВАРИАНТ ПОДТВЕРЖДЕНИЯ ДЕЙСТВИЯ Collective и JS
                            <div class="pl-1">
                                <button type="button" name="button"
                                        onclick="if(confirm('Вы уверены?')){ $('form#delete-{{$status->id}}').submit(); }"
                                        class="btn btn-danger btn-sm">Удалить
                                </button>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['task_statuses.destroy',$status->id], 'class' => 'hidden', 'id'=>"delete-".$status->id]) !!}
                                {!! Form::close() !!}
                            </div>--}}
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
