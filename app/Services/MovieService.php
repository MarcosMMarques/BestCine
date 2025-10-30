<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\ProductionCompanie;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\TmdbService;

class MovieService
{
    public static function createMovieFromTmdbData($data): ?Movie
    {
        $backdropUrl = TmdbService::getBackdropUrlFromTmdbData($data);


        $posterPath = data_get($data, 'poster_path');
        $posterUrl = $posterPath
            ? 'https://image.tmdb.org/t/p/w500' . $posterPath
            : null;

        $videos = data_get($data, 'videos.results', []);

        $trailer = Arr::first($videos, function ($video) {
            return ($video['type'] ?? null) === 'Trailer'
                && ($video['site'] ?? null) === 'YouTube'
                && filled($video['key'] ?? null);
        });

        $trailerUrl = $trailer ? 'https://www.youtube.com/watch?v=' . $trailer['key'] : null;

        $length = data_get($data, 'runtime');

        $date = data_get($data, 'release_date');


        $release_date = $date ? date('Y-m-d', strtotime($date)) : null;

        $synopsis = data_get($data, 'overview');

        $genres = collect(data_get($data, 'genres', []))
            ->pluck('name')
            ->values()
            ->all();

        $production_companies = collect(data_get($data, 'production_companies', []))
            ->pluck('name')
            ->values()
            ->all();

        $cast = collect(data_get($data, 'credits.cast', []))
            ->all();

        try {
            DB::beginTransaction();

            $createdGenres = [];
            foreach ($genres as $genre) {
                $getGenre = Genre::where(['name' => $genre])->first();
                if (is_null($getGenre)) {
                    $createdGenres[] = Genre::create(['name' => $genre]);
                    continue;
                }
                $createdGenres[] = $getGenre;
            }


            $createdCompanies = [];
            foreach ($production_companies as $pc) {
                $getProductionCompanie = ProductionCompanie::where(['name' => $pc])->first();
                if (is_null($getProductionCompanie)) {
                    $createdCompanies[] = ProductionCompanie::create(['name' => $pc]);
                    continue;
                }
                $createdCompanies[] = $getProductionCompanie;
            }


            $createdActors = [];
            $cast = collect($cast)->take(15);
            foreach ($cast as $actor) {
                $getActor = Actor::where(['id' => $actor['id']])->first();
                if (is_null($getActor)) {
                    $createdActors[] = Actor::create([
                        'id' => $actor['id'],
                        'name' => $actor['name'],
                        'profile_path' => $actor['profile_path'],
                    ]);
                    continue;
                }
                $createdActors[] = $getActor;
            }

            $createdMovie = Movie::create([
                'tmdb_id' => data_get($data, 'id'),
                'title' => data_get($data, 'title'),
                'synopsis' => $synopsis,
                'length' => $length,
                'image' => data_get($data, 'poster_path'),
                'backdrop_url' => $backdropUrl,
                'poster_url' => $posterUrl,
                'trailer_url' => $trailerUrl,
                'tagline' => data_get($data, 'tagline'),
                'release_date' => $release_date,
            ]);

            $cast = $cast->pluck('id')->values()->all();
            $createdMovie->actors()->attach($cast);

            $createdMovie->genres()->attach(Arr::pluck($createdGenres, 'id'));

            $createdMovie->productionCompanies()->attach(Arr::pluck($createdCompanies, 'id'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $createdMovie = null;
        }

        return $createdMovie;
    }
}
