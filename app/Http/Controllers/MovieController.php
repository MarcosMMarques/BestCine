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

        $trailer = collect(data_get($movie, 'videos.results', []))
            ->first(function ($video) {
                return ($video['type'] ?? null) === 'Trailer'
                    && ($video['site'] ?? null) === 'YouTube'
                    && filled($video['key'] ?? null);
            });

        $cast = collect(data_get($movie, 'credits.cast', []))
            ->filter(function ($actor) {
                return filled($actor['name'] ?? null) || filled($actor['character'] ?? null);
            })
            ->take(10);

        return view('movies.show', [
            'movie' => $movie,
            'trailer' => $trailer,
            'cast' => $cast,
        ]);
    }
}
