<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Session;
use App\Models\User;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation';

    protected $fillable = [
        'session_id',
        'user_id',
        'status'
    ];

    public function isCanceled()
    {
        return $this->status == ReservationStatus::CANCELED;
    }

    public function isReserved()
    {
        return $this->status == ReservationStatus::RESERVED;
    }

    public function setStatusAttribute(ReservationStatus $status)
    {
        $this->attributes['status'] = $status->value;
    }

    public function getStatusAttribute($value)
    {
        return ReservationStatus::from($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class, 'reservation_seat');
    }
}
