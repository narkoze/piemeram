<?php

namespace Piemeram;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'name',
        'genres',
        'year',
        'rating',
        'votes',
        'imdb',
    ];
}
