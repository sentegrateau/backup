<?php

namespace App\Http\Controllers;

use App\Models\Draft;
use App\Models\DraftItem;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Str;

class DraftController extends BaseController
{
    /**
     * @param Request $request
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

            if ($request->has('category')) {
               $drafts->where('category', $request['category']);
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
                'category' => $request['category'] ? $request['category'] : 'draft',
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
            DB::commit();
            return $this->sendResponse('', 'Draft updated successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $draft = Draft::findOrFail($id);
            if ($draft) {
                $draft->delete();
                return $this->sendResponse('','Deleted successfully');
            }
            return $this->sendError('Draft/Quotation not found', '', 404);
        }catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(),500);
        }

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getDraftItems($id): JsonResponse
    {
        try {
            $orderItems = DraftItem::with('room','device','package')->where('draft_id', $id)->get();
            $filtered = $orderItems->filter(function ($value, $key) {
                return $value['package']['status'] == '1';
            });
            return $this->sendResponse($filtered->all(), 'Requested Data');

        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveQuotation(Request $request): JsonResponse
    {
        $rules = [
            'user_id' => 'required',
            'partner_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'total_amount' => 'required',
            'draft_items' => 'required',
        ];

        try {
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return $this->sendError('Validation Error', $validation->errors(), 422);
            }
            DB::beginTransaction();
            $quotation = Draft::create([
                'user_id' => $request['user_id'],
                'partner_id' => $request['partner_id'],
                'title' => $request['title'],
                'type' => 'user',
                'category' => 'quotation',
                'quotation_no' => Str::random(4),
                'total_amount' => $request['total_amount'],
                'validity' => Carbon::now()->addDay(15)
            ]);
            if($request->has('draft_items') && is_array($request->draft_items) && count($request->draft_items)){
                foreach ($request->draft_items as $draft_item){
                    DraftItem::create([
                        'draft_id' => $quotation['id'],
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity'],
                        'price' => $draft_item['price']
                    ]);
                }
            }
            DB::commit();
            $draftItems = DraftItem::with('room','device','package')->where('draft_id', $quotation['id'])->get();
            $pdf = PDF::loadView("quotation", ["quotation" =>$quotation, "draft_items" => $draftItems]);
            Mail::send("quotation-email",["quotation" =>$quotation, "draft_items" => $draftItems], function($message) use ($pdf){
                $message->to('muhammadahmad476@gmail.com')
                    ->subject('Quotation')
                    ->attachData($pdf->output(),'quotation.pdf');
            });
            if (Mail::failures()){
                return $this->sendError('Error', 'Error in sending email', 422);
            }else {
                return $this->sendResponse('','Email has been send successfully');
            }
        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
