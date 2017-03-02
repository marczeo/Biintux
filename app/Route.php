<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'routes';
    
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;
}
