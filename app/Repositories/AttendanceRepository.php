<?php

namespace App\Repositories;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Models\Attendance;

class AttendanceRepository
{
    public function get($id)
    {
        return Attendance::find($id);
    }
    public function getAll(){
        return Attendance::all();
    }
    public function delete(Attendance $attendance)
    {
        return $attendance->delete();
    }
    public function save(Attendance $attendance){
        return $attendance->save();
    }
    public function getAttendance()
    {
        return Attendance::where(['status' => AttendanceDictionary::ATTENDANCE])->get();
    }
}
