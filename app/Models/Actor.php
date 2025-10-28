<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actor extends Model
{
    protected $table = 'actor';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'profile_path',
    ];


    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'actor_movie', 'actor_id', 'movie_id');
    }
}
