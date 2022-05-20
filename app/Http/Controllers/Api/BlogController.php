<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogImage;
use App\Models\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog = Blog::where('past_per', 0)->with('blogImages')->paginate(15);
        return view('admin.blog.index')->with(['blog' => $blog]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blog.add');
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
            'title' => 'required|max:200',
            'content' => 'required',
            'blog_img.*.img' => 'required',
            'blog_img.*.order' => 'required',
        ), ['blog_img.*.required' => 'Order field is required']);


        $data = $request->all();
        $slug = new Slug();
        $blog = new Blog();
        $blog->title = $data['title'];
        $blog->slug = $slug->createSlug($data['title'], 'blogs');
        $blog->user_id = 1;
        $blog->content = $data['content'];
        $blog->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blog->save();

        $blogImg = [];
        if (!empty($request->file('blog_img'))) {
            $i = 0;
            $blogImgThumb = new BlogImage();
            foreach ($request->file('blog_img') as $file) {

                $name = time() . $file['img']->getClientOriginalName();
                $blogImgThumb->createThumbs(file_get_contents($file['img']->getRealPath()), $name);
                Storage::disk('blog_uploads')->put($name, file_get_contents($file['img']->getRealPath()));
                $blogImg[] = ['blog_id' => $blog->id, 'image' => $name, 'order' => $data['blog_img'][$i]['order'], 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')];
                $i++;
            }
            BlogImage::insert($blogImg);
        }


        Session::flash('success', 'Blog has been created!');

        return redirect()->route('blog.index');

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
        $blog = Blog::where('id', '=', $id)->with('blogImages')->first();
        //pr($blog, 1);
        return view('admin.blog.edit')->with(['blog' => $blog]);
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
            'title' => 'required|max:200',
            'content' => 'required',
        ));

        $data = $request->all();
        $blogImgRecord = BlogImage::where('blog_id', $id)->get()->toArray();
        // pr($blogImgRecord, 1);
        $slug = new Slug();
        $blog = Blog::find($id);
        $blog->title = $data['title'];
        if ($blog->slug != str_slug($data['title']))
            $blog->slug = $slug->createSlug($data['title'], 'blogs');
        $blog->user_id = 1;
        $blog->content = $data['content'];
        $blog->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blog->save();

        $blogImg = [];

        if (!empty($data['blog_img'])) {


            foreach ($data['blog_img'] as $i => $file) {
                if (!empty($file['img'])) {
                    $blogImgThumb = new BlogImage();
                    $name = time() . $file['img']->getClientOriginalName();
                    $blogImgThumb->createThumbs(file_get_contents($file['img']->getRealPath()), $name);
                    Storage::disk('blog_uploads')->put($name, file_get_contents($file['img']->getRealPath()));
                    $blogImg[] = ['blog_id' => $blog->id, 'image' => $name, 'order' => $data['blog_img'][$i]['order'], 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')];
                } else {
                    $updateBlogImg = BlogImage::find($blogImgRecord[$i]['id']);
                    $updateBlogImg->blog_id = $blog->id;
                    $updateBlogImg->order = $data['blog_img'][$i]['order'];
                    $updateBlogImg->save();
                }
            }
            BlogImage::insert($blogImg);
        }

        Session::flash('success', 'Blog has been updated!');

        return redirect()->route('blog.index');
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
        $blogImages = BlogImage::where('blog_id', $blog->id)->get()->toArray();
        foreach ($blogImages as $img) {
            $blogImg = BlogImage::where('id', $img['id'])->first();
            File::delete(public_path('blog/' . $img['image']));
            $blogImg->delete();
        }
        $blog->delete();
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
