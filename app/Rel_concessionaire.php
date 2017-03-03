<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rel_concessionaire extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rel_concessionaire';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /*
     * Get the users records associated with the rel concessionaire.
    */
    public function user(){
        return $this->belongsTo('App\User','concessionaire_id');
    }
    /*
     * Get the route records associated with the rel concessionaire.
    */
    public function route(){
        return $this->belongsTo('App\Route');
    }
}
