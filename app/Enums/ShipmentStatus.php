<?php

namespace App\Enums;

enum ShipmentStatus: int
{
    case Pendiente = 1;
    case Enviando = 2;
    case Recibido = 3;
    case Fallido = 4;
}
