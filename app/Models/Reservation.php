<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ReservationStatus;

class Reservation extends Model
{
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
}
