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

it('returns false if seat is not reserved for the session', function () {
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);
    $date  = Carbon::parse('2023-10-10 20:00:00');

    Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
        'datetime' => $date->format('Y-m-d H:i:s'),
    ]);

    expect(
        $this->service->checkSeatReservationByMovieAndDateTime($movie, $date, $seat)
    )->toBeFalse();
});

it('returns true if user already has a reservation for the session', function () {
    $user  = User::factory()->create();
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $date  = Carbon::parse('2023-10-10 20:00:00');

    $session = Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
        'datetime' => $date->format('Y-m-d H:i:s'),
    ]);

    Reservation::factory()->create([
        'user_id'    => $user->id,
        'session_id' => $session->id,
        'status'     => ReservationStatus::RESERVED,
    ]);

    expect(
        $this->service->checkIfUserHasReservationForSession($user, $movie, $date)
    )->toBeTrue();
});

it('throws exception if seat is already reserved', function () {
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);
    $user  = User::factory()->create();
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
        fn () =>
        $this->service->validateReservation($user, $movie, $date, $seat)
    )->toThrow(SeatAlreadyReservedException::class);
});

it('throws exception if user already has a reservation for the session', function () {
    $user  = User::factory()->create();
    $movie = Movie::factory()->create();
    $room  = Room::factory()->create();
    $seat  = Seat::factory()->create(['room_id' => $room->id]);
    $date  = Carbon::parse('2023-10-10 20:00:00');

    $session = Session::factory()->create([
        'movie_id' => $movie->id,
        'room_id'  => $room->id,
        'datetime' => $date->format('Y-m-d H:i:s'),
    ]);

    Reservation::factory()->create([
        'user_id'    => $user->id,
        'session_id' => $session->id,
        'status'     => ReservationStatus::RESERVED,
    ]);

    expect(
        fn () =>
        $this->service->validateReservation($user, $movie, $date, $seat)
    )->toThrow(UserAlreadyHasReservationException::class);
});

it('creates session, reservation and attaches seat', function () {
    $user  = User::factory()->create();
    $movie = Movie::factory()->create();
    $seat  = Seat::factory()->create();
    $date  = Carbon::parse('2023-10-10 20:00:00');

    $this->service->createReservation($movie, $date, $seat, $user);

    expect(Session::where([
        'movie_id' => $movie->id,
        'datetime' => $date->format('Y-m-d H:i:s'),
    ])->exists())->toBeTrue();

    $session = Session::where('movie_id', $movie->id)->first();

    expect(Reservation::where([
        'user_id'    => $user->id,
        'session_id' => $session->id,
        'status'     => ReservationStatus::RESERVED,
    ])->exists())->toBeTrue();

    expect($session->hasSeatReserved($seat))->toBeTrue();
});
