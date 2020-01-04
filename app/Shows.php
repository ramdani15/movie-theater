<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Shows extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'shows';

    protected $fillable = [
    	'date', 'start_time', 'end_time', 'movie_id', 'hall_id', 'created_by',
    ];
}
