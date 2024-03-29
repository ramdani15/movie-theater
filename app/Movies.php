<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Movies extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'movies';

    protected $fillable = [
    	'title', 'genre', 'year',
    ];
}
