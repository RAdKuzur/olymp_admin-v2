<?php

namespace App\Components\Dictionaries;

class GenderDictionary
{
    public const MALE = 0;
    public const FEMALE = 1;
    public static function getList(){
        return [
            self::MALE => 'Мужской',
            self::FEMALE => 'Женский'
        ];
    }
}
