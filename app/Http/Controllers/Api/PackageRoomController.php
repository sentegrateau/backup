<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DraftItem;
use App\Models\Package_Room;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $allResult = DB::table('package__room__device')
                ->leftJoin('packages', 'package__room__device.package_id', '=', 'packages.id')
                ->leftJoin('rooms', 'package__room__device.room_id', '=', 'rooms.id')
                ->leftJoin('devices', 'package__room__device.device_id', '=', 'devices.id')
                ->select('package__room__device.*', 'packages.name as package_name', 'rooms.name as room_name', 'devices.name as device_name')->get();
            return response()->json([
                'error' => false,
                'message' => 'Requested Data',
                'data' => $allResult
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function store(Request $request): JsonResponse
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
            })->exists();
            if ($record_exists) {
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

    public function packageRooms($id, $draftId = null)
    {
        try {
            $roomIds = Package_Room::where('package_id', $id)->select('room_id')->get();
            $flatten = collect(Arr::flatten($roomIds))->pluck('room_id')->unique();
            $rooms = [];
            foreach ($flatten as $key => $a) {
                $devices = [];
                $room = Room::where('id', $a)->where('status', '1')->first();
                if ($room) {
                    $roomIds = Package_Room::where([
                        ['package_id', $id],
                        ['room_id', $a]
                    ])
                        ->select('device_id')->get();
                    $flatten1 = collect(Arr::flatten($roomIds))->pluck('device_id')->unique();
                    foreach ($flatten1 as $key => $b) {
                        $min_max = Package_Room::where([
                            ['package_id', $id],
                            ['room_id', $a],
                            ['device_id', $b]
                        ])->select('min_qty', 'max_qty')->first();
                        $cartQty = 0;
                        if (!empty($draftId)) {
                            $draftItem = DraftItem::where([['package_id', $id],
                                ['room_id', $a],
                                ['draft_id', $draftId],
                                ['device_id', $b]])->first();
                            if (!empty($draftItem->quantity))
                                $cartQty = $draftItem->quantity;
                        }
                        $device = Device::where('id', $b)->where('status', '1')->first();
                        if ($device) {
                            $device->min_qty = $min_max['min_qty'];
                            $device->max_qty = $min_max['max_qty'];
                            $device->cart_qty = $cartQty;
                            $devices[] = $device;
                        }
                    }
                    if (!empty($devices)) {
                        $room['devices'] = $devices;
                        $rooms[] = $room;
                    }

                }
            }
            $reFlatten = Arr::flatten($rooms);
            return response()->json([
                'error' => false,
                'message' => null,
                'data' => $reFlatten
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function roomDevices($id)
    {
        try {
            $roomIds = Package_Room::where('room_id', $id)->select('device_id')->get();
            $flatten = collect(Arr::flatten($roomIds))->pluck('device_id')->unique();
            $rooms = [];
            foreach ($flatten as $key => $a) {
                $rooms[] = Device::where('id', $a)->get();
            }
            $reFlatten = Arr::flatten($rooms);
            return response()->json([
                'error' => false,
                'message' => null,
                'data' => $reFlatten
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function minMaxQty(Request $request)
    {
        try {
            if ($request->has('package') && $request->has('room') && $request->has('device')) {
                $result = Package_Room::where([
                    ['package_id', '=', $request['package']],
                    ['room_id', '=', $request['room']],
                    ['device_id', '=', $request['device']],
                ])->select('min_qty', 'max_qty')->first();
                return response()->json([
                    'error' => false,
                    'message' => null,
                    'data' => $result
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
