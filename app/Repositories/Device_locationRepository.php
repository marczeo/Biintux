<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Device_location;
use App\Device;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class Device_locationRepository
{
    /**
     * Create location
     * @param Request $request
     * @return json
     */
    public function addLocation(Request $request)
    {
       $location= new Device_location;
        $location->longitude=$request->longitude;
        $location->latitude=$request->latitude;
        if($request->device_id)
        {
            $location->device_id=$request->device_id;
        }
        $location->save();
        return true;
    }

    /**
     * Get all of the device location
     *
     * @param  null
     * @return json
     */
     public function getAllDevice_location()
    {
        $currentUser=Auth::user();
        $locations_response=new Collection;
        if($currentUser->isAdmin())
        {
            $diferent_devices=Device::whereNotNull('user_id')->distinct('user_id')->get();
            
            foreach ($diferent_devices as $key=> $device)
            {
                $location=$device->device_locations->last();
                if($location)
                {
                    $location_array=[];
                    $location_array['id']=$location->id;
                    $location_array['device_id']=$location->device_id;
                    $location_array['longitude']=$location->longitude;
                    $location_array['latitude']=$location->latitude;
                    if($location->device)
                        $location_array['name']=$location->device->user->name;
                    else
                        $location_array['name']="";
                    $locations_response->push($location_array);
                }
            }

        }
        elseif ($currentUser->isConcessionaire()) {
            foreach ($currentUser->drivers as $driver) {
                $location=$driver->user->device->device_locations->last();
                if($location)
                {
                    $location_array=[];
                    $location_array['id']=$location->id;
                    $location_array['device_id']=$location->device_id;
                    $location_array['longitude']=$location->longitude;
                    $location_array['latitude']=$location->latitude;
                    if($location->device)
                        $location_array['name']=$location->device->user->name;
                    else
                        $location_array['name']="";
                    $locations_response->push($location_array);
                }
            }
            
        }
        $response['data']=$locations_response;
        return json_encode($response);
    }
}