<?php

namespace App\Http\Controllers\Admin;

use App\Models\Device;
use App\Models\DeviceFeature;
use App\Models\DeviceParameter;
use App\Models\DeviceParameterValue;
use App\Models\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class DeviceParameterController extends Controller
{

    public $route = 'device-parameters';

    public $title = 'Parameter';


    public function __construct()
    {

        view()->composer('*', function ($view) {
            $title = $this->title;
            $route = $this->route;

            $view->with(compact('title', 'route'));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogCategory = DeviceParameter::paginate(15);
        return view('admin.' . $this->route . '.index')->with(['blogCategory' => $blogCategory]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $device_names = Device::where('status', '1')->select('name', 'id')->get();
        return view('admin.' . $this->route . '.add')->with(['device_names' => $device_names]);
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
            'device_id' => 'required',
            'param_id' => 'required',
            'param_name' => 'required|max:200',
            'param_byte' => 'required|max:200',
            'value.*' => 'required',
        ));


        $data = $request->all();
        $blogCategory = new DeviceParameter();
        $blogCategory->param_id = $data['param_id'];
        $blogCategory->param_name = $data['param_name'];
        $blogCategory->param_byte = $data['param_byte'];
        $blogCategory->device_id = $data['device_id'];
        $blogCategory->save();

        foreach ($data['value'] as $val) {
            $deviceVal = new DeviceParameterValue();
            $deviceVal->device_parameter_id = $blogCategory->id;
            $deviceVal->value = $val;
            $deviceVal->save();
        }
        Session::flash('success', $this->title . ' has been created!');
        return redirect()->route($this->route . '.index');

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
        $device_names = Device::where('status', '1')->select('name', 'id')->get();
        $blogCategory = DeviceFeature::where('id', '=', $id)->first();
        return view('admin.' . $this->route . '.edit')->with(['blogCategory' => $blogCategory, 'device_names' => $device_names]);
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
            'name' => 'required|unique:device_features,name,' . $id . '|max:255',
            'device_id' => 'required',
            'type' => 'required',
        ));

        $data = $request->all();

        $blogcategory = DeviceFeature::find($id);
        $blogcategory->name = $data['name'];
        $blogcategory->device_id = $data['device_id'];
        $blogcategory->label = (!empty($data['label'])) ? $data['label'] : null;
        $blogcategory->type = $data['type'];
        $blogcategory->save();
        Session::flash('success', $this->title . ' has been updated!');
        return redirect()->route($this->route . '.index');
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
        return redirect()->route($this->route . '.index');
    }

    public function deleteMethod($id)
    {
        $blogCategory = DeviceFeature::where('id', $id)->first();
        $blogCategory->delete();
    }

    /* public function activeDeactivate(Request $request, $id)
     {
         $data = $request->all();
         $mt = DeviceFeature::where('id', $id)->first();
         $mt->update(['status' => !($mt->status)]);
         return redirect()->route('blog-category.index')->with('success', "Blog Category" . (($mt->status) ? 'Activated' : 'Deactivated') . " Successfully.");
     }

     public function updateFeatured(Request $request, $id){
         $data = $request->all();
         $update = BlogCategory::where('id', $id)->update(['featured' => $data['featured']]);
         $res = ['error' => false, 'message' => 'Featured Updated.'];
         echo json_encode($res);die;
     }*/
}
