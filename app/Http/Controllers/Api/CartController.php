<?php

namespace App\Http\Controllers\Api;

use App\Models\DraftItem;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cart(Request $request): JsonResponse
    {
        $rules = [
            'user_id' => 'required',
            'draft_items' => 'required',

        ];
        try {
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return $this->sendError('Validation Error', $validation->errors(), 422);
            }
            DB::beginTransaction();

            if ($request->has('draft_items') && is_array($request->draft_items) && count($request->draft_items)) {
                foreach ($request->draft_items as $draft_item) {
                    DraftItem::create([
                        'user_id' => $request->user_id,
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity']
                    ]);
                }
            }
            DB::commit();

            return $this->sendResponse([], 'Kit Saved Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function getGstSetting(): JsonResponse
    {
        $settings = Setting::whereIn('module_name', ['gst', 'pay_installation','ship_standard_text','ship_express_text','standard_ship_amt','express_ship_amt','payment_due_now','payment_due_on_installation'])->pluck('content', 'module_name');
        return $this->sendResponse(['settings' => $settings], '');
    }


}
