<?php

namespace App\Http\Controllers\Api;

use App\Models\Draft;
use App\Models\Order;
use App\Models\Setting;
use App\Models\ShippingDetail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function findOrCreateUser(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->role == 'user') {
                $allowedKeys = ['name', 'email', 'role', 'role2', 'abn', 'company'];
                $rules = [
                    'name' => 'required',
                    'email' => 'required|email',
                    'role' => 'required',
                    'company' => ['required', 'max:255'],
                    'abn' => 'required'
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return $this->sendError('Validation Errors', $validator->errors(), 422);
                }
                $find_user = User::where([
                    ['name', '=', $request['name']],
                    ['email', '=', $request['email']],
                    ['role', '=', $request['role']]
                ])->first();

                $kitId = null;
                try {
                    if (!empty($request['kit_name'])) {
                        $kitId = Draft::where('kit_name', $request['kit_name'])->first()->id;
                    }
                } catch (\Exception $exception) {

                }

                if (!$find_user) {
                    DB::beginTransaction();
                    $filteredParameters = array_intersect_key($request->all(), array_flip($allowedKeys));
                    // active user
                    $filteredParameters['status'] = 1;
                    $user = User::create($filteredParameters);
                    if (!empty($request['kit_name'])) {
                        $user->kitId = $kitId;
                    }
                    DB::commit();
                    return $this->sendResponse($user, 'Requested Data');
                } else {
                    if (!empty($request['kit_name'])) {
                        $find_user->kitId = $kitId;
                    }
                    return $this->sendResponse($find_user, 'Requested Data');
                }
            } else {
                $find_user = User::where([
                    ['name', '=', $request['name']],
                    ['role', '=', $request['role']]
                ])->first();
                if (!$find_user) {
                    $user = User::create($request->all());
                    return $this->sendResponse($user, 'Requested Data');
                } else {
                    return $this->sendResponse($find_user, 'Requested Data');
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function getShipping(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        if (!empty($data['user_id'])) {
            $shippingAdd = ShippingDetail::where('user_id', $data['user_id'])->first();
            return $this->sendResponse($shippingAdd, 'Shipping got successfully..');
        }
        return $this->sendResponse([], 'Shipping got successfully..');
    }


    public function uploadBankTransferFile(Request $request): JsonResponse
    {
        $rules = [
            'order_id' => 'required',
            'file' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Validation Errors', $validator->errors(), 422);
        }
        $order = Order::findOrFail($request->order_id);

        if ($request->hasfile('file')) {
            $image = $request->file('file');
            $name = 'order_bank_' . time() . $image->getClientOriginalName();
            Storage::disk('order_bank_uploads')->put($name, file_get_contents($image->getRealPath()));
            $order->bank_img = $name;
        }

        $order->save();

        return response()->json([], 200);
    }


    public function getInvalidTokenString(Request $request): JsonResponse
    {
        $settings = Setting::whereIn('module_name', ['invalid_token_heading', 'invalid_token_content'])->pluck('content', 'module_name');
        return response()->json(['setting' => $settings], 200);
    }

    public function getUser(Request $request)
    {
        $data = $request->all();
        $user = User::where('id',$data['user_id'])->first()->toArray();
        $settings = Setting::where('id', 1)->first()->toArray(); 
        return $this->sendResponse($user+['settings' => $settings], 'Requested Data');
    }

}
