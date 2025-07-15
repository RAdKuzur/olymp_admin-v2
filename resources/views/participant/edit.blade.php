{{-- resources/views/participants/edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Редактировать участника')

@section('content')
    <div class="container mt-4">
        <h1>Редактировать участника</h1>

        <form action="{{ route('participant.update', $participant->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="disability" class="form-label">Ограничения по здоровью</label>
                <select name="disability" id="disability" class="form-control">
                    @foreach($disabilities as $key => $label)
                        <option value="{{ $key }}" {{ $participant->disability == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="class" class="form-label">Класс обучения</label>
                <select name="class" id="class" class="form-control">
                    @foreach($classes as $key => $label)
                        <option value="{{ $key }}" {{ $participant->class == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="citizenship" class="form-label">Гражданство</label>
                <select name="citizenship" id="citizenship" class="form-control">
                    @foreach($countries as $key => $label)
                        <option value="{{ $key }}" {{ $participant->citizenship == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="school_id" class="form-label">Школа</label>
                <select name="school_id" id="school_id" class="form-control">
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $participant->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
