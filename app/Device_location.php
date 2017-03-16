<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device_location extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_location';
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
}
