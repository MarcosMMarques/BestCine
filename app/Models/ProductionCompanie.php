<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCompanie extends Model
{
    protected $table = 'production_companie';

    protected $incrementing = false;

    protected $fillable = [
        'name',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_production_companie', 'production_companie_name', 'movie_id');
    }
}
