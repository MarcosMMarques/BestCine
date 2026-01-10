<?php

namespace App\Exceptions;

use RuntimeException;

class SeatAlreadyReservedException extends RuntimeException
{
    protected $message = 'Este assento já está reservado para esta sessão.';
}
