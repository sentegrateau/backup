<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\BlogCategory;
use App\Models\Ratting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
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
        $blogs = Blog::where('status',1)->with('BlogCategory','blogImages');
        if(!empty($req['cat'])){
            $aCategory = BlogCategory::where('slug',$req['cat'])->first();
            $blogs = $blogs->where('blog_category_id',$aCategory->id);
        }
        if(!empty($req['tag'])) {
            $aTag = explode(',',$req['tag']);
            $blogIds = BlogTag::whereIn('tag', $aTag)->groupBy('blog_id')->get()->pluck('blog_id')->toArray();
            $blogs = $blogs->whereIn('id',$blogIds);
        }
        if(!empty($req['filter'])) {
            $blogs = $blogs->where('title', 'like', '%' . $req['filter'] . '%');
        }
        $blogs = $blogs->orderBy('id', 'DESC')->paginate($this->limit);
        $tags = BlogTag::select('*', DB::raw('count(tag) as blogCount'))->groupBy('tag')->having('blogCount', '>' , 0)->get();
        $categories = BlogCategory::withCount('blogs')->having('blogs_count', '>' , 0)->get();

        $recentBlog = Blog::orderBy('id', 'DESC')->with('blogImages')->take(4)->get();


        $headings['title'] = 'Blogs';
        $headings['keywords'] = '';
        $headings['description'] = '';

        return view('front.blogs.index', compact('blogs','tags', 'categories','recentBlog','headings'));
    }

    /**
     * @return mixed
     */
    public function blogDetail(Request $request, $slug)
    {
        $blog = Blog::where('status',1)->where('slug',$slug)->with('BlogCategory','blogImages')->first();
        $tags = BlogTag::select('*', DB::raw('count(tag) as blogCount'))->groupBy('tag')->having('blogCount', '>' , 0)->get();
        $categories = BlogCategory::withCount('blogs')->having('blogs_count', '>' , 0)->get();

        $recentBlog = Blog::orderBy('id', 'DESC')->with('blogImages')->take(4)->get();
        $headings['title'] = $blog->title;
        $headings['keywords'] = '';
        $headings['description'] = '';

        if(!empty($blog->title)){
            $headings['title'] = $blog->title;
        }
        if(!empty($blog->meta_key)){
            $headings['keywords'] = $blog->meta_key;
        }
        if(!empty($blog->meta_description)){

            $headings['description'] = $blog->meta_description;
        }
        return view('front.blogs.single-blog', compact('blog','tags', 'categories','recentBlog','headings'));
    }
}
