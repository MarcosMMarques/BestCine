<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movieId}', [MovieController::class, 'show'])
    ->whereNumber('movieId')
    ->name('movies.show');
