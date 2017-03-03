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
}