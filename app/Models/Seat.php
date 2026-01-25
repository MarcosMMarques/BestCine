<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\SeatStatus;

class Seat extends Model
{
    use HasFactory;

    protected $table = 'seat';

    protected $fillable = [
        'row',
        'number',
        'status',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }


    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_seat');
    }
}
