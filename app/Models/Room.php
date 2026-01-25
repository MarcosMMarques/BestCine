<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Session;
use App\Models\Seat;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';

    protected $fillable = [
        'name',
        'seat_quantity',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
