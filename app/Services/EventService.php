<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function transform($data)
    {
        $models = [];
        return $models;
    }
    public function transformModel($data)
    {
        $model = new Event();
        return $model;
    }
}
