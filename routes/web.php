<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReservationController;

Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movieId}', [MovieController::class, 'show'])
    ->whereNumber('movieId')
    ->name('movies.show');

//Show movie sessions
Route::get('/movies/{movie}/sessions', [MovieController::class, 'sessions'])->name('movies.sessions');

//Get reserved seats for a movie session
Route::get('/movies/{movie}/reserved-seats', [ReservationController::class, 'getReservedSeats'])->name('reservation.reserved-seats');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Buy ticket
    Route::post('/reservation/checkout/{movie}', [ReservationController::class, 'checkout'])->name('reservation.checkout');
    Route::get('/reservation/success', [ReservationController::class, 'success'])->name('reservation.success');
    Route::get('/reservation/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');

    //Get user tickets
    Route::get('/reservation/tickets/{user}', [ReservationController::class, 'getUserTickets'])
        ->name('reservation.user-tickets');
});


require __DIR__.'/auth.php';
