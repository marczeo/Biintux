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
        if($currentUser->isAdmin()){
            $buses= Bus::orderBy('id','asc')->get();
            $buses_response=[];
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
            $buses_response=[];
            foreach ($buses as $key=> $bus)
            {

                $buses_response[$key]['id']=$bus->id;
                $buses_response[$key]['economic_number']=$bus->economic_number;
                $buses_response[$key]['passenger_capacity']=$bus->passenger_capacity;
            }
        }
        $response['data']=$buses_response;
        return json_encode($response);
    }
}