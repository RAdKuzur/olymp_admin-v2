<?php

namespace App\Components\Dictionaries;

class SubjectDictionary
{
    public const HISTORY = 1;
    public const BIOLOGY = 2;
    public const GEOGRAPHY = 3;
    public const FOREIGN_LANGUAGE = 4;
    public const INFORMATICS = 5;
    public const LITERATURE = 6;
    public const MATH = 7;
    public const SOCIAL = 8;
    public const RUSSIAN = 9;
    public const PHYSICS = 10;
    public const CHEMISTRY = 11;
    public static function getList(){
        return [
            self::HISTORY => 'История',
            self::BIOLOGY => 'Биология',
            self::GEOGRAPHY => 'География',
            self::FOREIGN_LANGUAGE => 'Иностранный язык',
            self::INFORMATICS => 'Информатика и ИКТ',
            self::LITERATURE => 'Литература',
            self::MATH => 'Математика',
            self::SOCIAL => 'Обществознание',
            self::RUSSIAN => 'Русский язык',
            self::PHYSICS => 'Физика',
            self::CHEMISTRY => 'Химия',
        ];
    }
}
