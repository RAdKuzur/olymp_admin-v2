@extends('layouts.main')

@section('title', 'Выставление баллов')

@section('content')
    <a href="{{ route('event.show', $event->id) }}" class="btn btn-sm btn-primary">Перейти в карточку олимпиады</a>
    <div class="event-task">
        <h1>Выставление баллов</h1>
        <table class="table table-bordered table-striped mt-3">
            <thead>
            <tr>
                <th>#</th>
                <th>ФИО участника</th>
                @foreach($tasks as $task)
                    <th>{{ $task->number }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($attendances as $task => $attendance)
                <tr>
                    <td>{{ $index + 1}}</td>
                    <td>{{ 'FIO' }}</td>
                    @foreach($attendance->taskAttendances as $counter => $taskAttendance)
                        <td>{{$taskAttendance->points}}</td>
                    @endforeach
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
