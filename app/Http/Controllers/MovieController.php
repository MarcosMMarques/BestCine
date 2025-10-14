<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdb = $tmdbService;
    }

    public function index()
    {
        $movies = $this->tmdb->getNowShowingMoviesInBrazil(1)['results'];
        // dd($movies);
        return view('movies.index', ['movies' => $movies]);
    }

    public function show(int $movieId)
    {
        $movie = $this->tmdb->getMovieDetails($movieId, ['videos', 'credits']);

        if (empty($movie) || (isset($movie['success']) && $movie['success'] === false)) {
            abort(404);
        }

        $formatted = $this->tmdb->formatMovieDetails($movie);

        return view('movies.show', array_merge(['movie' => $movie], $formatted));
    }
}
