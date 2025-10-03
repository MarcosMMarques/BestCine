<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SeatStatus;

class Seat extends Model
{
    protected $table = 'seat';

    protected $fillable = [
        'row',
        'number',
        'status',
    ];
}
