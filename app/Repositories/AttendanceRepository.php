<?php

namespace App\Repositories;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Models\Attendance;

class AttendanceRepository
{
    private ApplicationRepository $applicationRepository;
    public function __construct(
        ApplicationRepository $applicationRepository
    )
    {
        $this->applicationRepository = $applicationRepository;
    }

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
    public function getAttendances()
    {
        return Attendance::where(['status' => AttendanceDictionary::ATTENDANCE])->get();
    }
    public function getByApplicationId($applicationId)
    {
        return Attendance::where(['application_id' => $applicationId])->get();
    }
    public function getAttendancesByEventId($id)
    {
        $applications = $this->applicationRepository->getByEventId($id);
        return Attendance::where(['status' => AttendanceDictionary::ATTENDANCE])->where(['application_id' => array_column($applications, 'id')])->get();
    }
    public function create($applicationId){
        Attendance::create([
            'application_id' => $applicationId,
            'status' => AttendanceDictionary::NO_ATTENDANCE,
        ]);
    }
}
