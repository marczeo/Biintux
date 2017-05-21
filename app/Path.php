<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paths';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /**
     * Get the route that owns the path.
     */
    public function route()
    {
        return $this->belongsTo('App\Route');
    }

    /*
     * Get the rel_route records associated with the path.
    */
    public function rel_route(){
      return $this->hasMany('App\Rel_route','path_id');
    }
}
