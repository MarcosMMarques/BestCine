<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case SUCCEEDED = 'succeeded';
}
