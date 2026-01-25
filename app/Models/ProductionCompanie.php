<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionCompanie extends Model
{
    use HasFactory;

    protected $table = 'production_companie';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_production_companie', 'production_companie_id', 'movie_id');
    }
}
