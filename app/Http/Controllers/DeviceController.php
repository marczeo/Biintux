<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Device_location;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use JWTAuth;
use App\Repositories\DeviceRepository;
use Tymon\JWTAuth\Exceptions\JWTException;

class DeviceController extends Controller
{
    /**
     * The usuario repository instance.
     *
     * @var usuarioRepository
     */
    protected $DeviceDAO;

    /**
     * Create a new controller instance.
     *
     * @param  Device_locationRepositiry  $device
     * @return void
     */
    public function __construct(DeviceRepository $device)
    {
        //Use DAO
        $this->DeviceDAO = $device;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->DeviceDAO->addDevice($request);
        return json_encode(["device_id"=>$result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Validate device
     *
     * 
     * @return json
     */
    public function validate_device(Request $request)
    {
        $result = $this->DeviceDAO->exist($request['device_id']);
        return json_encode(["exist"=>$result]);
    }
}
