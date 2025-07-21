<?php

namespace App\Services;

use App\Models\User;
use DateTime;
use function Illuminate\Events\queueable;

class UserService
{
    public function transform($data)
    {
        $models = [];
        foreach ($data['data']['data'] as $item) {
            $model = new User();
            $model->id = $item['id'];
            $model->email = $item['email'];
            $model->firstname = $item['firstname'];
            $model->surname = $item['surname'];
            $model->patronymic = $item['patronymic'];
            $model->phone_number = $item['phone_number'];
            $model->gender = $item['gender'];
            $model->role = $item['role'];
            $model->birthdate = $item['birthdate'];
            $models[] = $model;
        }
        return $models;
    }
    public function transformModel($data)
    {
        $item = $data['data']['data'];
        $model = new User();
        $model->id = $item['id'];
        $model->email = $item['email'];
        $model->firstname = $item['firstname'];
        $model->surname = $item['surname'];
        $model->patronymic = $item['patronymic'];
        $model->phone_number = $item['phone_number'];
        $model->gender = $item['gender'];
        $model->role = $item['role'];
        $date = DateTime::createFromFormat('Y-m-d H:i:s O e', $item['birthdate']);
        $model->birthdate = $date->format('Y-m-d');
        return $model;
    }
}
