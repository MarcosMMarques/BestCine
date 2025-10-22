<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $table = 'genre';

    protected $primaryKey = 'name';

    protected $keyType = 'string';

    protected $incrementing = false;

    protected $fillable = [
        'name',
    ];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'genre_movie', 'genre_name', 'movie_id');
    }
}
