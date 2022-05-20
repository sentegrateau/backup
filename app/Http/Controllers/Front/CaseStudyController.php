<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Ratting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaseStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $limit = 10;
    public function index(Request $request)
    {
        $req = $request->all();
        $blogs = CaseStudy::where('status',1)->with('images');
        if(!empty($req['filter'])) {
            $blogs = $blogs->where('title', 'like', '%' . $req['filter'] . '%');
        }
        $blogs = $blogs->orderBy('id', 'DESC')->paginate($this->limit);
        $recentBlog = CaseStudy::orderBy('id', 'DESC')->with('images')->take(4)->get();
        $headings['title'] = 'Case Studies';
        $headings['keywords'] = '';
        $headings['description'] = '';
        return view('front.case-study.index', compact('blogs','recentBlog','headings'));
    }

    /**
     * @return mixed
     */
    public function detail(Request $request, $slug)
    {
        $blog = CaseStudy::where('status',1)->where('slug',$slug)->with('images')->first();
        $recentBlog = CaseStudy::orderBy('id', 'DESC')->with('images')->take(4)->get();
        $headings['title'] = $blog->title;
        $headings['keywords'] = '';
        $headings['description'] = '';
        if(!empty($blog->meta_key)){
            $headings['keywords'] = $blog->meta_key;
        }
        if(!empty($blog->meta_description)){
            $headings['description'] = $blog->meta_description;
        }
        return view('front.case-study.detail', compact('blog','recentBlog','headings'));
    }
}
