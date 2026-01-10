<?php

namespace App\Exceptions;

use RuntimeException;

class UserAlreadyHasReservationException extends RuntimeException
{
    protected $message = 'O usuário já possui uma reserva para esta sessão.';
}
