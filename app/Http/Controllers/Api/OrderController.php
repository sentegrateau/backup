<?php

namespace App\Http\Controllers\Api;

use App\Mail\OrderMail;
use App\Mail\OrderEmailMail;
use App\Models\Coupon;
use App\Models\CouponUsedUser;
use App\Models\Draft;
use App\Models\Order;
use App\Models\OrderEmail;
use App\Models\OrderItem;
use App\Models\OrderStatusCode;
use App\Models\ShippingDetail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use \Swift_Mailer;
use \Swift_SmtpTransport as SmtpTransport;

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
                'billing_information' => 'required',
                'order_data.order_items' => 'array|required',
            ];
            $newData = $request->all();
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Errors', $validator->errors(), 422);
            }
            $order_items = data_get($request, 'order_data.order_items', null);
            $shipping_info = data_get($request, 'shipping_information', null);
            $billing_info = data_get($request, 'billing_information', null);
            $user_info = (data_get($request, 'user_information', null));
            DB::beginTransaction();


            if(!empty($newData['coupon_code'])){
                $getCoupon = Coupon::where('coupon_code',$newData['coupon_code'])->first();
                if(!empty($getCoupon)){
                    $couponUsed = new CouponUsedUser();
                    $couponUsed->user_id = $request->order_data['user_id'];
                    $couponUsed->coupon_id = $getCoupon->id;
                    $couponUsed->save();
                }
            }




            $user = User::findOrFail($request->order_data['user_id']);
            $order = new Order();
            $order->user_id = $request->order_data['user_id'];
            $order->partner_id = $request->order_data['partner_id'];
            $order->type = $request->order_data['type'];
            $order->amount = $request->order_data['amount'];
            $order->draft_id = $request->order_data['draft_id'];
            $order->discount = $request->order_data['discount'];
            $order->shipping_info = json_encode($shipping_info);
            $order->billing_info = json_encode($billing_info);
            $payOnInstallation = settings('pay_installation');
            $dueNowPercentage = 100 - $payOnInstallation;

            /*$this->pr($dueNowPercentage);
            $this->pr($dueNowPercentage);*/

            //$order->paid_amt = round(($order->amount * $dueNowPercentage) / 100, 2);
            $order->paid_amt = round($request->paid_amount, 2);

            if (!empty($request->order_data['ship_type'])) {
                $shippingAmt = settings($request->order_data['ship_type'] . '_ship_amt');
                $order->ship_amt = $shippingAmt;
                $order->ship_type = $request->order_data['ship_type'];
            }
            /*if ($request->order_data['payment_type'] == 'paypal') {
                $order->order_status = '1';
                $order->payment_type = '1';
            } else {*/
                $order->order_status = '6';
                $order->payment_type = '0';
            //}
            if ($request->order_data['payment_type'] == 'paypal') {
                $order->payment_type = '1';
            }
            $order->coupon_code = $newData['coupon_code'];
            $order->save();
            if (isset($order_items) && ($order_items != null)) {
                foreach ($order_items as $order_item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->package_id = $order_item['package_id'];
                    $orderItem->room_id = $order_item['room_id'];
                    $orderItem->device_id = $order_item['device_id'];
                    $orderItem->quantity = $order_item['quantity'];
                    $orderItem->save();
                }
            }

            if ($request->order_data['payment_type'] != 'paypal') {
                Draft::where('id', $order->draft_id)->where('type', '!=', 'admin')->delete();
                
                
                Order::where('id',$order->id)->update(['order_status'=>0]);
                Order::where('id', $order->id)->update(['stripe_order_id' => 2]);
                $emailContent = OrderEmail::where('id',2)->first();
                $transport = (new SmtpTransport(config('mail.host_other'), config('mail.port_other'), config('mail.encryption_other')))->setUsername(config('mail.username_other'))->setPassword(config('mail.password_other'));

                $mailer = new Swift_Mailer($transport);
                Mail::setSwiftMailer($mailer);
                Mail::to($user->email)->send(new OrderMail($order,$emailContent));

                //$ordercount= Order::where('user_id',$order->user_id)->count();
                 $query = $request->query();
                $queryVal = $request->query();
                 $ordercount = Order::select( 'orders.*','users.*')->join('users', 'users.id', '=', 'orders.user_id')->where('users.company', '=', 'Smart Home Installer')->where('orders.user_id', '=',$order->user_id)->count();
                 //$ordercount= $ordercount->count();
                
                //$queryVal='Smart Home Installer';
              /*     $ordercount = $ordercount->where(function ($query) use ($queryVal) {
                $query->where('users.company', '=', 'Smart Home Installer');
                });*/

            // $ordercount = $ordercount->count() 
               //echo $ordercount->toSql();die();

                if ($ordercount==1)
                {
                //mail to particular email
                $emailOrder = OrderEmail::where('id',1)->first();
                if(!empty($emailOrder)){
                    Mail::to($user->email)->send(new OrderEmailMail($order,$emailOrder));
               }
            }
            }
            DB::commit();
            return response()->json(['order' => $order], 200);
            /* if (isset($order) && ($order != null)) {
                 Stripe\Stripe::setApiKey($this->secret_key);
                 $session = Stripe\Checkout\Session::create([
                     'mode' => 'payment',
                     'line_items' => $request['stripe_data'],
                     'success_url' => env('SUCCESS_URL').'/order-success',
                     'cancel_url' => env('CANCEL_URL').'/order-error',
                     'payment_method_types' => ['card'],

                 ]);
             }*/
            //Order::where('id',$order->id)->update(['stripe_order_id' => $session->id]);
            // payment type=>  1=>paypal,2=>Bank Transfer,




            //return response()->json($session, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
    
    public function paypalSuccess(Request $request){
        $aData = $request->all();     
        Order::where('id', $aData['order_id'])->update(['order_status'=>1]);
        
        $order = Order::where('id', $aData['order_id'])->first();
        $emailContent = OrderEmail::where('id',2)->first();
        $transport = (new SmtpTransport(config('mail.host_other'), config('mail.port_other'), config('mail.encryption_other')))->setUsername(config('mail.username_other'))->setPassword(config('mail.password_other'));

        $mailer = new Swift_Mailer($transport);
        Mail::setSwiftMailer($mailer);


        Mail::to($aData['email'])->send(new OrderMail($order,$emailContent));
/*        Mail::mailer('smtp2')->to('enquiries@sentegrate.com.au')->send(new OrderMail($order,$emailContent));
*/      $ordercount = Order::select( 'orders.*','users.*')->join('users', 'users.id', '=', 'orders.user_id')->where('users.company', '=', 'Smart Home Installer')->where('orders.user_id', '=',$order->user_id)->count();
        //$ordercount= Order::where('user_id',$order->user_id)->count();
        if ($ordercount==1)
        {
        //mail to particular email
        $emailOrder = OrderEmail::where('id',1)->first();
        if(!empty($emailOrder)){
            Mail::to($aData['email'])->send(new OrderEmailMail($order,$emailOrder));
        }
    }
        
        return response()->json(['order' => $order], 200);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user_info = (data_get($request, 'user_information', null));
            if(!empty($user_info['password'])){
                $rules = [
                    'user_information.password' => ['required', 'string', 'min:6', 'confirmed'],
                ];
            }
            $rules['user_information.contact'] = ['required', 'string'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Errors', $validator->errors(), 422);
            }
            $user = User::findOrFail($user_info['user_id']);
            if (!empty($user_info['contact'])) {
                $user->contact = $user_info['contact'];
            }
            if (!empty($user_info['password'])) {
                $user->password = Hash::make($user_info['password']);
            }
            $user->save();
            return response()->json(['message'=>'profile updated successfully...','profile'=>$user], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function updateShipping(Request $request)
    {
        try {
            $shipping_info = data_get($request, 'shipping_information', null);
            $billing_info = data_get($request, 'billing_information', null);
            $shipping = ShippingDetail::where('user_id', $shipping_info['user_id'])->first();
            if ($shipping === null) {
                $shipping = new ShippingDetail();
            }
            $shipping->user_id = $shipping_info['user_id'];
            $shipping->shipping_first_name = $shipping_info['first_name'];
            $shipping->shipping_last_name = $shipping_info['last_name'];
            $shipping->shipping_address = $shipping_info['address'];
            $shipping->shipping_address_2 = !empty($shipping_info['address2'])?$shipping_info['address2']:'';
            $shipping->shipping_country = $shipping_info['country'];
            $shipping->shipping_state = $shipping_info['state'];
            $shipping->shipping_city = $shipping_info['city'];
            $shipping->shipping_phone = $shipping_info['phone'];
            $shipping->shipping_zip = $shipping_info['zip'];
            $shipping->same_add = $shipping_info['same_add'];
            if ($shipping_info['same_add'] == 0) {
                $shipping->billing_first_name = $billing_info['first_name'];
                $shipping->billing_last_name = $billing_info['last_name'];
                $shipping->billing_address = $billing_info['address'];
                $shipping->billing_address_2 = !empty($billing_info['address2'])?$billing_info['address2']:'';
                $shipping->billing_country = $billing_info['country'];
                $shipping->billing_state = $billing_info['state'];
                $shipping->billing_city = $billing_info['city'];
                $shipping->billing_zip = $billing_info['zip'];
                $shipping->billing_phone = $billing_info['phone'];
            } else {
                $shipping->billing_first_name = $shipping_info['first_name'];
                $shipping->billing_last_name = $shipping_info['last_name'];
                $shipping->billing_address = $shipping_info['address'];
                $shipping->billing_address_2 = !empty($shipping_info['address2'])?$shipping_info['address2']:'';
                $shipping->billing_country = $shipping_info['country'];
                $shipping->billing_state = $shipping_info['state'];
                $shipping->billing_city = $shipping_info['city'];
                $shipping->billing_zip = $shipping_info['zip'];
                $shipping->billing_phone = $shipping_info['phone'];
            }

            $shipping->save();
            return response()->json(['message'=>'Shipping updated successfully...','shipping_details'=>$shipping], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function stripe_webhook(Request $request)
    {
        if (isset($request) && $request != null) {
            if ($request->data['object']['payment_status'] && $request->data['object']['payment_status'] == 'paid') {
                Order::where('stripe_order_id', $request->data['object']['id'])->update(['order_status_code_id' => OrderStatusCode::where('status_code', 'payment.recieved')->pluck('id')->first()]);
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
            $orderItems = OrderItem::with('room', 'device', 'package')->where('order_id', $id)->get();
            return $this->sendResponse($orderItems, 'Requested Data');

        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function updatePaymentStatus(Request $request): JsonResponse
    {
        $data = $request->all();
        Log::info(json_encode($request->all()));
        if (!empty($data['event_type']) && $data['event_type'] == 'PAYMENT.CAPTURE.COMPLETED') {

            $invoiceId = explode('-', $data['resource']['invoice_id']);
            $order = Order::find($invoiceId[2]);
            $order->stripe_order_id = $data['resource']['id'];
            $order->payment_type = '1';
            $order->order_status = '1';
            $order->save();

            Draft::where('id', $order->draft_id)->where('type', '!=', 'admin')->delete();

        }

        return response()->json([], 200);
    }

    public function getShippingAddress(Request $request)
    {
        $data = $request->all();
        if (isset($data['user_id']) && !empty($data['user_id'])) {
            $objShippingDetail = new ShippingDetail();
            $return = $objShippingDetail->where('user_id', $data['user_id'])->first();
            return response()->json(['data' => $return], 200);
        } else {
            return response()->json(['data' => 'need User Id'], 500);
        }
    }

    public function deleteOrder(Request $request){
        $data = $request->all();
        if (isset($data['order_id']) && !empty($data['order_id'])) {
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['data' => 'need Order Id'], 500);
        }
    }
}
