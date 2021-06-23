<?php

namespace App\Http\Controllers;

use App\Models\OrderStatusCode;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe;

class OrderController extends BaseController
{
    protected $secret_key;

    public function __construct()
    {
        $this->secret_key = env('STRIPE_SECRET');
    }

    public function index(Request $request)
    {

    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = [
                'order_data.user_id' => 'required',
                'order_data.partner_id' => 'required',
                'order_data.type' => 'required',
                'order_data.amount' => 'required',
                'shipping_information' => 'required',
                'order_data.order_items' => 'array|required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Errors', $validator->errors(),422);
            }
            $order_items  = data_get($request,'order_data.order_items',null);
            $shipping_info = json_encode(data_get($request,'shipping_information',null));
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $request->order_data['user_id'],
                'partner_id' => $request->order_data['partner_id'],
                'type' => $request->order_data['type'],
                'amount' => $request->order_data['amount'],
                'shipping_info' =>$shipping_info,

            ]);

            if (isset($order_items) && ($order_items != null)){
                foreach ($order_items as $order_item){

                    OrderItem::create([
                        'order_id' => $order->id,
                        'package_id' => $order_item['package_id'],
                        'room_id' => $order_item['room_id'],
                        'device_id' => $order_item['device_id'],
                        'quantity' => $order_item['quantity']
                    ]);
                }
            }
//            Stripe\Stripe::setApiKey($this->secret_key);
            if (isset($order) && ($order != null)) {
                Stripe\Stripe::setApiKey('sk_test_51IxTP1Gkj3mYwg4UvTOCAR1DgCyoquCGr1kcYiESUaUt78SdeQsLhkYaCvpI9lpWoz2IcosLPlv8U7TwdJW1BNfB00SFAYzcWu');
                $session = Stripe\Checkout\Session::create([
                    'success_url' => 'http://localhost:4200/order-success',
                    'cancel_url' => 'http://localhost:4200/order-error',
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'usd',
                                'product_data' => [
                                    'name' => 'Sentegrate Order'
                                ],
                                'unit_amount' => $order->amount
                            ],
                            'quantity' => 1
                        ]
                    ],
                    'mode' => 'payment'
                ]);
            }
            Order::where('id',$order->id)->update(['stripe_order_id' => $session->id]);
            DB::commit();
            return response()->json($session, 200);

        }catch (\Exception $e) {
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
    public function stripe_webhook(Request $request)
    {
        if (isset($request) && $request != null){
            if ($request->data['object']['payment_status'] && $request->data['object']['payment_status'] == 'paid' ){
                Order::where('stripe_order_id',$request->data['object']['id'])->update(['order_status_code_id' =>OrderStatusCode::where('status_code', 'payment.recieved')->pluck('id')->first()]);
            }
        }
    }
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
