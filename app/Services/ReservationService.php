<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Seat;
use App\Models\Session;
use Illuminate\Support\Carbon;

class ReservationService
{
    public static function checkSeatReservationByMovieAndDateTime(Movie $movie, Carbon $dateTime, Seat $seat): bool
    {
        $session = Session::where('movie_id', $movie->id)
            ->where('datetime', $dateTime->format('Y-m-d H:i:s'))
            ->first();

        return $session ? $session->hasSeatReserved($seat) : false;
    }
}
