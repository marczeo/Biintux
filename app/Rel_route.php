<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rel_route extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rel_route';

    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /*
     * Get the route records associated with the rel route.
    */
    public function route(){
        return $this->belongsTo('App\Route');
    }
    /*
     * Get the start node records associated with the rel route.
    */
    public function start_node(){
        return $this->belongsTo('App\Node','start_node_id');
    }
}
