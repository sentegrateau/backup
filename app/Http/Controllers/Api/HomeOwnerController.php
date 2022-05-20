<?php

namespace App\Http\Controllers\Api;

use App\Models\HomeOwner;
use App\Models\HomeOwnerRoom;
use App\Models\OrderHomeOwnerRoom;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class HomeOwnerController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $rooms = Room::query()->where('home_owner', '1')->where('status', '1')->get();
            $homeOwners = HomeOwner::query()->where('status', '1')->get()->toArray();
            $homeOwnerRooms = HomeOwnerRoom::query()->select(['home_owner_id', 'room_id'])->get();
            $allHomeOwnerRooms = [];
            foreach ($homeOwnerRooms as $homeOwnerRoom) {
                $homeId = $homeOwnerRoom->home_owner_id;
                $roomId = $homeOwnerRoom->room_id;
                $allHomeOwnerRooms[$roomId . '-' . $homeId] = $homeId;
            }


            $allRooms = [];
            foreach ($rooms as $key => $room) {
                $allHomeOwners = [];
                foreach ($homeOwners as $homeOwner) {
                    if (!empty($allHomeOwnerRooms[$room['id'] . '-' . $homeOwner['id']])) {
                        $room['checked'] = true;
                        $homeOwner['active'] = true;
                        $homeOwner['checked'] = true;
                    } else {
                        $room['checked'] = false;
                        $homeOwner['active'] = false;
                        $homeOwner['checked'] = false;
                    }
                    $allHomeOwners[] = $homeOwner;
                }
                $room->active = ($key == 0) ? true : false;
                $room['homeOwner'] = $allHomeOwners;
                $allRooms[] = $room;
            }
            return $this->sendResponse($rooms, 'All Home Owner');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }


    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            if ($request->has('homeOwner') && is_array($request->homeOwner) && count($request->homeOwner)) {
                HomeOwnerRoom::query()->delete();
                foreach ($request->homeOwner as $quantity) {
                    HomeOwnerRoom::create([
                        'room_id' => $quantity['room_id'],
                        'home_owner_id' => $quantity['home_owner_id'],
                    ]);
                }
            }

            DB::commit();
            return $this->sendResponse([], 'Home Owner Saved Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->exceptionHandler($exception->getMessage(), 500);
        }
    }

    public function getHomeOwner(Request $request)
    {
        try {
            $package = Room::query()->where('home_owner', '1')->where('status', '1');
            $packages = $package->with(['homeOwners'])->get();
            return $this->sendResponse($packages, '');

        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function saveHomeOwner(Request $request)
    {
        $rules = [
            'user_email' => 'required',
            'home_items' => 'required'
        ];
        try {

            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return $this->sendError('Validation Error', $validation->errors(), 422);
            }
            DB::beginTransaction();
            $user = User::where('email', $request['user_email'])->first();
            if ($request->has('home_items') && count($request->home_items) && !empty($user->id)) {
                foreach ($request->home_items as $home_item) {
                    OrderHomeOwnerRoom::insert([
                        'user_id' => $user->id,
                        'home_owner_id' => $home_item['home_owner_id'],
                        'room_id' => $home_item['room_id'],
                    ]);
                }
            }
            //mail function here

            Mail::send("emails.home-owner-quote", [], function ($message) use ($request) {
                $message->to($request['user_email'])
                    ->subject('Home Owner Quotation');
            });

            DB::commit();

            return $this->sendResponse([], 'Quote sent successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }


}
