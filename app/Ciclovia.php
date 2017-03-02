<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciclovia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cycling_routes';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;

    public function setColor()
    {
       /*$red 	= 255;
       $green 	= rand(100,255);
       $blue 	= rand(0,100);
       $this->color="rgb(".$red.",".$green.",".$blue.")";*/
       $matiz= mt_rand(0, 1) ? mt_rand(0, 25) : mt_rand(325, 360);
       $saturacion = mt_rand(85,100);
       $luminosidad = mt_rand(30,60);
       $this->color="hsla(".$matiz.",".$saturacion."%,".$luminosidad."%,1)";
    }
}
