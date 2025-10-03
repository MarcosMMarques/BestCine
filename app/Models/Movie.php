<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany
use App\Models\Session;

class Movie extends Model
{
    protected $table = 'movie';

    protected $fillable = [
        'title',
        'synopsis',
        'length',
        'image',
    ];

    protected $hidden = [
        'image',
    ];

    protected function sessions()
    {
        return $this->hasMany(Sessiom::class);
    }
}
