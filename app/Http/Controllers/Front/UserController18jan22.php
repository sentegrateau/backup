<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\countries;
use App\Models\ShippingDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Hash;

//use Zend\Diactoros\Request;


class UserController extends Controller
{

    public $role2 = ['owner' => 'Home Owner', 'installer' => 'Installer / Electrician', 'developer' => 'Developer'];

    public $order_status = [0 => 'Pending payment', 1 => 'Initial payment received', 4 => 'On Hold', 2 => 'Dispatched', 3 => 'Completed', -1 => 'Cancelled', 5 => 'Refunded'];

    public function index()
    {
        $shippingAdd = ShippingDetail::where('user_id', Auth::user()->id)->first();
        //$this->pr($shippingAdd->toArray());die;
        $role2 = $this->role2;
        $countries = countries::get()->toArray();
        //echo "<pre>";print_r($countries);die;
        return view('front.users.index', compact('shippingAdd', 'role2', 'countries'));
    }

    public function verifyOtherUser($id)
    {
        $user = User::where('id', $id)->first();
        // $user->update(['status' => 1]);
        $user->sendEmailVerificationNotification();
        //return
        echo "verified link sent to the user";
        die;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        //profile store
        $id = Auth::user()->id;
        $validation = [
            'name' => 'required|max:15|min:2',
            'ship_address.shipping_zip' => 'required|numeric',
            'bill_address.billing_zip' => 'required|numeric',
            'contact' => 'required|max:10|min:10',
            'bill_address.billing_phone' => 'required|numeric',
            'ship_address.shipping_phone' => 'required|numeric',
            //'abn' => 'required|numeric|max:11|min:11',
        ];
        $validateMessages = [];
        if($data['ship_address']['shipping_country'] == 'Australia'){
            $validation['ship_address.shipping_zip'] = 'required|digits:4';
            $validateMessages['ship_address.shipping_zip.digits'] = 'Shipping Postcode must be 4 digits.';
        }
        if($data['bill_address']['billing_country'] == 'Australia'){
            $validation['bill_address.billing_zip'] = 'required|digits:4';
            $validateMessages['bill_address.billing_zip.digits'] = 'Billing Postcode must be 4 digits.';
        }
        $this->validate($request, $validation,$validateMessages);
        if (Auth::user()->role2 != 'owner') {
            $this->validate($request, array(
                'company' => 'required',
                //'abn' => 'required|numeric|max:11|min:11',
                'abn' => 'required|digits:11',
                'ship_address.shipping_zip.digits' => 'Shipping Postcode must be 4 digits.',
                'bill_address.billing_zip.digits' => 'Billing Postcode must be 4 digits.',
                'ship_address.shipping_phone.numeric' => 'Shipping Phone number must be number.',
                'bill_address.billing_phone.numeric' => 'Billing Phone number must be number.',
            ));
        }
        if (!empty($data['password'])) {
            $this->validate($request, array(
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ));
        }

        $User = User::find($id);
        $User->name = $data['name'];
        $User->contact = $data['contact'];
        if (Auth::user()->role2 != 'owner') {
            $User->abn = $data['abn'];
            $User->company = $data['company'];
        } else {
            $User->abn = null;
            $User->company = null;
        }
        if (!empty($data['password'])) {
            $User->password = Hash::make($data['password']);
        }
        $User->save();
        //address store
        if (!empty($data['same_add']) && $data['same_add'] == 1) {
            $data['bill_address']['billing_first_name'] = $data['ship_address']['shipping_first_name'];
            $data['bill_address']['billing_last_name'] = $data['ship_address']['shipping_last_name'];
            if (!empty($data['ship_address']['shipping_email']))
                $data['bill_address']['billing_email'] = $data['ship_address']['shipping_email'];
            $data['bill_address']['billing_phone'] = $data['ship_address']['shipping_phone'];
            $data['bill_address']['billing_address'] = $data['ship_address']['shipping_address'];
            $data['bill_address']['billing_address_2'] = $data['ship_address']['shipping_address_2'];
            $data['bill_address']['billing_country'] = $data['ship_address']['shipping_country'];
            $data['bill_address']['billing_state'] = $data['ship_address']['shipping_state'];
            $data['bill_address']['billing_city'] = $data['ship_address']['shipping_city'];
            $data['bill_address']['billing_zip'] = $data['ship_address']['shipping_zip'];
        }
        $delivery_details = array_merge($data['ship_address'], $data['bill_address']);
        $delivery_details['user_id'] = Auth::user()->id;
        $delivery_details['same_add'] = (!empty($data['same_add']) && $data['same_add'] == 1) ? 1 : null;
        $shippingAdd = ShippingDetail::where('user_id', Auth::user()->id)->first();
        if (empty($shippingAdd)) {
            $ship = ShippingDetail::create($delivery_details);
        } else {
            $ship = ShippingDetail::find($shippingAdd->id);
            $ship->update($delivery_details);
        }



        Session::flash('success', 'Profile has been updated!');
        return redirect()->route('user.profile');
    }

