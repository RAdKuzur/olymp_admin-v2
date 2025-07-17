<?php

namespace App\Repositories;

use App\Models\TaskAttendance;

class TaskAttendanceRepository
{
    public function get($id){
        return TaskAttendance::find($id);
    }
    public function getAll(){
        return TaskAttendance::all();
    }
    public function getByTaskId($taskId){
        return TaskAttendance::where(['task_id' => $taskId])->get();
    }
    public function getByAttendanceId($attendanceId)
    {
        return TaskAttendance::where(['attendance_id' => $attendanceId])->get();
    }
    public function save(TaskAttendance $taskAttendance){
        return $taskAttendance->save();
    }
    public function delete(TaskAttendance $taskAttendance){
        return $taskAttendance->delete();
    }
}
