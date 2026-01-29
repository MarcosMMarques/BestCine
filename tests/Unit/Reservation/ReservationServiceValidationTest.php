<?php

use App\Services\ReservationService;
use App\Exceptions\SeatAlreadyReservedException;
use App\Exceptions\UserAlreadyHasReservationException;
use App\Models\User;
use App\Models\Movie;
use App\Models\Seat;
use Illuminate\Support\Carbon;

it('throws SeatAlreadyReservedException if seat is already reserved', function () {
    $service = Mockery::mock(ReservationService::class)->makePartial();

    $service
        ->shouldReceive('checkSeatReservationByMovieAndDateTime')
        ->once()
        ->andReturn(true);

    $service
        ->shouldReceive('checkIfUserHasReservationForSession')
        ->never();

    expect(
        fn () =>
        $service->validateReservation(
            Mockery::mock(User::class),
            Mockery::mock(Movie::class),
            Carbon::now(),
            Mockery::mock(Seat::class)
        )
    )->toThrow(SeatAlreadyReservedException::class);
});

it('throws UserAlreadyHasReservationException if user already has reservation', function () {
    $service = Mockery::mock(ReservationService::class)->makePartial();

    $service
        ->shouldReceive('checkSeatReservationByMovieAndDateTime')
        ->once()
        ->andReturn(false);

    $service
        ->shouldReceive('checkIfUserHasReservationForSession')
        ->once()
        ->andReturn(true);

    expect(
        fn () =>
        $service->validateReservation(
            Mockery::mock(User::class),
            Mockery::mock(Movie::class),
            Carbon::now(),
            Mockery::mock(Seat::class)
        )
    )->toThrow(UserAlreadyHasReservationException::class);
});
