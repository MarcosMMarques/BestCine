<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        /* $movies = $this->tmdb->getPopularMovies(); */
        /* $movieId = $movies["results"][0]["id"]; */
        /* dd($this->tmdb->getMovieDetails($movieId)); */
        dd($this->tmdb->getNowShowingMoviesInBrazil(2));
    }
}
