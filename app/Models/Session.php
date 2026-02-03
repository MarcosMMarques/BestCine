<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Seat;
use app\Enums\ReservationStatus;

class Session extends Model
{
    use HasFactory;

    protected $table = 'session';

    protected $fillable = [
        'room_id',
        'movie_id',
        'datetime'
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function hasSeatReserved(Seat $seat): bool
    {
        return $this->reservations()
            ->where('status', ReservationStatus::RESERVED)
            ->whereHas('seats', function ($q) use ($seat) {
                $q->where('seat.id', $seat->id);
            })
            ->exists();
    }

    public function getReservedSeats(): array
    {
        $reservedSeats = $this->reservations()
            ->where('status', ReservationStatus::RESERVED)
            ->with('seats')
            ->get()
            ->pluck('seats')
            ->flatten();

        return $reservedSeats->map(function ($seat) {
            $rowLetter = chr(ord('A') + $seat->row - 1);
            return $rowLetter . '-' . $seat->number;
        })->toArray();
    }
}
