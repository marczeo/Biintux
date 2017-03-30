<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Device;
use App\Device_location;
use App\Node;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
       $device->user_id=$request->user_id;
       $device->device_key=$request->device_key;
       $device->save();
        return $device->id;
    }
}