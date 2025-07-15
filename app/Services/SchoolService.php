<?php

namespace App\Services;

use App\Models\School;

class SchoolService
{
    public function transform($data)
    {
        $models = [];

        foreach ($data['data']['data'] as $item) {
            $model = new School();
            $model->id = $item['id'];
            $model->name = $item['name'];
            $model->region = $item['region'];
            $models[] = $model;
        }
        return $models;
    }
    public function transformModel($data)
    {

        $item = $data['data']['data'];
        $model = new School();
        $model->id = $item['id'];
        $model->name = $item['name'];
        $model->region = $item['region'];
        return $model;
    }
}
