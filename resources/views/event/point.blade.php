@extends('layouts.main')

@section('title', 'Выставление баллов')

@section('content')
    <a href="{{ route('event.show', $event->id) }}" class="btn btn-sm btn-primary">Перейти в карточку олимпиады</a>
@endsection
