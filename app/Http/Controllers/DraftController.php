<?php

namespace App\Http\Controllers;

use App\Models\Draft;
use App\Models\DraftItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as BaseController;

class DraftController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $drafts = Draft::query();

            if ($request->has('type')) {
                $drafts->where('type', $request['type']);
            }

            if ($request->has('user_id')) {
                $drafts->where('user_id', $request['user_id']);
            }

            return $this->sendResponse($drafts->get(), 'Requested Data');

        }catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(),500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'user_id' => 'required',
            'partner_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'draft_items' => 'required',

        ];
        try {
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return $this->sendError('Validation Error', $validation->errors(), 422);
            }
            DB::beginTransaction();
            $draft = Draft::create([
                'user_id' => $request['user_id'],
                'partner_id' => $request['partner_id'],
                'type' => $request['type'],
                'title' => $request['title'],
            ]);
            if($request->has('draft_items') && is_array($request->draft_items) && count($request->draft_items)){
                foreach ($request->draft_items as $draft_item){
                    DraftItem::create([
                        'draft_id' => $draft['id'],
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity']
                    ]);
                }
            }
            DB::commit();

            return $this->sendResponse($draft, 'Draft Saved Successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $draft = Draft::where('id', $id)->first();
            return $this->sendResponse($draft, 'Requested Data');
        }catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Draft $draft
     * @return Response
     */
    public function edit(Draft $draft)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Draft $draft
     * @return JsonResponse
     */
    public function update(Request $request, Draft $draft): JsonResponse
    {
        try {
            DB::beginTransaction();
            $draft->update([
                'user_id' => $request['user_id'],
                'partner_id' => $request['partner_id'],
                'type' => $request['type'],
                'title' => $request['title'],
                'category' => $request['category'],
            ]);
            if($request->has('draft_items') && is_array($request->draft_items) && count($request->draft_items)){
                $draft->items()->delete();
                foreach ($request->draft_items as $draft_item){
                    DraftItem::create([
                        'draft_id' => $draft['id'],
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity']
                    ]);
                }
            }


        }catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Draft $draft
     * @return Response
     */
    public function destroy(Draft $draft)
    {
        //
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getDraftItems($id): JsonResponse
    {
        try {
            $orderItems = DraftItem::with('room','device','package')->where('draft_id', $id)->get();
            return $this->sendResponse($orderItems, 'Requested Data');

        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
