<?php

namespace App\Http\Controllers;
use App\Models\Package_Room;
use Illuminate\Support\Facades\Validator;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as BaseController;

class DeviceController extends BaseController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $devices = Device::all();
            return $this->sendResponse($devices, 'All Devices');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = [
                'name' => 'required',
                'price' => 'required',
                'partner_id' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Errors', $validator->errors(),422);
            }
            DB::beginTransaction();
             $device = Device::create([
                 'partner_id' => $request['partner_id'],
                 'name' => $request['name'],
                 'description' => $request['description'],
                 'brand' => $request['brand'],
                 'model' => $request['model'],
                 'price' => $request['price'],
                 'discount' => $request['discount'],
                 'supplier' => $request['supplier'],
                 'image' => $request['image']
             ]);
            if ($request->has('quantities') && is_array($request->quantities) && count($request->quantities)){
                foreach ($request->quantities as $quantity){
                    Package_Room::create([
                        'package_id' => $quantity['package_id'],
                        'room_id' => $quantity['room_id'],
                        'device_id' => $device['id'],
                        'min_qty' => $quantity['min_qty'],
                        'max_qty' => $quantity['max_qty']
                    ]);
                }
            }
             DB::commit();
            return $this->sendResponse($device, 'New Device Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(),500);
        }
    }
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $device = Device::findOrFail($id);
            return $this->sendResponse($device, 'Requested Device');
        }catch (\Exception $e) {
            return $this->sendResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request, Device $device): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->has('activation')){
                DB::beginTransaction();
                $update = $device->update(['status' => $request['activation']]);
                DB::commit();
                if ($update) {
                    return $this->sendResponse('', 'Status updated successfully');
                }
            }else{
                DB::beginTransaction();
                $update = $device->update($request->all());
                DB::commit();
                if ($update) {
                    return $this->sendResponse('','Device updated successfully');
                }
            }

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $device = Device::where('id', $id)->delete();
            return $this->sendResponse('', 'Device deleted successfully');
            } catch (\Exception $e) {
                return $this->exceptionHandler($e->getMessage(), 500);
            }
    }
    // changing the state of the device
    public function changeStatus(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $device = Device::findOrFail($id);
            $update = $device->update(['status' => $request['status']]);
            DB::commit();
            if ($update){
                return $this->sendResponse('','Status updated successfully');
            }else{
                return $this->sendError('', 'Problem in updating status', 400);
            }
        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
