<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pendiente = 1;
    case Alistando = 2;
    case Enviando = 3;
    case Recibido = 4;
    case Fallido = 5;
    case Devuelto = 6;
    case Cancelado = 7;
}