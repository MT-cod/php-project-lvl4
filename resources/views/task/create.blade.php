@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Создать задачу') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Имя') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Описание') }}</label>

                                <div class="col-md-6">
                                    <textarea class="form-control" name="description" cols="50" rows="10" id="description"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status_id" class="col-md-4 col-form-label text-md-right">{{ __('Статус') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control @error('status_id') is-invalid @enderror" id="status_id" name="status_id">
                                        <option selected="selected" value="">----------</option>
                                        @foreach ($taskStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('status_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="assigned_to_id" class="col-md-4 col-form-label text-md-right">{{ __('Исполнитель') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="assigned_to_id" name="assigned_to_id">
                                        <option selected="selected" value="">----------</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="LABELS" class="col-md-4 col-form-label text-md-right">{{ __('Метки') }}</label>

                                {{--<div class="col-md-6">
                                    <select class="form-control" id="assigned_to_id" name="assigned_to_id">
                                        <option selected="selected" value="">----------</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('assigned_to_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>--}}
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Создать') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
