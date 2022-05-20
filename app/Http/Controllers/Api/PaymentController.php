<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Models\OrderStatusCode;
use App\Models\Order;
use App\Models\StripeRequestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe;

class PaymentController extends Controller
{
    protected $secret_key;

    public function __construct()
    {
        $this->secret_key = env('STRIPE_SECRET');
    }

    private function get_order_status_code_id($status_code)
    {
        return OrderStatusCode::where('status_code', $status_code)->first();
    }

    public function charge(Request $request)
    {
        $order = Order::where(['id', $request->order_id, 'order_status_code_id' => $this->get_order_status_code_id('payment.pending')])->first();

        if (isset($order) && ($order != null)) {
            $request_data = array("amount"=>$order->amount, "currency"=>'usd', "source"=>$request->stripe_token);

            $id = DB::table('stripe_request_responses')->insertGetId([
                'stripe_request' => json_encode($request_data),
            ]);
            Stripe\Stripe::setApiKey($this->secret_key);
            $res = Stripe\Charge::create([
                "amount" => $order->amount,
                "currency" => "usd",
                "source" => $request->stripe_token,
                "description" => "Test Payment For Sentegrate"
            ]);
            StripeRequestResponse::where('id',$id)->update([
                'stripe_response'=> $res
            ]);

            //here just need to check the response and of status succeeded send the response of amount is charged
        }
    }
}
