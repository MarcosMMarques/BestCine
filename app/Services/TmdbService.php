<?php

namespace App\Services;

use Carbon\Carbon;
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

    public function formatNowShowingMoviesInBrazil(array $movies): array
    {
        $movies = collect($movies)->take(8)->all();
        return array_map(function ($movie) {
            $posterPath = $movie['poster_path'] ?? null;
            $posterUrl = $posterPath ? 'https://image.tmdb.org/t/p/w500' . $posterPath :
                'https://via.placeholder.com/500x750?text=Sem+Imagem';
            $releaseDate = $movie['release_date'] ?? null;
            $formattedDate = $releaseDate ? Carbon::parse($releaseDate)->format('d/m/Y') : null;

            return array_merge($movie, [
                'posterUrl' => $posterUrl,
                'formattedDate' => $formattedDate,
            ]);
        }, $movies);
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

    public static function getBackdropUrlFromTmdbData($data)
    {
        $backdropPath = data_get($data, 'backdrop_path');
        return $backdropPath
            ? 'https://image.tmdb.org/t/p/original' . $backdropPath
            : null;
    }

    public static function getPosterUrlFromTmdbData($data)
    {
        $posterPath = data_get($data, 'poster_path');
        return $posterPath
            ? 'https://image.tmdb.org/t/p/w500' . $posterPath
            : null;
    }

    public static function getTrailerUrlFromTmdbData($data)
    {
        $videos = data_get($data, 'videos.results', []);

        $trailer = collect($videos)->first(function ($video) {
            return ($video['type'] ?? null) === 'Trailer'
                && ($video['site'] ?? null) === 'YouTube'
                && filled($video['key'] ?? null);
        });

        return $trailer ? 'https://www.youtube.com/watch?v=' . $trailer['key'] : null;
    }

    public static function getMovieReleaseDateFromTmdbData($data)
    {
        $releaseDate = data_get($data, 'release_date');
        return $date ? date('Y-m-d', strtotime($date)) : null;
    }

    public static function getMovieGenresFromTmdbData($data)
    {
        return collect(data_get($data, 'genres', []))
            ->pluck('name')
            ->values()
            ->all();
    }

    public static function getMovieProductionCompaniesFromTmdbData($data)
    {
        return collect(data_get($data, 'production_companies', []))
            ->pluck('name')
            ->values()
            ->all();
    }

    public static function getMovieCastFromTmdbData($data)
    {
        return collect(data_get($data, 'credits.cast', []))
            ->all();
    }
}
