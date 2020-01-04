<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Tickets extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'tickets';

    protected $fillable = [
    	'show_id', 'seat_id', 'customer_id', 'created_by',
    ];
}
