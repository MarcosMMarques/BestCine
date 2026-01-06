<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Services\MovieService;
use App\Models\Movie;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdb = $tmdbService;
    }

    public function index()
    {
        try {
            $moviesData = $this->tmdb->getNowShowingMoviesInBrazil(1);
        } catch (\Throwable $e) {
            report($e);
            return abort('500', 'Failed to fetch now showing movies.');
        }

        $movies = $this->tmdb->formatNowShowingMoviesInBrazil($moviesData['results'] ?? []);

        return view('movies.index', ['movies' => $movies]);
    }

    public function show(int $movieId)
    {
        $movie = Movie::where('tmdb_id', $movieId)->first();
        if (is_null($movie)) {
            try {
                $tmdbData = $this->tmdb->getMovieDetails($movieId, ['videos', 'credits']);
            } catch (RequestException $e) {
                report($e);
                abort(500, 'Failed to fetch movie details from TMDB.');
            } catch (ValidationException $e) {
                report($e);
                abort(500, 'Invalid data received from TMDB.');
            }

            if (empty($tmdbData) || (isset($tmdbData['success']) && $tmdbData['success'] === false)) {
                abort(404);
            }

            $movie = MovieService::createMovieFromTmdbData($tmdbData);

            if (is_null($movie)) {
                return back()->withErrors(['error' => 'Failed to create movie from TMDB data.']);
            }
        }

        return view('movies.show', compact('movie'));
    }

    public function sessions(Movie $movie)
    {
        return view('movies.sessions', compact('movie'));
    }
}
