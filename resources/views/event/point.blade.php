@extends('layouts.main')

@section('title', 'Выставление баллов')

@section('content')
    <a href="{{ route('event.show', $event->id) }}" class="btn btn-sm btn-primary">Перейти в карточку олимпиады</a>
    <div class="event-task">
        <h1>Выставление баллов</h1>
        <table class="table table-bordered table-striped mt-3">
            <thead>
            <tr>
                <th>ФИО участника</th>
                @foreach($tasks as $task)
                    <th>{{ $task->number }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($table as $row)
                <tr>
                    <td>{{     $row['person']->getFullFio() }}</td>
                    @foreach($row['taskAttendances'] as $counter => $taskAttendance)
                        <td> <input type="text"
                                    name="task_points[{{ $taskAttendance->id }}]"
                                    value="{{ $taskAttendance->points ?? '' }}"
                                    maxlength="{{ $taskAttendance->task->max_points }}"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
