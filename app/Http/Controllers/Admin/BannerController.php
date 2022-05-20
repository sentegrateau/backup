<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class BannerController extends Controller
{

    public $title = 'Banner';


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $banners = Banner::paginate(15);

        return view('admin.banner.index')->with(['banners' => $banners, 'title' => $this->title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banner.add')->with(['title' => $this->title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, array(
            'title' => 'required|max:500',
            'url' => 'required',
            'ordr' => 'required',
            'expiry' => 'required',
           /// 'sub_title' => 'required',
            'image' => 'mimes:jpeg,jpg,png|required',
        ));

        $data = $request->all();

        $banner = new Banner();
        $banner->title = $data['title'];
        $banner->url = $data['url'];
        $banner->ordr = $data['ordr'];
        $banner->expiry = $data['expiry'];
        //$banner->sub_title = $data['sub_title'];
        $banner->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;

        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $name = 'banner_img_' . time() . $image->getClientOriginalName();
            Storage::disk('banner_uploads')->put($name, file_get_contents($image->getRealPath()));
            $banner->image = $name;
        }

        $banner->save();

        Session::flash('success', $this->title . ' has been created!');

        return redirect()->route('banner.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banner = Banner::where('id', '=', $id)->first();
        return view('admin.banner.edit')->with(['banner' => $banner, 'title' => $this->title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'title' => 'required|max:500',
            'url' => 'required',
            'ordr' => 'required',
            'expiry' => 'required',
          //  'sub_title' => 'required',
            'image' => 'mimes:jpeg,jpg,png',
        ));

        $data = $request->all();

        $banner = Banner::find($id);
        $banner->title = $data['title'];
        $banner->url = $data['url'];
        $banner->ordr = $data['ordr'];
        $banner->expiry = $data['expiry'];
       // $banner->sub_title = $data['sub_title'];
        $banner->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        if ($request->hasfile('image')) {
            Storage::disk('banner_uploads')->delete($banner->image);
            $image = $request->file('image');
            $name = 'banner_img_' . time() . $image->getClientOriginalName();
            Storage::disk('banner_uploads')->put($name, file_get_contents($image->getRealPath()));
            $banner->image = $name;
        }
        $banner->save();

        Session::flash('success', 'Banner has been updated!');

        return redirect()->route('banner.index');
    }

    public function delete($id)
    {
        $banner = Banner::where('id', $id)->first();
        Storage::disk('banner_uploads')->delete($banner->image);
        $banner->delete();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }


    public function deleteMethod($id)
    {
        $banner = Banner::where('id', $id)->first();
        Storage::disk('banner_uploads')->delete($banner->image);
        $banner->delete();
    }


    public function deleteAll(Request $request)
    {
        $data = $request->all();
        $ids = explode(',', $data['ids']);
        foreach ($ids as $id) {
            $this->deleteMethod($id);
        }
        return response()->json(['success' => "Banner Deleted successfully."]);
    }
}
