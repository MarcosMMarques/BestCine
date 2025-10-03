<?php

namespace app\Enums;

enum SeatStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case RESERVED = 'RESERVED';
}
