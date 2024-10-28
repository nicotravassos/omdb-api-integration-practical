<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendingMovie extends Model
{
    protected $table = 'trending_movies';

    protected $fillable = [
        'imdb_id',
        'title',
        'search_count',
    ];
    
    protected $casts = [
        'search_count' => 'integer',
    ];
}
