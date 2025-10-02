<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('TMDB_API_KEY');
    }

    public function getPopularMovies()
    {
        $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR',
            'page' => 1
        ]);

        return $response->json();
    }

    public function getMovieDetails($movieId)
    {
        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}", [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR',
        ]);

        return $response->json();
    }

    public function searchMovies($query)
    {
        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => $this->apiKey,
            'query' => $query,
            'language' => 'pt-BR',
        ]);

        return $response->json();
    }
}
