<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nodes';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;
}
