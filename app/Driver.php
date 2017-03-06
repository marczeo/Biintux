<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'driver';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /*
     * Get the concessionaire(user) records associated with the driver.
    */
    public function concessionaire(){
        return $this->belongsTo('App\User','id','concessionaire_id');
    }

    /*
     * Get the user records associated with the driver.
    */
    public function user(){
        return $this->belongsTo('App\User');
    }
}
