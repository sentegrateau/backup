<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogTag;
use App\Models\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use DB;

class BlogTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $req = $request->all();
        $tag = BlogTag::select('tag', DB::raw('count(tag) as blogCount'));
        if(!empty($req['tag'])){
            $tag = $tag->where('tag', 'like', '%' . $req['tag'] . '%');
        }
        $tag = $tag->groupBy('tag')->paginate(15);
        return view('admin.blog-tag.index')->with(['tag' => $tag]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blog-tag.add');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        $tag = BlogTag::where('tag', '=', $name)->first();
        return view('admin.blog-tag.edit')->with(['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $input = $request->all();
        $update = BlogTag::where('tag', $name)->update(['tag' => $input['tag_name']]);
        return redirect()->route('blog-tag.index')->with('success', 'Tag Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  varchar $name
     * @return \Illuminate\Http\Response
     */

    public function delete($name)
    {
        BlogTag::where('tag', $name)->delete();
        return redirect()->route('blog-tag.index');
    }
}
