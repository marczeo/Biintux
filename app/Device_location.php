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
    public $timestamps = true;

    /*
     * Get the device record associated with the Device location.
    */
    public function device(){
        return $this->belongsTo('App\Device','device_id','id');
    }
}
