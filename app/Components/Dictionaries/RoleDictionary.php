<?php

namespace App\Components\Dictionaries;

class RoleDictionary
{
    public const ADMIN = 1;
    public const JURY = 2;
    public const PARTICIPANT = 3;
    public static function getList(){
        return [
            self::ADMIN => 'Администратор',
            self::JURY => 'Организатор',
            self::PARTICIPANT => 'Участник',
        ];
    }
}
