<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciclovia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cycling_routes';
    public $timestamps = false;
}
