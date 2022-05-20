<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Package;
use App\Models\Package_Room;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class DeviceController extends Controller
{
    public $routeName = 'device';


    public function __construct()
    {
        view()->composer('*', function ($view) {
            $routeName = $this->routeName;
            $view->with(compact('routeName'));
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = '';
        $query = $request->query();
        $queryVal = $request->query();
        $devices = Device::query();
        //$devices = Device::orderBy('id', 'desc');
        if (!empty($queryVal['search'])) {
            $search = $queryVal['search'];
            $devices = $devices->where(function ($query) use ($queryVal) {
                $query->where('name', 'like', '%' . $queryVal['search'] . '%')->orWhere('brand', 'like', '%' . $queryVal['search'] . '%')->orWhere('model', 'like', '%' . $queryVal['search'] . '%');
            });
        }


       if (!empty($query) && !empty($query['sort']) && !empty($query['sortName'])) {
            if ($query['sortName']) {
                $devices = $devices->orderBy($query['sortName'], ($query['sort'] == 'asc') ? 'asc' : 'desc');
            }
        }
         else{
            $devices = $devices->orderBy('id', 'desc');
        }

        $devices = $devices->paginate(15);

        return view('admin.device.index')->with(['devices' => $devices, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.device.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|max:200',
            'description' => 'required|max:2000',
            'brand' => 'required|max:200',
            'supplier' => 'required|max:200',
            // 'model' => 'required|max:200',
            /*'price' => 'required|number',
            'discount' => 'required|number',*/
            //"image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ));
        $data = $request->all();
        $device = new Device();
        $device->name = $data['name'];
        $device->brand = $data['brand'];
        $device->supplier = $data['supplier'];
        $device->model = (!empty($data['model'])) ? $data['model'] : null;
        $device->price = $data['price'];
        $device->discount = $data['discount'];
        $device->partner_id = 1;
        $device->description = $data['description'];

        if (!empty($data['image'])) {
            $image = $request->file('image');
            $teaser_image = time() . '.' . $image->getClientOriginalExtension();
            $destination_path = public_path('/images');
            $image->move($destination_path, $teaser_image);
            $device->image = $teaser_image;
        }
        $device->save();
        Session::flash('success', 'Device has been created!');
        //return redirect()->route('device.index');
        return redirect()->route('device.addPackage', $device->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device = Device::where('id', '=', $id)->first();
        return view('admin.device.edit')->with(['device' => $device]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|max:200',
            'description' => 'required|max:2000',
            'brand' => 'required|max:200',
            'supplier' => 'required|max:200',
            //'model' => 'required|max:200',
            /*'price' => 'required|number',
            'discount' => 'required|number',*/
        ));
        $data = $request->all();
        $device = Device::find($id);
        $device->name = $data['name'];
        $device->brand = $data['brand'];
        $device->supplier = $data['supplier'];
        $device->model = (!empty($data['model'])) ? $data['model'] : null;
        $device->price = $data['price'];
        $device->discount = $data['discount'];
        $device->partner_id = 1;
        $device->description = $data['description'];
        $device->device_id = $data['device_id'];

        if (!empty($data['image'])) {
            $image = $request->file('image');
            $teaser_image = time() . '.' . $image->getClientOriginalExtension();
            $destination_path = public_path('/images');
            $image->move($destination_path, $teaser_image);
            File::delete(public_path('images/' . $device->image));
            $device->image = $teaser_image;
        }

        $device->save();
        Session::flash('success', 'Device has been updated!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->deleteMethod($id);
        return redirect()->route('device.index');
    }

    public function deleteMethod($id)
    {
        $device = Device::where('id', $id)->first();
        $device->delete();
    }

    public function activeDeactivate(Request $request, $id)
    {
        $data = $request->all();
        $mt = Device::where('id', $id)->first();
        $mt->update(['status' => ($mt->status == '1' ? '0' : '1')]);
        return redirect()->back()->with('success', "Device " . (($mt->status) ? 'Activated' : 'Deactivated') . " Successfully.");
    }


    public function addPackage(Request $request, $device_id)
    {
        $device = Device::where('id', $device_id)->first();
        $packages = Package::orderBy('order', 'asc')->get();
        $rooms = Room::all();
        $packageRoom = Package_Room::where(['device_id' => $device_id])->get()->toArray();

        $pkg_ids = [];
        $room_ids = [];
        $pkgs = [];
        foreach ($packageRoom as $key => $pkgRoom) {
            $pkg_ids[$pkgRoom['package_id']] = $pkgRoom['package_id'];
            $room_ids[$pkgRoom['room_id']] = $pkgRoom['room_id'];
            $pkgs[$pkgRoom['package_id'] . '-' . $pkgRoom['room_id'] . '-' . $pkgRoom['device_id']] = ['min_qty' => $pkgRoom['min_qty'], 'max_qty' => $pkgRoom['max_qty']];
        }
        $selected_pkg_id = array_values($pkg_ids);
        $selected_room_id = array_values($room_ids);
        //print_r()
        if (!empty(old())) {
            $pkgs = old('package');
            $selected_pkg_id = old('packages');
            $selected_room_id = old('rooms');
        }


        return view('admin.device.add-package')->with(['device_id' => $device_id, 'device' => $device, 'rooms' => $rooms, 'packages' => $packages, 'selected_pkg_id' => $selected_pkg_id, 'selected_room_id' => $selected_room_id, 'selected_pkgs' => ($pkgs)]);
    }

    public function getMaxMinQty(Request $request)
    {
        $render = [];
        $data = $request->all();

        if (!empty($data['package']) && !empty($data['room'])) {
            $packageName = Package::pluck('name', 'id');
            $roomName = Room::pluck('name', 'id');
            $combinations = $this->combinations(array($data['package'], $data['room'], [$data['device_id']]));
            $selected_pkg = !empty($data['selected_pkgs']) ? $data['selected_pkgs'] : [];
            $render = view('admin.device.qty')->with(['packageName' => $packageName, 'roomName' => $roomName, 'device_id' => $data['device_id'], 'rooms' => $data['room'], 'packages' => $data['package'], 'combinations' => $combinations, 'selected_pkgs' => $selected_pkg])->render();
        }
        return ['html' => $render];
    }

    public function combinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }

    public function saveCombination(Request $request, $device_id)
    {
        $this->validate($request, array(
            'package.*.min_qty' => 'required|numeric',
            'package.*.max_qty' => 'required|numeric|min:0|gt:package.*.min_qty',
        ), [
            'package.*.min_qty.required' => 'Package min qty is required',
            'package.*.max_qty.required' => 'Package max qty is required',
            'package.*.min_qty.numeric' => 'Package max qty is numeric field',
            'package.*.max_qty.numeric' => 'Package max qty is numeric field',
            'package.*.max_qty.gt' => 'Package max qty should be greater than min qty field'
        ]);
        $data = $request->all();

        try {
            DB::beginTransaction();
            if (!empty($data['package'])) {
                Package_Room::where('device_id', $device_id)->delete();
                foreach ($data['package'] as $key => $pkg) {
                    $ids = explode('-', $key);
                    $packageRoom = Package_Room::firstOrNew(['package_id' => $ids[0], 'room_id' => $ids[1], 'device_id' => $ids[2]]);
                    $packageRoom->min_qty = $pkg['min_qty'];
                    $packageRoom->max_qty = $pkg['max_qty'];
                    $packageRoom->save();
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Package saved');
    }

}
