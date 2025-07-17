@extends('layouts.main')

@section('title', 'Явки')

@section('content')
    <a href="{{ route('event.show', $event->id) }}" class="btn btn-sm btn-primary">Перейти в карточку олимпиады</a>
    <table class="table table-bordered table-striped mt-3">
        <thead>
        <tr>
            <th>#</th>
            <th>ФИО участника</th>
            <th>Явка</th>
        </tr>
        </thead>
        <tbody>
        @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ 'FIO' }}</td>
                <td>
                    <select name="status-{{$attendance->id}}" id="status-{{$attendance->id}}" class="form-control">
                        @foreach($attendanceStatuses as $key => $status)
                            <option value="{{ $key }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
