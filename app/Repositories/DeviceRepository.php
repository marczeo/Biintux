<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Device;
use App\Device_location;
use App\Node;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class DeviceRepository
{

    /**
     * Create Device
     * @param Request $request
     * @return json
     */
    public function addDevice(Request $request)
    {
       $device=new Device;
       $user=null;
       try {
             $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            
        }
        if($user)
        {
            $location->user_id=$user->id;
        }
        if($request->device_key)
            $device->device_key=$request->device_key;
       $device->save();
        return $device->id;
    }
}