<?php

use App\Models\User;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Room;
use App\Models\Session;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Enums\ReservationStatus;
use App\Exceptions\SeatAlreadyReservedException;
use App\Exceptions\UserAlreadyHasReservationException;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->service = new ReservationService();
});

/*
*   validateReservation tests
*/

it('returns true if seat is reserved for a given movie and datetime', function () {
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);
    $date  = Carbon::parse('2023-10-10 20:00:00');

    $session = Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
        'datetime' => $date->format('Y-m-d H:i:s'),
    ]);

    $reservation = Reservation::factory()->create([
        'session_id' => $session->id,
        'status'     => ReservationStatus::RESERVED,
    ]);

    $reservation->seats()->attach($seat);

    expect(
        $this->service->checkSeatReservationByMovieAndDateTime($movie, $date, $seat)
    )->toBeTrue();
});
