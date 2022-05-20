<?php

namespace App\Http\Controllers\Store;;


use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		echo "herer"; exit;
    }


	 public function addStore()
    {
        return view('store.add');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 
	'pharmacy_license' => 'required|max:200',
	'gst_no' => 'required|max:200',
	'company_reg_no' => 'required|max:200',
	'address' => 'required|max:200',
	'image' => 'required', 
	'license_image' => 'required',  
	 
     */
    public function store(Request $request)
    {
		$this->validate($request, array(
            'name' => 'required|max:200|unique:stores',
            'pharmacy_license' => 'required|max:200|unique:stores',
            'gst_no' => 'required|max:200|unique:stores',
            'company_reg_no' => 'required|max:200|unique:stores',
			'address' => 'required|max:200',
			'license_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb	
			'store_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb	
        )); 
		 
        $data = $request->all();
	//	echo "<pre>"; print_r($data);exit;
		
		$store = new Store(); 
		if ($request->hasfile('store_image')) {
            $name = 'store_image' . time() . $request->file('store_image')->getClientOriginalName();
			$store->createThumbs(file_get_contents($request->file('store_image')->getRealPath()), $name);
            Storage::disk('store_img')->put($name, file_get_contents($request->file('store_image')->getRealPath()));
            $data['store_image'] = $name;
        }
		if ($request->hasfile('license_image')) {
            $name = 'license_image' . time() . $request->file('license_image')->getClientOriginalName();
			$store->createLicenseThumbs(file_get_contents($request->file('license_image')->getRealPath()), $name);
            Storage::disk('license_image')->put($name, file_get_contents($request->file('license_image')->getRealPath()));
            $data['license_image'] = $name;
        }
		 
             
        $store->name 				= $data['name'];
        $store->pharmacy_license 	= $data['pharmacy_license'];
        $store->gst_no 				= $data['gst_no'];
        $store->company_reg_no 		= $data['company_reg_no'];
        $store->address 			= $data['address']; 
        $store->store_image 		= $data['store_image'];
        $store->license_image 		= $data['license_image'];
	//	echo "<pre>"; print_r($store);exit;
        $store->save(); 
		 
		Session::flash('success', 'Stroe has been created!');
        return redirect()->route('store.addStore');
		 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }
}
