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

    public function getNowShowingMoviesInBrazil(int $page)
    {
        $response = Http::get(
            'https://api.themoviedb.org/3/movie/now_playing',
            [
                'api_key' => $this->apiKey,
                'language' => 'pt-BR',
                'page' => $page,
                'region' => 'BR',
                'sort_by' => 'popularity.desc',
                'with_release_type' => '2|3',
                'release_date.lte' => now()->toDateString(),
                'release_date.gte' => now()->subMonths(2)->toDateString(),
            ],
        );

        return $response->json();
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

    public function getMovieDetails($movieId, array $append = [])
    {
        $query = [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR',
        ];

        if (!empty($append)) {
            $query['append_to_response'] = implode(',', $append);
        }

        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}", $query);

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
