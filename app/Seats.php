<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Seats extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'seats';

    protected $fillable = [
    	'code', 'hall_id',
    ];
}
