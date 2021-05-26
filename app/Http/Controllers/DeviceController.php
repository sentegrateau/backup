<?php

namespace App\Http\Controllers;
use App\Models\Package_Room;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as BaseController;

class DeviceController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $devices = Device::all();
            return $this->sendResponse($devices, 'All Devices');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
    public function store(Request $request): JsonResponse
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
    public function show($id): JsonResponse
    {
        try {
            $device = Device::where('id', $id)->with('packagesRoomsDevices')->first();
            return $this->sendResponse($device, 'Requested Device');
        }catch (\Exception $e) {
            return $this->sendResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request, Device $device): JsonResponse
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
                $update = $device->update([
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'brand' => $request['brand'],
                    'model' => $request['model'],
                    'price' => $request['price'],
                    'discount' => $request['discount'],
                    'supplier' => $request['supplier']
                ]);
                if ($request->has('packagesRoomsDevices')) {
                    $device->packagesRoomsDevices()->delete();
                    foreach ($request->packagesRoomsDevices as $packageRoomDevice){
                        Package_Room::create([
                            'package_id' => $packageRoomDevice['package_id'],
                            'room_id' => $packageRoomDevice['room_id'],
                            'device_id' => $device['id'],
                            'min_qty' => $packageRoomDevice['min_qty'],
                            'max_qty' => $packageRoomDevice['max_qty']
                        ]);
                    }
                }
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

    /**
     *  Change Device Image
     */
    public function changeImage(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                "image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            $getDevice = Device::findOrFail($id);
            if ($getDevice) {
              $imageUrl = $getDevice->image;
              $getImagePath = $this->remove(env('APP_URL'), $imageUrl);
              if (File::exists(public_path($getImagePath))) {
                  File::delete(public_path($getImagePath));
              }
              $image = $request->file('image');
              $teaser_image = time().'.'.$image->getClientOriginalExtension();
              $destination_path = public_path('/images');
              $image->move($destination_path, $teaser_image);
              $update  = $getDevice->update([
                  'image' => env('APP_URL').'/images/'.$teaser_image
              ]);
              if ($update) {
                  return $this->sendResponse($update, 'Image Updated Successfully');
              } else{
                  return $this->sendError('Error in updating image', '', 621);
              }
            }

        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
    /**
     * Remove Certain Data From String
     */
    private function remove($search, $subject, $caseSensitive = true)
    {
        return $caseSensitive
            ? str_replace($search, '', $subject)
            : str_ireplace($search, '', $subject);
    }
}
