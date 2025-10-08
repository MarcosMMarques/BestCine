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
        $movies = $this->tmdb->getNowShowingMoviesInBrazil(1)['results'];
        // dd($movies);
        return view('movies.index', ['movies' => $movies]);
    }
}
