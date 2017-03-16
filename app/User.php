<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 
    ];

    public function setNamesAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    /*
     * Get the role records associated with the user.
    */
    public function role(){
        return $this->belongsTo('App\Role');
    }
    /*
     * Get the device locations records associated with the user.
    */
    public function device_locations(){
        return $this->hasMany('App\Device_location');
    }
    /*
     * Get the rel_concessionaire records associated with the user(concessionaire).
    */
    public function rel_concessionaire(){
        return $this->hasOne('App\Rel_concessionaire','concessionaire_id');
    }

    /**
     * Verify that the role of the user is admin
     * @return boolean
    */
    public function isAdmin()
    {
       return $this->role->description == 'Administrator';
    }

    /**
     * Verify that the role of the user is concessionaire
     * @return boolean
    */
    public function isConcessionaire()
    {
       return $this->role->description == 'Administrator' || $this->role->description == 'Concessionaire';
    }

    /**
     * Verify that the role of the user is admin
     * @return boolean
    */
    public function isDriver()
    {
       return $this->role->description == 'Concessionaire' || $this->role->description == 'Driver';
    }

    /**
     * Get color related to the user
     * @return strin
    */
    public function getColor()
    {
       if($this->isAdmin()){
            return "hsla(121, 25%, 50%, 1)";
       }
        elseif($this->isConcessionaire()){
            return "hsla(178, 34%, 56%, 1)";
        }
        elseif($this->isDriver()){
            return "hsla(216, 34%, 56%, 1)";
        }
        else{
            return "hsla(124, 3%, 81%, 1)";
        }
    }

    /*
     * Get the drivers records associated with the user (Concessionaire).
    */
    public function drivers(){
        return $this->hasMany('App\Driver','concessionaire_id');
    }
    /*
     * Get the driver records associated with the user (driver).
    */
    public function driver(){
        return $this->hasOne('App\Driver');
    }
    /*
     * Get the buses records associated with the user (Concessionaire).
    */
    public function buses(){
        return $this->hasMany('App\Bus','concessionaire_id');
    }
}
