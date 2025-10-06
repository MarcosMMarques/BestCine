<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< Updated upstream
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Session;
use App\Models\Seat;
>>>>>>> Stashed changes

class Room extends Model
{
    protected $table = 'room';

    protected $filled = [
        'name',
        'seat_quantity',
    ];
<<<<<<< Updated upstream
=======

    protected function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    protected function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
>>>>>>> Stashed changes
}
