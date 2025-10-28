<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Session;

class Movie extends Model
{
    protected $table = 'movie';

    protected $fillable = [
        'tmdb_id',
        'title',
        'synopsis',
        'length',
        'image',
        'backdrop_url',
        'poster_url',
        'trailer_url',
        'tagline',
        'release_date',
    ];

    protected function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    protected function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id');
    }

    protected function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'actor_movie', 'movie_id', 'actor_id');
    }

    protected function productionCompanies(): BelongsToMany
    {
        return $this->belongsToMany(ProductionCompanie::class, 'movie_production_companie', 'movie_id', 'production_companie_id');
    }
}
