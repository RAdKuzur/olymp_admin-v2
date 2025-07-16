@extends('layouts.main')

@section('title', 'Редактирование заявки')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('application.index') }}">Список заявок</a></li>
    <li class="breadcrumb-item active">Редактирование заявки</li>
@endsection

@section('content')
    <div class="application-edit">
        <h1>Редактирование заявки #{{ $application->code }}</h1>

        <form action="{{ route('application.update', $application->id) }}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-header">Основная информация</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status">Статус заявки</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="completed" {{ $application->status == 'completed' ? 'selected' : '' }}>Завершена</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Информация об участнике</div>
                <div class="card-body">
                    <div class="form-group">
                        <option value="">Выберите участника</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->getFullFio() }}</option>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Информация о мероприятии</div>
                <div class="card-body">
                    <div class="form-group">
                        <option value="">Выберите мероприятие</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">Статус</div>
                <div class="card-body">
                    <div class="form-group">
                        <option value="">Выберите статус</option>
                        @foreach($statuses as $index => $status)
                            <option value="{{ $index + 1 }}">{{ $status }}</option>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <a href="{{ route('application.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection
