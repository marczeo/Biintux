<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Device_location;
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
       $user=null;
       $location= new Device_location;
        try {
             $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            
        }
        if($user)
        {
            $location->user_id=$user->id;
        }
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
        $locations =Device_location::orderBy('id','asc')->get();
        
        
        $locations_response=new Collection;
        foreach ($locations as $key=> $location)
        {
            $location_array=[];
            $location_array['id']=$location->id;
            $location_array['device_id']=$location->device_id;
            $location_array['user_id']=$location->user_id;
            $location_array['longitude']=$location->longitude;
            $location_array['latitude']=$location->latitude;
            if($location->user)
                $location_array['name']=$location->user->name;
            else
                $location_array['name']="";
            $locations_response->push($location_array);
        }
        $response['data']=$locations_response;
        return json_encode($response);
    }
}