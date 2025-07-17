<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function get($id)
    {
        return Task::find($id);
    }
    public function getAll(){
        return Task::all();
    }
    public function save(Task $task){
        return $task->save();
    }
    public function delete(Task $task){
        return $task->delete();
    }
    public function getByEventId($eventId){
        return Task::where(['event_id' => $eventId])->get();
    }
}
