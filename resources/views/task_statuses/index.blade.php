@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="display-10">Статусы</h1>

            @guest
            @else
                <a href="/task_statuses/create" class="btn btn-primary">Создать статус</a>
                <br><br>
            @endguest

            <table class="table table-success table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Дата создания</th>
                </tr>
                </thead>
                <tbody>
            @foreach ($statuses as $status)
                    <tr>
                        <td>{{$status->id}}</td>
                        <td>{{Str::limit($status->name, 200)}}</td>
                        <td>{{$status->created_at}}</td>
                    </tr>
            @endforeach

                    </tbody>
                </table>
            <div class="d-flex justify-content-center">
            {{ $statuses->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
