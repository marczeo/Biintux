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

    /*
     * Get the rel_concessionaire records associated with the route.
    */
    public function rel_concessionaire(){
        return $this->hasOne('App\Rel_concessionaire');
    }
}
