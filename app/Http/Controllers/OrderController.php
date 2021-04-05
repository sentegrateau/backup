<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $order = Order::query();
            if($request->has('type')) {
                $order->where('type', $request['type']);
            }
            $orders = $order->get();
            return $this->sendResponse($orders, 'Data');
        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage(), $e->getCode());
        }
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules = [
            'user_id' => 'required',
            'partner_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'order_items' => 'required'
        ];
        try {
            $validation  = Validator::make($request->all(), $rules);
            if($validation->fails()){
                return $this->sendError('Validation Error', $validation->errors(), 422);
            }
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $request['user_id'],
                'partner_id' => $request['partner_id'],
                'type' => $request['type'],
                'amount' => $request['amount'],
                'title' => $request['title']
            ]);
            if($request->has('order_items') && is_array($request->order_items) && count($request->order_items)){
                foreach ($request->order_items as $order_item){
                    OrderItem::create([
                        'order_id' => $order['id'],
                        'package_id' => $order_item['package_id'],
                        'room_id' => $order_item['room_id'],
                        'device_id' => $order_item['device_id'],
                        'quantity' => $order_item['quantity']
                    ]);
                }
            }
            DB::commit();
            return $this->sendResponse($order, 'Created Successfully');

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
    public function getOrderItems($id): \Illuminate\Http\JsonResponse
    {
        try {
            $orderItems = OrderItem::with('room','device','package')->where('order_id', $id)->get();
            return $this->sendResponse($orderItems, 'Requested Data');

        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
