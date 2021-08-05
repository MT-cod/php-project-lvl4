@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="display-10">Просмотр задачи: {{ $task->name }} <a href="{{ route('tasks.edit', $task->id) }}">🔧</a></h1>

            <p>Имя: {{ $task->name }}</p>
            <p>Статус: {{ $task->status->name }}</p>
            <p>Описание: {{ $task->description }}</p>
            <p>Метки:</p>
        </div>
    </div>
</div>
@endsection
