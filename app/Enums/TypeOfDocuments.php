<?php

namespace App\Enums;

enum TypeOfDocuments: string
{
    case DNI = 'DNI';
    case CE = 'CE';

    public function label(): string
    {
        return match ($this) {
            self::DNI => 'DNI',
            self::CE => 'CE (Carnet de extranjería)'
        };
    }
}