    public function storeAddress(Request $request)
    {

        $this->validate($request, array(
            'ship_address.shipping_zip' => 'required|digits:4',
            'bill_address.billing_zip' => 'required|digits:4',
            'bill_address.billing_phone' => 'required|numeric',
            'ship_address.shipping_phone' => 'required|numeric',
            //'abn' => 'required|numeric|max:11|min:11',
        ), [
            'ship_address.shipping_zip.digits' => 'Shipping Postcode must be 4 digits.',
            'bill_address.billing_zip.digits' => 'Billing Postcode must be 4 digits.',
            'ship_address.shipping_phone.numeric' => 'Shipping Phone number must be number.',
            'bill_address.billing_phone.numeric' => 'Billing Phone number must be number.',

        ]);

        $data = $request->all();
        if (!empty($data['same_add']) && $data['same_add'] == 1) {
            $data['bill_address']['billing_first_name'] = $data['ship_address']['shipping_first_name'];
            $data['bill_address']['billing_last_name'] = $data['ship_address']['shipping_last_name'];
            if (!empty($data['ship_address']['shipping_email']))
                $data['bill_address']['billing_email'] = $data['ship_address']['shipping_email'];
            $data['bill_address']['billing_phone'] = $data['ship_address']['shipping_phone'];
            $data['bill_address']['billing_address'] = $data['ship_address']['shipping_address'];
            $data['bill_address']['billing_address_2'] = $data['ship_address']['shipping_address_2'];
            $data['bill_address']['billing_country'] = $data['ship_address']['shipping_country'];
            $data['bill_address']['billing_state'] = $data['ship_address']['shipping_state'];
            $data['bill_address']['billing_city'] = $data['ship_address']['shipping_city'];
            $data['bill_address']['billing_zip'] = $data['ship_address']['shipping_zip'];
        }
        $delivery_details = array_merge($data['ship_address'], $data['bill_address']);
        $delivery_details['user_id'] = Auth::user()->id;
        $delivery_details['same_add'] = (!empty($data['same_add']) && $data['same_add'] == 1) ? 1 : null;
        $shippingAdd = ShippingDetail::where('user_id', Auth::user()->id)->first();
        if (empty($shippingAdd)) {
            $ship = ShippingDetail::create($delivery_details);
        } else {
            $ship = ShippingDetail::find($shippingAdd->id);
            $ship->update($delivery_details);
        }
        Session::flash('success', 'Address has been updated!');
        return redirect()->route('user.profile');
    }


    public function myOrder(Request $request)
    {
        $user_id = Auth::id();
        $orders = Order::where('user_id', $user_id)->orderBy('id', 'desc')->paginate();
        $order_status = $this->order_status;
        $settings = Setting::whereIn('module_name', ['gst', 'pay_installation', 'ship_standard_text', 'ship_express_text', 'standard_ship_amt', 'express_ship_amt'])->pluck('content', 'module_name')->toArray();

        return view('front.users.my-orders', compact('orders', 'order_status', 'settings'));
    }

    public function orderstatus ( $order_id)
    {
        $orderData = $this->getOrderDetails($order_id);
        $order = $orderData['order'];
        $items = $this->filteringSubItems($order->toArray());
        $shippingText = $orderData['shippingText'];
        $orders = $orderData['orders'];
        //echo "<pre>";print_r($orders);die;

        $total = $orderData['total'];
        $discount = $orderData['discount'];
        $paid_amt = $orderData['paid_amt'];
        return view('front.users.orderstatus', compact('order', 'shippingText', 'orders', 'total', 'order_id', 'discount', 'paid_amt','items'));


    }


    public function bankImgRemove(Request $request, $order_id)
    {

        $order = Order::findOrFail($order_id);

        Storage::disk('order_bank_uploads')->delete($order->bank_img);

        $order->bank_img = '';

        $order->save();
        return redirect()->back();
    }

