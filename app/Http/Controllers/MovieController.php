<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Services\MovieService;
use App\Models\Movie;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdb = $tmdbService;
    }

    public function index()
    {
        $moviesData = $this->tmdb->getNowShowingMoviesInBrazil(1);
        $movies = $this->tmdb->formatNowShowingMoviesInBrazil($moviesData['results'] ?? []);

        return view('movies.index', ['movies' => $movies]);
    }

    public function show(int $movieId)
    {
        $movie = Movie::where('tmdb_id', $movieId)->first();
        if (is_null($movie)) {
            $tmdbData = $this->tmdb->getMovieDetails($movieId, ['videos', 'credits']);

            if (empty($tmdbData) || (isset($tmdbData['success']) && $tmdbData['success'] === false)) {
                abort(404);
            }

            $movie = MovieService::createMovieFromTmdbData($tmdbData);

            if (is_null($movie)) {
                abort(500, 'Failed to create movie from TMDB data.');
            }
        }

        return view('movies.show', compact('movie'));
    }

    public function sessions(Movie $movie)
    {
        return view('movies.sessions', compact('movie'));
    }
}
