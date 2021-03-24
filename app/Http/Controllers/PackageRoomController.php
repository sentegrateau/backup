<?php

namespace App\Http\Controllers;
use App\Models\Device;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Models\Package_Room;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PackageRoomController extends Controller
{
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
        try {
            $rules = [
                'device_id' => 'required',
                'package_id' => 'required',
                'room_id' => 'required',
                'max_qty' => 'required',
                'min_qty' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->all(),
                    'data' => null,
                ], 422);
            }
            $record_exists = Package_Room::where(function ($query) use ($request) {
                $query->where('package_id', $request['package_id'])
                    ->where('room_id', $request['room_id'])
                    ->where('device_id', $request['device_id']);
            })->orWhere(function ($q) use ($request){
                $q->where('room_id', $request['room_id'])->where('device_id', $request['device_id']);
            })->exists();
            if($record_exists){
                return response()->json([
                   'error' => true,
                   'message' => 'Record Already Exists',
                   'data' => null
                ], 422);
            }
             $package_room = new Package_room($request->all());

             $package_room->save();

            return response()->json(
            [
                'error' => false,
                'message' => [$package_room->name . ' created successfully'],
                'data' => null,
            ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
                ], 400);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package_Room  $package_Room
     * @return \Illuminate\Http\Response
     */
    public function show(Package_Room $package_Room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package_Room  $package_Room
     * @return \Illuminate\Http\Response
     */
    public function edit(Package_Room $package_Room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package_Room  $package_Room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package_Room $package_Room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package_Room  $package_Room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package_Room $package_Room)
    {
        //
    }

    public function packageRooms($id){
        try{
          $roomIds = Package_Room::where('package_id', $id)->select('room_id')->get();
          $flatten = collect(Arr::flatten($roomIds))->pluck('room_id');
          $rooms = [];
          foreach ($flatten as $key => $a){
              $rooms[] = Room::where('id', $a)->get();
          }
          $reFlatten = Arr::flatten($rooms);
          return response()->json([
              'error' => false,
              'message' => null,
              'data' => $reFlatten
          ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
    public function roomDevices($id){
        try{
            $roomIds = Package_Room::where('room_id', $id)->select('device_id')->get();
            $flatten = collect(Arr::flatten($roomIds))->pluck('device_id');
            $rooms = [];
            foreach ($flatten as $key => $a){
                $rooms[] = Device::where('id', $a)->get();
            }
            $reFlatten = Arr::flatten($rooms);
            return response()->json([
                'error' => false,
                'message' => null,
                'data' => $reFlatten
            ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
