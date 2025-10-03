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

    public function isAvailable()
    {
        return $this->status == SeatStatus::AVAILABLE;
    }

    public function isReserved()
    {
        return $this->status == SeatStatus::RESERVED;
    }

    public function setStatusAttribute(SeatStatus $status)
    {
        $this->attributes['status'] = $status->value;
    }

    public function getStatusAttribute($value)
    {
        return SeatStatus::from($value);
    }
}
