<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use App\Models\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogCategory = BlogCategory::paginate(15);
        return view('admin.blog-category.index')->with(['blogCategory' => $blogCategory]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blog-category.add');
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
            'name' => 'required|max:200',
        ));


        $data = $request->all();
        $slug = new Slug();
        $blogCategory = new BlogCategory();
        $blogCategory->name = $data['name'];
        $blogCategory->slug = $slug->createSlug($data['name'], 'blog_categories');
        $blogCategory->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blogCategory->featured = (!empty($data['featured']) && $data['featured'] == 'on') ? true : false;
        $blogCategory->save();
        Session::flash('success', 'Category has been created!');
        return redirect()->route('blog-category.index');

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
        $blogCategory = BlogCategory::where('id', '=', $id)->first();
        return view('admin.blog-category.edit')->with(['blogCategory' => $blogCategory]);
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
            'name' => 'required|unique:blog_categories|max:255',
        ));

        $data = $request->all();
        $slug = new Slug();
        $blogcategory = BlogCategory::find($id);
        $blogcategory->name = $data['name'];
        if ($blogcategory->slug != str_slug($data['name']))
            $blogcategory->slug = $slug->createSlug($data['name'], 'blog_categories');
        $blogcategory->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blogcategory->featured = (!empty($data['featured']) && $data['featured'] == 'on') ? true : false;
        $blogcategory->save();
        Session::flash('success', 'Blog category has been updated!');
        return redirect()->route('blog-category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->deleteMethod($id);
        return redirect()->route('blog-category.index');
    }

    public function deleteMethod($id)
    {
        $blogCategory = BlogCategory::where('id', $id)->first();
        $blogCategory->delete();
    }

    public function activeDeactivate(Request $request, $id)
    {
        $data = $request->all();
        $mt = BlogCategory::where('id', $id)->first();
        $mt->update(['status' => !($mt->status)]);
        return redirect()->route('blog-category.index')->with('success', "Blog Category" . (($mt->status) ? 'Activated' : 'Deactivated') . " Successfully.");
    }

    public function updateFeatured(Request $request, $id){
        $data = $request->all();
        $update = BlogCategory::where('id', $id)->update(['featured' => $data['featured']]);
        $res = ['error' => false, 'message' => 'Featured Updated.'];
        echo json_encode($res);die;
    }
}
