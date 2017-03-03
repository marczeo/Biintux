<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Bus;
use Illuminate\Support\Facades\Auth;
class BusRepository
{

    public function createBus(Request $request)
    {
        $currentUser = Auth::user();
        $bus = new Bus;
        $bus->economic_number = $request->economic_number;
        $bus->route_id = $currentUser->rel_concessionaire()->route_id;
        $bus->passenger_capacity = $request->passenger_capacity;
        $bus->save();

        return true;
    }
    public function getAllBuses()
    {
        $buses= Bus::orderBy('id','asc')->select('id','economic_number','passenger_capacity')->get();
        $buses_response=[];
        foreach ($buses as $key=> $bus)
        {

            $buses_response[$key]['id']=$bus->id;
            $buses_response[$key]['economic_number']=$bus->economic_number;
            $buses_response[$key]['passenger_capacity']=$bus->passenger_capacity;
        }
        return json_encode($buses_response);
    }
}