<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Bus;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
class BusRepository
{
    /**
     * Get all of the buses  for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    public function getAllBusesForUser(User $user)
    {
        return $user->buses()
                    ->orderBy('economic_number', 'asc')
                    ->get();
    }

    public function createBus(Request $request)
    {
        $currentUser = Auth::user();
        $bus = new Bus;
        $bus->economic_number = $request->economic_number;
        $bus->passenger_capacity = $request->passenger_capacity;
        if($currentUser->isAdmin())
            $bus->concessionaire_id = $request->concessionaire_id;
        else
            $bus->concessionaire_id = $currentUser->id;
        $bus->save();

        return true;
    }
    public function getAllBuses()
    {
        $buses=new Collection;
        $currentUser=Auth::user();
        $buses_response=[];
        if($currentUser->isAdmin()){
            $buses= Bus::orderBy('id','asc')->get();
            
            foreach ($buses as $key=> $bus)
            {

                $buses_response[$key]['id']=$bus->id;
                $buses_response[$key]['economic_number']=$bus->economic_number;
                $buses_response[$key]['passenger_capacity']=$bus->passenger_capacity;
                $buses_response[$key]['route']=$bus->concessionaire->rel_concessionaire->route->code;
                $buses_response[$key]['concessionaire']=$bus->concessionaire->name;
            }
        }
        elseif ($currentUser->isConcessionaire()) {
            $buses=$currentUser->buses;
            foreach ($buses as $key=> $bus)
            {

                $buses_response[$key]['id']=$bus->id;
                $buses_response[$key]['economic_number']=$bus->economic_number;
                $buses_response[$key]['passenger_capacity']=$bus->passenger_capacity;
            }
            $response['encodepath']=$currentUser->rel_concessionaire->route->paths;
        }
        $response['data']=$buses_response;
        return json_encode($response);
    }

    /**
     * Change status, enabled or disabled a bus
     * @param Bus $bus
     * @param String status
     * @return json
    */
    public function changeStatus(Bus $bus, $status)
    {
        try {
            $bus->enabled=$status;
            $bus->save();
            return response()->json(['code'=>200, 'response'=>'Status changed successfully'],200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['code'=>400 ,'response'=>'An error has occurred'],400);
        }
        
    }
}