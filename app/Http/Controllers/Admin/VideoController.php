<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $type = ['discover-smart-homes'=>'Discover Smart Homes','sentegrate-smart-home-products'=>'Sentegrate Smart Home Products'];

    public function index()
    {
        $video = Video::paginate(15);
        return view('admin.videos.index')->with(['video' => $video]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aType = $this->type;
        return view('admin.videos.add', compact('aType'));
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
            'link' => 'required',
        ));
        $data = $request->all();
        $slug = new Slug();
        $video = new Video();
        $video->name = $data['name'];
        $video->type = $data['type'];
        $video->link = $data['link'];
        $video->slug = $slug->createSlug($data['name'], 'videos');
        $video->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $video->save();
        Session::flash('success', 'Video has been created!');
        return redirect()->route('video.index');
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
        $aType = $this->type;
        $video = Video::where('id', '=', $id)->first();
        return view('admin.videos.edit')->with(['video' => $video,'aType'=>$aType]);
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
            'link' => 'required',
        ));

        $data = $request->all();
        $slug = new Slug();
        $video = Video::find($id);
        $video->name = $data['name'];
        if ($video->slug != str_slug($data['name']))
            $video->slug = $slug->createSlug($data['name'], 'videos');
        $video->type = $data['type'];
        $video->link = $data['link'];
        $video->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $video->save();

        Session::flash('success', 'Video has been updated!');

        return redirect()->route('video.index');
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
        return redirect()->route('video.index');
    }

    public function deleteMethod($id)
    {
        $video = Video::where('id', $id)->first();
        $video->delete();
    }

    public function deleteAll(Request $request)
    {

        $data = $request->all();

        $ids = explode(',', $data['ids']);
        foreach ($ids as $id) {
            $this->deleteMethod($id);
        }
        return response()->json(['success' => "Videos Deleted successfully."]);
    }
}
