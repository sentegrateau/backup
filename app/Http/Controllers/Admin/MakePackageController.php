<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Models\DraftItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MakePackageController extends Controller
{
    public $route = 'make-packages';

    public $title = 'Standard Kit';

    public function __construct()
    {
        view()->composer('*', function ($view) {
            $title = $this->title;
            $routeName = $this->route;
            $view->with(compact('title', 'routeName'));
        });
    }


    public function index(Request $request)
    {
        $pkgs = Draft::where('type', 'admin')->paginate(20);
        return view('admin.' . $this->route . '.index')->with(['pkgs' => $pkgs]);
    }

    public function create(Request $request)
    {
        $kit_name = '';
        $kit_name2 ='';
        // $packages = Package::orderBy('order', 'asc')->get();
        //return view('admin.' . $this->route . '.standard-kit', compact('packages'));
        return view('admin.' . $this->route . '.add', compact('kit_name','kit_name2'));
    }

    public function edit($id)
    {
        $draft = Draft::where('id', $id)->first();
        $kit_name = $draft->title;
        $kit_name2 = $draft->kit_name;
        return view('admin.' . $this->route . '.add', compact('kit_name', 'id', 'draft', 'kit_name2'));
    }

    public function store(Request $request)
    {
        $this->validate($request, array(
            'kit_name' => 'required',
            'kit_name2' => 'required',
        ));
        $data = $request->all();
        $draft = new Draft();
        $draft->title = $data['kit_name'];
        $draft->kit_name = $data['kit_name2'];
        $draft->type = 'admin';
        $draft->user_id = 1;
        $draft->partner_id = 1;
        $draft->category = 'draft';
        $draft->save();

        return redirect()->route('make-packages.makeKit', $draft->id)->with('success', 'Kit Saved');

    }

    public function makeKit(Request $request, $id)
    {
        $kit = Draft::where('id', $id)->first();
        $draftItems = DraftItem::where('draft_id', $id)->with(['device' => function ($q) {
            return $q->select(DB::raw('id,name,price'));
        }, 'package' => function ($q) {
            return $q->select(DB::raw('id,name'));
        }, 'room' => function ($q) {
            return $q->select(DB::raw('id,name'));
        }])->get();
        $items = [];
        foreach ($draftItems as $item) {
            if (!empty($item->device)) {
                $items[$item->package_id . '-' . $item->room_id . '-' . $item->device_id] = [
                    'name' => ['device' => $item->device->name, 'pkg_name' => $item->package->name, 'room_name' => $item->room->name],
                    'amt' => $item->device->price,
                    'package_id' => $item->package_id,
                    'room_id' => $item->room_id,
                    'device_id' => $item->device_id,
                    'quantity' => $item->quantity
                ];
            }
        }


        return view('admin.' . $this->route . '.standard-kit', compact('kit', 'draftItems', 'items'));
    }

    /* public function edit($id)
     {
         $packages = Package::orderBy('order', 'asc')->get();
         $draft = Draft::where('id', $id)->first();
         $draftItems = DraftItem::where('draft_id', $draft->id)->select(DB::raw('draft_id,package_id,room_id,device_id,quantity'))->get();

         $device_ids = [];
         if (!empty($draftItems)) {
             $device_ids = array_column($draftItems->toArray(), 'device_id');
         }
         $devices = Device::whereIn('id', $device_ids)->get();
         $device_name_price = [];
         foreach ($devices as $item) {
             $device_name_price[$item->id] = ['name' => $item->name, 'price' => $item->price];
         }
         $addToCartItems = [];
         foreach ($draftItems as $item) {
             $addToCartItems[$item->package_id . '-' . $item->room_id . '-' . $item->device_id] = $item;
             $addToCartItemsNamePrice[$item->package_id . '-' . $item->room_id . '-' . $item->device_id] = $device_name_price[$item->device_id];
         }
         return view('admin.' . $this->route . '.add', compact('id', 'packages', 'draft', 'draftItems', 'addToCartItems', 'addToCartItemsNamePrice'));
     }*/

    public function delete($id)
    {
        $news = Draft::where('id', $id)->first();
        DraftItem::where('draft_id', $id)->delete();
        $news->delete();
        return back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'kit_name' => 'required',
            'kit_name2' => 'required',
        ));
        $data = $request->all();
        $draft = Draft::find($id);
        $draft->title = $data['kit_name'];
        $draft->kit_name = $data['kit_name2'];
        $draft->save();
        if (isset($data['final_submit']))
            return redirect()->back()->with('success', 'Kit Updated');
        else
            return redirect()->route('make-packages.makeKit', $draft->id)->with('success', 'Kit Updated');
    }

    public function order(Request $request, $id)
    {
        Draft::where('id', $id)->update(['order' => $request->order]);

        return redirect()->back()->with('success', 'Order Saved');
    }

}
