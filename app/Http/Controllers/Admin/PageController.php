<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Slug;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $type = ['about-portfolio' => 'About Portfolio', 'product-&-services' => 'Product And Services', 'about-sentegrate' => 'About Sentegrate', 'why-sentegrate' => 'Why Sentegrate'];

    public function index(Request $request)
    {

        $page = Page::paginate(15);

        return view('admin.page.index')->with(['page' => $page]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aType = $this->type;
        return view('admin.page.add', compact('aType'));
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
            'description' => 'required',
        ), ['description.required' => 'Content field is required']);

        $data = $request->all();
        $slug = new Slug();
        $blog = new Page();
        $blog->name = $data['name'];
        $blog->meta_key = $data['meta_key'];
        $blog->type = $data['type'];
        $blog->meta_description = $data['meta_description'];
        $blog->slug = $slug->createSlug($data['name'], 'pages');
        $blog->description = $data['description'];
        $blog->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blog->save();
        Session::flash('success', 'Page has been created!');
        return redirect()->route('page.index');
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
        $page = Page::where('id', '=', $id)->first();
        return view('admin.page.edit')->with(['page' => $page, 'aType' => $aType]);
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
            'description' => 'required',
        ));

        $data = $request->all();
        // pr($blogImgRecord, 1);
        $slug = new Slug();
        $blog = Page::find($id);
        $blog->name = $data['name'];
        if ($blog->slug != str_slug($data['name']))
            $blog->slug = $slug->createSlug($data['name'], 'pages');
        $blog->description = $data['description'];
        $blog->meta_key = $data['meta_key'];
        $blog->type = $data['type'];
        $blog->meta_description = $data['meta_description'];
        $blog->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blog->save();

        Session::flash('success', 'Page has been updated!');

        return redirect()->route('page.index');
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
        return redirect()->route('blog.index');
    }

    public function deleteImage(Request $request)
    {
        $data = $request->all();
        $blogImg = BlogImage::find($data)->first();
        if (!empty($blogImg)) {
            File::delete(public_path('blog/' . $blogImg->image));
            $blogImg->delete();
            $res = ['error' => false, 'message' => 'Record deleted.'];
        } else {
            $res = ['error' => true, 'message' => 'Record not deleted.'];
        }
        echo json_encode($res);
        die;
    }

    public function deleteMethod($id)
    {
        $blog = Blog::where('id', $id)->first();
        if(!empty($blog) && isset($blog->id)){
            $blogImages = BlogImage::where('blog_id', $blog->id)->get()->toArray();
            foreach ($blogImages as $img) {
                $blogImg = BlogImage::where('id', $img['id'])->first();
                File::delete(public_path('blog/' . $img['image']));
                $blogImg->delete();
            }
            $blog->delete();
        }
    }


    public function deleteAll(Request $request)
    {

        $data = $request->all();

        $ids = explode(',', $data['ids']);
        foreach ($ids as $id) {
            $this->deleteMethod($id);
        }
        return response()->json(['success' => "Blog Deleted successfully."]);
    }
}
