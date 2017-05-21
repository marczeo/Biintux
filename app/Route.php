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

    public function setColor()
    {
       $matiz= mt_rand(175, 300);
       $saturacion = mt_rand(85,100);
       $luminosidad = mt_rand(30,60);
       $this->color="hsla(".$matiz.",".$saturacion."%,".$luminosidad."%,1)";
    }

    /*
     * Get the rel_concessionaire records associated with the route.
    */
    public function rel_concessionaire(){
        return $this->hasMany('App\Rel_concessionaire');
    }


    /*
     * Get the paths records associated with the route.
    */
    public function paths(){
      return $this->hasMany('App\Path');
    }
}
