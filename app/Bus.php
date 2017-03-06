<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'route_car';
    
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /*
     * Get the user concessionaire records associated with the bus.
    */
    public function concessionaire(){
        return $this->belongsTo('App\User','concessionaire_id');
    }
}
