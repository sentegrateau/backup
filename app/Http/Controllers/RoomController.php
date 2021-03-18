<?php

namespace App\Http\Controllers;
use App\Models\Package;
use Illuminate\Support\Facades\Validator;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $rooms = Room::all();
            // dd($packages);
            foreach($rooms as $r){
                $r->packages;
            }

            // $rooms = Room::all();
            return response()->json(
                [
                    'error' => false,
                    'message' => [],
                    'data' => $rooms
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
                'name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->all(),
                    'data' => null,
                ], 422);
            }

             $room = new Room($request->all());

             $room->save();

            return response()->json(
            [
                'error' => false,
                'message' => [$room->name . ' created successfully'],
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
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $room = Room::where('id', $id)->firstOrFail();
            return response()->json(
                [
                    'error' => false,
                    'message' => [],
                    'data' => $room,
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $room = Room::where('id', $id)->delete();
            return response()->json(
                [
                    'error' => false,
                    'message' => ["Deleted Successfully ! "],
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
}
