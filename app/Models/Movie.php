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

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_movie', 'movie_id', 'genre_id');
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'actor_movie', 'movie_id', 'actor_id');
    }

    public function productionCompanies(): BelongsToMany
    {
        return $this->belongsToMany(ProductionCompanie::class, 'movie_production_companie', 'movie_id', 'production_companie_id');
    }
}
