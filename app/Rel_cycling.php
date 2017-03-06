<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rel_cycling extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rel_cycling';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    /*
     * Get the bikeway records associated with the rel cycling.
    */
    public function bikeway(){
        return $this->belongsTo('App\Ciclovia');
    }
    /*
     * Get the start node records associated with the rel cycling.
    */
    public function start_node(){
        return $this->belongsTo('App\Node','start_node_id');
    }
}
