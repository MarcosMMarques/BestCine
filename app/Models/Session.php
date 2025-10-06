<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Reservation;

class Session extends Model
{
    protected $table = 'session';

    protected $filled = [
        'room_id',
        'movie_id',
        'datetime'
    ];

    protected function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    protected function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    protected function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
