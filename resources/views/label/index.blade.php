@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('flash::message')

            <h1 class="display-10">Метки</h1>

            @guest
            @else
                <a href="/labels/create" class="btn btn-primary">Создать метку</a>
                <br><br>
            @endguest

            <table class="table table-success table-striped table-sm mx-auto">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Дата создания</th>
                    @guest
                    @else
                    <th scope="col">Действия</th>
                    @endguest
                </tr>
                </thead>
                <tbody>
                @foreach ($labels as $label)
                    <tr>
                        <td>{{ $label->id }}</td>
                        <td>{{ Str::limit($label->name, 20) }}</td>
                        <td>{{ Str::limit($label->description, 200) }}</td>
                        <td>{{ $label->created_at }}</td>
                        @guest
                        @else
                            <td>
                                <a href="{{ route('labels.edit', $label->id) }}">Изменить</a>
                                <a class="text-danger" href="{{ route('labels.destroy', $label->id) }}" data-confirm="Вы уверены?" data-method="delete" rel="nofollow">Удалить</a>
                                {{--<form action="/labels/{{ $label->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" value="{{ $label->id }}" name="id">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                                </form>--}}
                            </td>
                        @endguest
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $labels->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
