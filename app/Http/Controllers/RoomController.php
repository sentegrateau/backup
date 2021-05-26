<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as BaseController;

class RoomController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $rooms = Room::all();
            return $this->sendResponse($rooms, 'All Rooms');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(),500);
        }
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $rules = [
                'name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
               return $this->sendError('Validation Errors',$validator->errors(), 422);
            }
            Db::beginTransaction();
             $room = new Room($request->all());
             $room->save();
             DB::commit();
            return $this->sendResponse($room, 'Room Created Successfully');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
            }
    }
    public function show($id): JsonResponse
    {
        try {
            $room = Room::findOrFail($id);
            return $this->sendResponse($room, 'Requested Room');
            } catch (\Exception $e) {
                return $this->exceptionHandler($e->getMessage(),500);
            }
    }
    public function update(Request $request, Room $room): JsonResponse
    {
        try {
            if ($request->has('activation')){
                DB::beginTransaction();
                $update = $room->update(['status' => $request['activation']]);
                DB::commit();
                if ($update) {
                    return $this->sendResponse('','Status updated Successfully');
                }
            }else {
                DB::beginTransaction();
                $update = $room->update($request->all());
                DB::commit();
                if ($update) {
                    return $this->sendResponse('','Room updated successfully');
                }
            }

        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(),500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $room = Room::where('id', $id)->delete();
            return $this->sendResponse('','Room deleted successfully');
            } catch (\Exception $e) {
                return $this->exceptionHandler($e->getMessage(),500);
            }
    }
}
