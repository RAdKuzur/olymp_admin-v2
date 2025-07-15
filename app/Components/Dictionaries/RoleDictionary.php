<?php

namespace App\Components\Dictionaries;

class RoleDictionary
{
    public const ADMIN = 0;
    public const JURY = 1;
    public const PARTICIPANT = 2;
    public static function getList(){
        return [
            self::ADMIN => 'Администратор',
            self::JURY => 'Организатор',
            self::PARTICIPANT => 'Участник',
        ];
    }
}
