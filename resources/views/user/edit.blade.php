@extends('layouts.app')

@section('title', 'Редактирование пользователя ' . $user->getFullFio())

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Список пользователей</a></li>
    <li class="breadcrumb-item active">Редактирование пользователя {{ $user->getFullFio() }}</li>
@endsection

@section('content')
    <div class="user-form">
        <h1>Редактирование пользователя {{ $user->getFullFio() }}</h1>

        <form id="dynamic-form" method="POST" action="{{ route('user.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="email">Email (логин)</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required>
                @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password">Новый пароль (оставьте пустым, если не нужно менять)</label>
                <input type="password" class="form-control" id="password" name="password">
                @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="surname">Фамилия</label>
                <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" maxlength="255" required>
                @error('surname')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="firstname">Имя</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" maxlength="255" required>
                @error('firstname')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="patronymic">Отчество</label>
                <input type="text" class="form-control" id="patronymic" name="patronymic" value="{{ old('patronymic', $user->patronymic) }}" maxlength="255">
                @error('patronymic')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Телефон</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number"
                       value="{{ old('phone_number', $user->phone_number) }}"
                       pattern="\+7\s?[\(]{0,1}9[0-9]{2}[\)]{0,1}\s?\d{3}[-]{0,1}\d{2}[-]{0,1}\d{2}"
                       placeholder="+7 (999) 999-99-99">
                @error('phone_number')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="gender">Пол</label>
                <select class="form-control" id="gender" name="gender" required>
                    @foreach($genders as $key => $value)
                        <option value="{{ $key }}" {{ old('gender', $user->gender) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('gender')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="role">Роль</label>
                <select class="form-control" id="role" name="role" required>
                    @foreach($roles as $key => $value)
                        <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('role')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="birthdate">Дата рождения</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate"
                       value="{{ old('birthdate', $user->birthdate) }}"
                       max="{{ date('Y-m-d') }}" required>
                @error('birthdate')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>

    <script>
        // Маска для телефона
        document.getElementById('phone_number').addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            e.target.value = '+7' + (x[2] ? ' (' + x[2] : '') + (x[3] ? ') ' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
        });
    </script>
@endsection
