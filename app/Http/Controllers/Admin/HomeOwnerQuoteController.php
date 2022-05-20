<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHomeOwnerRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HomeOwnerQuoteController extends Controller
{

    public $order_status = [-1 => 'Cancelled', 0 => 'Pending', 1 => 'Processing', 2 => 'Dispatched', 3 => 'Completed'];



    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $orders = OrderHomeOwnerRoom::with(['user'])->orderBy('id', 'desc')->groupBy(['user_id','created_at'])->paginate(20);
        return view('admin.home-owner-quotes.index')->with(['orders' => $orders]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $order = OrderHomeOwnerRoom::where('user_id',$id)->with(['user'])->orderBy('id', 'desc')->get();
        // $delivery_agents = Shipping::where('status', 1)->select(['id', 'shipping_name'])->get();
        //$this->pr($order->toArray());die;
       // $this->pr($order->toArray(), 1);
        return view('admin.home-owner-quotes.show')->with(['order' => $order]);
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
}
