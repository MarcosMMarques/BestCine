<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Seat;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Session;
use Illuminate\Support\Carbon;
use app\Enums\ReservationStatus;
use App\Exceptions\SeatAlreadyReservedException;
use App\Exceptions\UserAlreadyHasReservationException;

class ReservationService
{
    public function checkSeatReservationByMovieAndDateTime(Movie $movie, Carbon $dateTime, Seat $seat): bool
    {
        $session = Session::where('movie_id', $movie->id)
            ->where('datetime', $dateTime->format('Y-m-d H:i:s'))
            ->first();

        return $session ? $session->hasSeatReserved($seat) : false;
    }

    public function checkIfUserHasReservationForSession(User $user, Movie $movie, Carbon $dateTime): bool
    {
        return Session::where('movie_id', $movie->id)
            ->where('datetime', $dateTime->format('Y-m-d H:i:s'))
            ->whereHas('reservations', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
    }

    public function validateReservation(
        User $user,
        Movie $movie,
        Carbon $dateTime,
        Seat $seat
    ): void {
        if ($this->checkSeatReservationByMovieAndDateTime($movie, $dateTime, $seat)) {
            throw new SeatAlreadyReservedException();
        }

        if ($this->checkIfUserHasReservationForSession($user, $movie, $dateTime)) {
            throw new UserAlreadyHasReservationException();
        }
    }

    public function createReservation(Movie $movie, Carbon $dateTime, Seat $seat, User $user): void
    {
        try {
            DB::beginTransaction();
            $session = Session::firstOrCreate([
                'movie_id' => $movie->id,
                'datetime' => $dateTime->format('Y-m-d H:i:s')
            ]);

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'session_id' => $session->id,
                'status' => ReservationStatus::RESERVED,
            ]);

            $reservation->seats()->attach($seat->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
