<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderEmail;
use App\Models\OrderStatusLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class OrderController extends Controller
{
    public $routeName = 'orders';


    public function __construct()
    {
        view()->composer('*', function ($view) {
            $routeName = $this->routeName;
            $view->with(compact('routeName'));
        });
    }

    public $order_status = [99=>'Select Order Status',0 => 'Pending payment', 1 => 'Initial payment received', 4 => 'On Hold', 2 => 'Dispatched', 3 => 'Completed', -1 => 'Cancelled', 5 => 'Refunded' ,6=>'Incomplete'];

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {        
        $search = '';
        if($request->orderstatus!='')
        {
        $orderstatus = $request->orderstatus;
    }
    else{
        $orderstatus='99';
    }
        $query = $request->query();
        $queryVal = $request->query();
        
$settings = Setting::whereIn('module_name', ['gst', 'pay_installation', 'ship_standard_text', 'ship_express_text', 'standard_ship_amt', 'express_ship_amt'])->pluck('content', 'module_name')->toArray();

        // $orders = Order::with(['user']);
         $orders = Order::select( 'orders.*','users.name')->join('users', function($join) {
          $join->on('users.id', '=', 'orders.user_id');
         });

        
         if (!empty($queryVal['search'])) {
            $search = $queryVal['search'];
            $orders = $orders->where(function ($query) use ($queryVal) {
                $query->where('orders.id', '=', '' . $queryVal['search'] . '')->orWhere('orders.amount', 'like', '%' . $queryVal['search'] . '%')->orWhere('orders.paid_amt', 'like', '%' . $queryVal['search'] . '%')->orWhere('orders.ship_amt', 'like', '%' . $queryVal['search'] . '%')->orWhere('users.name', 'like', '%' . $queryVal['search'] . '%');
                });

           // $orders = $query->orWhere('users.name','like', '%' . $queryVal['search'] . '%' );

        }

        if($orderstatus!='99'){
               $orders = $orders->where('orders.order_status', '=', $orderstatus );
        }
          
       if (!empty($query) && !empty($query['sort']) && !empty($query['sortName'])) {
            if ($query['sortName']) {
                $orders = $orders->orderBy($query['sortName'], ($query['sort'] == 'asc') ? 'asc' : 'desc');          
            }
                     
        }
        else{
            $orders = $orders->orderBy('orders.id', 'desc');
        }        
    
      // echo $orders->toSql();exit();
       $orders = $orders->paginate(20);
        
      
      
      //print_r($orders);exit();
        return view('admin.orders.index')->with(['orders' => $orders, 'order_status' => $this->order_status, 'settings' => $settings,'search' => $search,'orderstatus' => $orderstatus]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        //$objOrder = new Order;
        $order = Order::where('id', '=', $id)->with(['user', 'items','draft'])->first();
        $settings = Setting::whereIn('module_name', ['gst', 'pay_installation', 'ship_standard_text', 'ship_express_text', 'standard_ship_amt', 'express_ship_amt'])
            ->pluck('content', 'module_name')
            ->toArray();
        $quotation = ['title'=>'','category'=>''];
        if(is_numeric($order->draft_id)){
            $quotationQ = DB::table('drafts')->where('id','=',$order->draft_id)->first();
            $quotation['title']=$quotationQ->title;
            $quotation['category']= $quotationQ->category =='quotation' ? 'Quotation': 'Kit Name';
        }
        

        //$this->pr($settings, 1);
        // $delivery_agents = Shipping::where('status', 1)->select(['id', 'shipping_name'])->get();
        //$this->pr($order->toArray());die;
        // $this->pr($order->toArray(), 1);
        $title = '--';
        //echo $order->draft[0]->category;die;
        if (isset($order->draft) && isset($order->draft[0]) && isset($order->draft[0]->category) && $order->draft[0]->category == 'quotation') {
            $title = $order->draft[0]->title;
        }
         $ordertype=$order->payment_type;
        $shipping = json_decode($order->shipping_info);
        $billing = json_decode($order->billing_info);
        return view('admin.orders.show')->with(['order' => $order, 'title' => $title, 'order_status' => $this->order_status,'ordertype' => $ordertype, 'shipping' => $shipping, 'settings' => $settings, 'billing' => $billing,'quotation'=>$quotation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $order = Order::find($id);


        $order->order_status = $data['order_status'];
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        //here save order status logs
        $orderStatusLOg = new OrderStatusLog();
        $orderStatusLOg->order_id = $order->id;
        $orderStatusLOg->status = $data['order_status'];
        $orderStatusLOg->remarks = $data['remark'];
        $orderStatusLOg->save();
        Session::flash('success', 'Order has been updated!');

        return \redirect()->back();
    }


    public function delete($id)
    {
        $news = Order::where('id', $id)->first();
        $news->delete();
        return back();
    }


    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Order::whereIn('id', explode(",", $ids))->delete();
        return response()->json(['success' => "News Deleted successfully."]);
    }

    public function saveOtherDetails(Request $request, $id)
    {
        $data = $request->all();
        foreach ($data['order_items'] as $item) { 
            OrderItem::where(['id' => $item['id']])->update(['serial_number' => $item['serial_number'], 'mac_address' => $item['mac_address'], 'ip_address' => $item['ip_address']]);
        }
        return redirect()->back()->with('success','Saved successfully');
    }

    public function orderEmail($id)
    {
        $email = OrderEmail::where('id', '=', $id)->first();
        return view('admin.orders.order_email')->with(['email' => $email,'id'=>$id]);
    }

    public function orderEmailSubmit(Request $request,$id)
    {
        $data = $request->all();
        $email = OrderEmail::where('id', '=', $id)->first();
        $email->subject = $data['subject'];
        $email->content = $data['content'];
        $email->save();
        return back();
    }
}
