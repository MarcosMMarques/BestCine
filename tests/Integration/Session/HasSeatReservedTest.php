<?php

use App\Models\Session;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Movie;
use App\Models\Room;
use App\Enums\ReservationStatus;

it('returns true when seat is reserved in a valid reservation', function () {
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);

    $session = Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
    ]);

    $reservation = Reservation::factory()->create([
        'session_id' => $session->id,
        'status'     => ReservationStatus::RESERVED,
    ]);

    $reservation->seats()->attach($seat);

    expect($session->hasSeatReserved($seat))->toBeTrue();
});

it('returns false when seat is not attached to any reservation', function () {
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);

    $session = Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
    ]);

    Reservation::factory()->create([
        'session_id' => $session->id,
        'status'     => ReservationStatus::RESERVED,
    ]);

    expect($session->hasSeatReserved($seat))->toBeFalse();
});

it('returns false when reservation status is not reserved', function () {
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);

    $session = Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
    ]);

    $reservation = Reservation::factory()->create([
        'session_id' => $session->id,
        'status'     => ReservationStatus::CANCELED,
    ]);

    $reservation->seats()->attach($seat);

    expect($session->hasSeatReserved($seat))->toBeFalse();
});
