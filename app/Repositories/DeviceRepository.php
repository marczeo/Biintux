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
     * @return int
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
            $device->user_id=$user->id;
        }
        if($request->device_key)
            $device->device_key=$request->device_key;
       $device->save();
        return $device->id;
    }

    /**
     * Asignar usuario al dispositivo
     * @param string $device_id
     * @return json
    */
    public function assignUserDevice($device_id)
    {
        $device=Device::find($device_id);
        if($device)
        {
            $user=null;
            try {
               $user = JWTAuth::parseToken()->authenticate();
           } catch (JWTException $e) {

           }
           if($user)
           {
                $device->user_id=$user->id;
           }
           $device->save();
           return response()->json(['code'=>200, 'response'=>'User assigned to device successfully'],200);
        }
        else
        {
            return response()->json(['code'=>400 ,'response'=>'An error has occurred'],400);
        }
    }

    /**
     * Validar que la existencia de un dispositivo
     * @param string $device_id
     * @return boolean
     */
    public function exist($device_id)
    {
        if(Device::find($device_id))
            return true;
        return false;
    }
}