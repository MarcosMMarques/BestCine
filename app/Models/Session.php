<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';

    protected $filled = [
        'room_id',
        'movie_id',
        'datetime'
    ];
}
