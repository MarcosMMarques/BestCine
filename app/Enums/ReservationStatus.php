<?php

namespace app\Enums;

enum ReservationStatus: string
{
    case CANCELED = 'CANCELED';
    case RESERVED = 'RESERVED';
}
