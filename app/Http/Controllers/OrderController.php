<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
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
            Stripe\Stripe::setApiKey($this->secret_key);
            $session  = Stripe\Checkout\Session::create([
                'success_url' => 'http://localhost:4200/order-success',
                'cancel_url' => 'http://localhost:4200/order-error',
                'payment_method_types' => ['card', 'alipay'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'T Shirt'
                            ],
                            'unit_amount' => 2000
                        ],
                        'quantity' => 1
                    ]
                ],
                'mode' => 'payment'
            ]);
            return response()->json($session, 200);

        }catch (\Exception $e) {
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
