<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
