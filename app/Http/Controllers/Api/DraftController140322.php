<?php

namespace App\Http\Controllers\Api;

use App\Models\Draft;
use App\Models\DraftItem;
use App\Models\Setting;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
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
            $drafts->orderBy('order', 'asc')->orderBy('updated_at', 'desc')->toSql();

            $all_drafts = $drafts->orderBy('order', 'asc')->orderBy('updated_at', 'desc')->get();

            if ($request->has('isValid')) {
                $all_drafts->where('validity', '>=', Carbon::now());
            }
            return $this->sendResponse($all_drafts, 'Requested Data');

        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
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
            //'title' => 'required | unique:drafts,title,NULL,id,category,draft,deleted_at,NULL',
            'type' => 'required',
            'draft_items' => 'required',

        ];
        try {
            if (!empty($request['user_id'])) {
                $rules['title'] = 'required | unique:drafts,title,' . $request['title'] . ',id,category,draft,deleted_at,NULL,user_id,' . $request['user_id'];
                // $this->pr($rules);die;
            }
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
            if ($request->has('draft_items') && count($request->draft_items)) {
                foreach ($request->draft_items as $draft_item) {
                    DraftItem::insert([
                        'draft_id' => $draft['id'],
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity']
                    ]);
                }
            }
            DB::commit();

            return $this->sendResponse($draft, 'Kit Saved Successfully');
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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
        $rules = [
            'id' => 'required',
            'user_id' => 'required',
            'partner_id' => 'required',
            'title' => 'required | unique:drafts,title',
            'type' => 'required',
            'draft_items' => 'required'
        ];
        try {
            $validation = Validator::make($request->all(), $rules);
            DB::beginTransaction();
            $draft->update([
                'user_id' => $request['user_id'],
                'partner_id' => $request['partner_id'],
                'type' => $request['type'],
                'title' => $request['title'],
                'category' => $request['category'] ? $request['category'] : 'draft',
            ]);
            if ($request->has('draft_items') && is_array($request->draft_items) && count($request->draft_items)) {
                $draft->items()->delete();
                foreach ($request->draft_items as $draft_item) {
                    DraftItem::insert([
                        'draft_id' => $draft['id'],
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity']
                    ]);
                }
            }
            DB::commit();
            return $this->sendResponse('', 'Kit updated successfully');
        } catch (\Exception $e) {
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
                return $this->sendResponse('', 'Deleted successfully');
            }
            return $this->sendError('Draft/Quotation not found', '', 404);
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getDraftItems($id): JsonResponse
    {
        try {
            $orderItems = DraftItem::with('room', 'device', 'package')->where('draft_id', $id)->get();
            $draftItems = [];
            foreach ($orderItems as $value) {
                if ($value['package']['status'] == '1') {
                    array_push($draftItems, $value);
                }
            }
            return $this->sendResponse($draftItems, 'Requested Data');

        } catch (\Exception $e) {
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
            $expiry = settings('expiry');

            $getUser = User::where('id', $request['user_id'])->first();


            $quotation = Draft::create([
                'user_id' => $request['user_id'],
                'partner_id' => $request['partner_id'],
                'title' => $request['title'],
                'type' => 'user',
                'category' => 'quotation',
                'quotation_no' => Str::random(4),
                'total_amount' => $request['total_amount'],
                'validity' => Carbon::now()->addDay(($expiry))
            ]);

            if ($request->has('draft_items') && count($request->draft_items)) {

                foreach ($request->draft_items as $draft_item) {

                    DraftItem::insert([
                        'draft_id' => $quotation['id'],
                        'package_id' => $draft_item['package_id'],
                        'room_id' => $draft_item['room_id'],
                        'device_id' => $draft_item['device_id'],
                        'quantity' => $draft_item['quantity'],
                        'price' => $draft_item['price']
                    ]);

                }
            }
            Draft::where(['id' => $request['draft_id'], 'category' => 'draft'])->where('type','!=','admin')->delete();
            DB::commit();
            $draftItems = DraftItem::with('room', 'device', 'package')->where('draft_id', $quotation['id'])->get();
            $setting = Setting::where(function ($query) {
                return $query->where('module_name', 'name')->orWhere('module_name', 'company')->orWhere('module_name', 'email');
            })->pluck('content', 'module_name');




            //$this->pr($setting['name']);die;
            //$this->pr($quotation->toArray());$this->pr($draftItems->toArray(),1);
            $pdf = PDF::loadView("quotation", ["quotation" => $quotation, "draft_items" => $draftItems, 'setting' => $setting]);

            //echo $pdf->output();die;
            $quotationNo = explode("-",$quotation->quot_name,2)[1];

            Mail::send("quotation-email", ["quotationNo" => $quotationNo,"quotation" => $quotation, "draft_items" => $draftItems,"user"=>$getUser], function ($message) use ($pdf, $getUser, $quotationNo) {
                $message->to($getUser->email)
                    ->subject('Sentegrate Quotation No: '.$quotationNo)
                    ->attachData($pdf->output(), 'quotation.pdf');
            });
            if (Mail::failures()) {
                return $this->sendError('Error', 'Error in sending email', 422);
            } else {
                return $this->sendResponse(['quotation' => $quotation], 'Email has been send successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
