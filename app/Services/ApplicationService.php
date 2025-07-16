<?php

namespace App\Services;

use App\Models\Application;

class ApplicationService
{
    public function transform($data)
    {
        $models = [];
        return $models;
    }
    public function transformModel($data)
    {
        $model = new Application();
        return $model;
    }
}
