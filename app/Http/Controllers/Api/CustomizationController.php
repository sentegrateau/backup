<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Models\Draft;
use App\Models\Order;
use App\Models\Room;
use App\Models\Setting;
use App\Models\ShippingDetail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function foo\func;

class CustomizationController extends BaseController
{

    public function getRoomsWithOrder(Request $request, $orderId): JsonResponse
    {
        if (empty($orderId)) {
            return $this->sendError('Validation Errors', 'Order Id is required field', 422);
        }

        $rooms = Room::select(['rooms.id', 'rooms.name'])
            ->with(['packageRoom.device' => function ($p) use ($orderId) {
                return $p->select(['id', 'name'])->with(['deviceFeatureFunctions', 'deviceFeatureParams'])
                    ->withCount(['orderItems' => function ($q) use ($orderId) {
                        return $q->where('order_id', $orderId);
                    }]);
            }])
            ->withCount(['orderItems' => function ($q) use ($orderId) {
                return $q->where('order_id', $orderId);
            }])->get()->map(function ($room) {
                $allDevices = $room->packageRoom->map(function ($pkgRoom) {
                    return $pkgRoom->device;
                });

                unset($room->packageRoom);
                $room->devices = $allDevices;

                return $room;
            });



        return $this->sendResponse(['rooms' => $rooms], '');
    }

    public function getDevicesWithOrder(Request $request, $orderId, $roomId): JsonResponse
    {
        if (empty($orderId)) {
            return $this->sendError('Validation Errors', 'Order Id is required field', 422);
        }
        if (empty($roomId)) {
            return $this->sendError('Validation Errors', 'Room Id is required field', 422);
        }

        $rooms = Device::select(['devices.id', 'devices.name'])
            ->leftJoin('package__room__device', 'devices.id', '=', 'package__room__device.device_id')
            ->where('package__room__device.room_id', $roomId)
            ->with(['deviceFeatureFunctions', 'deviceFeatureParams'])
            ->withCount(['orderItems' => function ($q) use ($orderId) {
                return $q->where('order_id', $orderId);
            }])
            ->get();

        return $this->sendResponse(['devices' => $rooms], '');
    }

}