    public function invoiceDownload(Request $request, $order_id)
    {
        /*$orderData = $this->getOrderDetails($order_id);
        $order = $orderData['order'];
        $shippingText = $orderData['shippingText'];
        $orders = $orderData['orders'];
        $total = $orderData['total'];*/
        $orderData = $this->getOrderDetails($order_id);
        $order = $orderData['order'];
        $items = $this->filteringSubItems($order->toArray());
        $shippingText = $orderData['shippingText'];
        $orders = $orderData['orders'];
        //echo "<pre>";print_r($orders);die;
        $total = $orderData['total'];
        $discount = $orderData['discount'];
        $paid_amt = $orderData['paid_amt'];


        //return view('invoice', compact('order', 'shippingText', 'orders', 'total', 'order_id', 'discount', 'paid_amt'));


        //$pdf = PDF::loadView("invoice", ["order" => $order, 'shippingText' => $shippingText, 'orders' => $orders, 'total' => $total]);
        $pdf = PDF::loadView("invoice", compact('order', 'shippingText', 'orders', 'total', 'order_id', 'discount', 'paid_amt','items'));
        $headers = array(
            'Content-Type: application/pdf',
        );

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }


    public function filteringSubItems($items){

        $packageid = [];
        $finalItems =[];
       // $this->pr($items);

        foreach($items['items'] as $item){

            if(!in_array($item['package_id'],$packageid)){

                $finalItems[$item['package_id']][ $item['device']['id']]['title']  = $item['device']['name'];
                $finalItems[$item['package_id']][ $item['device']['id']]['qty']  = $item['quantity'];


                array_push($packageid,$item['package_id']);

            }else if( array_key_exists( $item['device']['id'],$finalItems[$item['package_id']] )){
                $finalItems[$item['package_id']][ $item['device']['id']]['qty']  += $item['quantity'] ;
            }else{

                $finalItems[$item['package_id']][ $item['device']['id']]['title']  = $item['device']['name'];
                $finalItems[$item['package_id']][ $item['device']['id']]['qty']  = $item['quantity'];
            }


        }


        return $finalItems;
    }

    public function orderThankYou(Request $request, $order_id)
    {
        $orderData = $this->getOrderDetails($order_id);
        $order = $orderData['order'];
        $items = $this->filteringSubItems($order->toArray());
        $shippingText = $orderData['shippingText'];
        $orders = $orderData['orders'];
       // echo "<pre>";print_r($orderData);die;

        $total = $orderData['total'];
        $discount = $orderData['discount'];
        $paid_amt = $orderData['paid_amt'];
        return view('front.users.thank-you', compact('order', 'shippingText', 'orders', 'total', 'order_id', 'discount', 'paid_amt','items'));
    }


    public function getOrderDetails($order_id)
    {
        $orders = [];
        $order = Order::find($order_id);


        $setting = Setting::whereIn('module_name', ['gst', 'ship_' . $order->ship_type . '_text', $order->ship_type . '_ship_amt'])->pluck('content', 'module_name');
        $text = $setting['ship_' . $order->ship_type . '_text'];
        $ship_amt = $setting[$order->ship_type . '_ship_amt'];
        $gst = $setting['gst'];
        $shippingText = ['text' => $text, 'gst' => $gst, 'amt' => $ship_amt];

        $total = [];
        $divCount = count($order->items);
        foreach ($order->items as $item) {
            $price = ((float)$item->device->price * (float)$item->quantity);
            if (empty($total[$item->package->name]))
                $total[$item->package->name] = ['total' => 0, 'gstTotal' => 0, 'productTotal' => 0, 'qty' => 0];

            $total[$item->package->name]['total'] += $price;
            $gst = ($price * $shippingText['gst']) / 100;
            $total[$item->package->name]['gstTotal'] += $gst;
            $total[$item->package->name]['productTotal'] += round($gst + $price, 2);
            $total[$item->package->name]['qty'] += $item->quantity;




            $orders[$item->package->name] = ['package_id'=>$item->package->id,'item' => $item->package->name, 'paid_amt' => ($order->paid_amt / $divCount), 'count' => $divCount, 'discount' => $order->discount, 'price' => $total[$item->package->name]['total'], 'qty' => $total[$item->package->name]['qty'], 'gst' => $total[$item->package->name]['gstTotal'], 'productTotal' => $total[$item->package->name]['productTotal']];
        }
        //echo "<pre>";print_r($orders);die;
        return ['orders' => $orders, 'discount' => $order->discount, 'shippingText' => $shippingText, 'total' => 'total', 'order' => $order, 'paid_amt' => $order->paid_amt];
    }
}
