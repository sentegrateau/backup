<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PackageController extends Controller
{
    public $routeName = 'package';


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
        $query = $request->query();
        $packages = Package::query();

        if (!empty($query) && !empty($query['sort']) && !empty($query['sortName'])) {
            if ($query['sortName']) {
                $packages = $packages->orderBy($query['sortName'], ($query['sort'] == 'asc') ? 'asc' : 'desc');
            }
        }
        $packages = $packages->paginate(15);
        return view('admin.package.index')->with(['packages' => $packages]);
    }

    public function order()
    {
        $packages = Package::query();

        $packages = $packages->orderBy('order', 'asc')->get();
        return view('admin.package.order')->with(['packages' => $packages]);
    }

    public function orderSave(Request $request)
    {
        $this->validate($request, array(
            'order' => 'required',
        ));
        $data = $request->all();

        $orderWithId = json_decode($data['order']);

        foreach ($orderWithId as $key=>$order) {
            Package::where('id', $order->id)->update(['order' => $key+1]);
        }
        return redirect()->back()->with('success', 'Order Saved');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package.add');
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
            'name' => 'required|max:200|unique:packages,name,NULL,id,deleted_at,NULL',
            'description' => 'required|max:2000',
        ));
        $data = $request->all();
        $package = new Package();
        $package->name = $data['name'];
        $package->partner_id = 1;
        $package->description = $data['description'];
        $package->save();
        Session::flash('success', 'Package has been created!');
        return redirect()->route('package.index');

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
        $package = Package::where('id', '=', $id)->first();
        return view('admin.package.edit')->with(['package' => $package]);
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
            'name' => 'required|max:255|unique:packages,name,' . $id . ',id,deleted_at,NULL',
            'description' => 'required|max:2000',
        ));

        $data = $request->all();
        $package = Package::find($id);
        $package->name = $data['name'];
        $package->description = $data['description'];
        $package->save();
        Session::flash('success', 'Package has been updated!');
        return redirect()->route('package.index');
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
        return redirect()->route('package.index');
    }

    public function deleteMethod($id)
    {
        $package = Package::where('id', $id)->first();
        $package->delete();
    }

    public function activeDeactivate(Request $request, $id)
    {
        $data = $request->all();
        $mt = Package::where('id', $id)->first();
        $mt->update(['status' => ($mt->status == '1' ? '0' : '1')]);
        return redirect()->route('package.index')->with('success', "Package " . (($mt->status) ? 'Activated' : 'Deactivated') . " Successfully.");
    }
}
