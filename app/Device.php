<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'devices';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /*
     * Get the user record associated with the Device location.
    */
    public function user(){
        return $this->belongsTo('App\User');
    }

    /*
     * Get the device locations records associated with the user.
    */
    public function device_locations(){
        return $this->hasMany('App\Device_location');
    }
}
