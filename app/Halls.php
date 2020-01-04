<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Halls extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'halls';

    protected $fillable = [
    	'name', 'seat_amount',
    ];
}
