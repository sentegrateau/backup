<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogImage;
use App\Models\BlogTag;
use App\Models\CaseStudy;
use App\Models\CaseStudyImage;
use App\Models\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CaseStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $case_study = CaseStudy::with('images')->paginate(15);
        //$this->pr($case_study->toArray());die;
        return view('admin.case_study.index')->with(['case_study' => $case_study]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.case_study.add');
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
        $blog = new CaseStudy();
        $blog->title = $data['title'];
        $blog->meta_key = $data['meta_key'];
        $blog->meta_description = $data['meta_description'];
        $blog->slug = $slug->createSlug($data['title'], 'blogs');
        $blog->content = $data['content'];
        $blog->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blog->save();
        $blogid = $blog->id;
        $blogImg = [];
        if (!empty($request->file('blog_img'))) {
            $i = 0;
            $blogImgThumb = new CaseStudyImage();
            foreach ($request->file('blog_img') as $file) {
                $name = time() . $file['img']->getClientOriginalName();
                $blogImgThumb->createThumbs(file_get_contents($file['img']->getRealPath()), $name);
                Storage::disk('case_study_uploads')->put($name, file_get_contents($file['img']->getRealPath()));
                $blogImg[] = ['case_study_id' => $blog->id, 'image' => $name, 'order' => $data['blog_img'][$i]['order'], 'alt_text' => $data['blog_img'][$i]['alt_text'], 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')];
                $i++;
            }
            CaseStudyImage::insert($blogImg);
        }
        Session::flash('success', 'Case study has been created!');
        return redirect()->route('case_study.index');
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
        $case_study = CaseStudy::where('id', '=', $id)->with('images')->first();
        return view('admin.case_study.edit')->with(['case_study' => $case_study]);
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
        $blogImgRecord = CaseStudyImage::where('case_study_id', $id)->get()->toArray();
        $slug = new Slug();
        $blog = CaseStudy::find($id);
        $blog->title = $data['title'];
        if ($blog->slug != str_slug($data['title']))
            $blog->slug = $slug->createSlug($data['title'], 'case_studies');

        $blog->content = $data['content'];
        $blog->meta_key = $data['meta_key'];
        $blog->meta_description = $data['meta_description'];
        $blog->status = (!empty($data['status']) && $data['status'] == 'on') ? true : false;
        $blog->save();

        $blogImg = [];
        if (!empty($data['blog_img'])) {
            foreach ($data['blog_img'] as $i => $file) {
                if (!empty($file['img'])) {
                    $blogImgThumb = new CaseStudyImage();
                    $name = time() . $file['img']->getClientOriginalName();
                    $blogImgThumb->createThumbs(file_get_contents($file['img']->getRealPath()), $name);
                    Storage::disk('case_study_uploads')->put($name, file_get_contents($file['img']->getRealPath()));
                    $blogImg[] = ['case_study_id' => $blog->id, 'image' => $name, 'order' => $data['blog_img'][$i]['order'], 'alt_text' => $data['blog_img'][$i]['alt_text'], 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')];
                } else {
                    $updateBlogImg = CaseStudyImage::find($blogImgRecord[$i]['id']);
                    $updateBlogImg->case_study_id = $blog->id;
                    $updateBlogImg->order = $data['blog_img'][$i]['order'];
                    $updateBlogImg->alt_text = $data['blog_img'][$i]['alt_text'];
                    $updateBlogImg->save();
                }
            }
            CaseStudyImage::insert($blogImg);
        }
        Session::flash('success', 'Case Study has been updated!');
        return redirect()->route('case_study.index');
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
        return redirect()->route('case_study.index');
    }

    public function deleteImage(Request $request)
    {
        $data = $request->all();
        $blogImg = CaseStudyImage::find($data)->first();
        if (!empty($blogImg)) {
            File::delete(public_path('case_study/' . $blogImg->image));
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
        $blog = CaseStudy::where('id', $id)->first();
        $blogImages = CaseStudyImage::where('case_study_id', $blog->id)->get()->toArray();
        foreach ($blogImages as $img) {
            $blogImg = CaseStudyImage::where('id', $img['id'])->first();
            File::delete(public_path('case_study/' . $img['image']));
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
        return response()->json(['success' => "Case Studies Deleted successfully."]);
    }
}
