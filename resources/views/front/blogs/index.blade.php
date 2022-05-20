@extends('layouts.app')
@section('content')

<div class="page-content">
    <div class="mini-header our-blogs-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mini-header-content">
                    <h1>Our Blogs</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5 mb-3">
        <div class="row">
            <div class="col-lg-9">
                @foreach($blogs as $key=>$blg)
                <article class="entry entry-list">
                    <div class="row">
                        <div class="col-md-4">
                            <figure class="entry-media">
                                <a href="{{route('blogs.detail', $blg->slug)}}">
                                    <img src="{{(!empty($blg->blogImages[0]))?asset('blog/'.$blg->blogImages[0]->image):''}}" alt="image desc">
                                </a>
                            </figure><!-- End .entry-media -->
                        </div><!-- End .col-md-5 -->

                        <div class="col-md-8">
                            <div class="entry-body">
                                <div class="entry-meta">
                                    <span class="entry-author">
                                        By <a href="{{route('blogs.detail', $blg->slug)}}">Admin</a>
                                    </span>
                                    <span class="meta-separator">|</span>
                                    <a href="{{route('blogs.detail', $blg->slug)}}">{{ \Carbon\Carbon::parse($blg->created_at)->format('d M, Y')}}</a>
                                    <span class="meta-separator">|</span>
                                </div><!-- End .entry-meta -->

                                <h2 class="entry-title">
                                    <a href="{{route('blogs.detail', $blg->slug)}}">{{$blg->title}}</a>
                                </h2><!-- End .entry-title -->


                                <div class="entry-content">
                                    <p>{!! substr(preg_replace("/<img[^>]+\>/i", "", strip_tags($blg->content)),0,400) !!}...</p>
                                    <a href="{{route('blogs.detail', $blg->slug)}}" class="read-more">continue reading....</a>
                                </div><!-- End .entry-content -->
                            </div><!-- End .entry-body -->
                        </div><!-- End .col-md-7 -->
                    </div><!-- End .row -->
                </article><!-- End .entry -->
                @endforeach

                {{$blogs->appends(request()->query())->links() }}
            </div><!-- End .col-lg-9 -->

            <aside class="col-lg-3">
                <div class="sidebar">
                    <div class="widget widget-search">
                        <h3 class="widget-title">Search</h3><!-- End .widget-title -->
                        <div class="sidebar-wrapper-inner">
                        <form method="get">
                            <label for="ws" class="sr-only">Search in blog</label>
                            <input type="search" class="form-control" type="text" name="filter" value="{{Request::get('filter')}}" id="ws" placeholder="Search in blog">
                            <input type="hidden" value="{{Request::get('cat')}}" name="cat">
                            <input type="hidden" value="{{Request::get('tag')}}" name="tag">
                            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                        </form>
                        </div>
                    </div><!-- End .widget -->

                    <div class="widget widget-cats">
                        <h3 class="widget-title">Categories</h3><!-- End .widget-title -->
                        <div class="sidebar-wrapper-inner">
                        <ul>
                            @foreach($categories as $key=>$value)
                            <li><a class="{{Request::get('cat')==$value->slug?'active':''}}" href="{{Request::fullUrlWithQuery(['page'=>1,'cat' => (Request::get('cat')==$value->slug?'':$value->slug)])}}">{{$value->name}}<span>{{$value->blogs_count}}</span></a></li>
                            @endforeach
                        </ul>
                        </div>
                    </div><!-- End .widget -->

                    <div class="widget">
                        <h3 class="widget-title">Recent Posts</h3><!-- End .widget-title -->
                        <div class="sidebar-wrapper-inner">
                        <ul class="posts-list">
                            @foreach($recentBlog as $key=>$blg)
                            <li>
                                <figure>
                                    <a href="{{route('blogs.detail', $blg->slug)}}">
                                        <img src="{{(!empty($blg->blogImages[0]))?asset('blog/'.$blg->blogImages[0]->image):''}}" alt="post">
                                    </a>
                                </figure>

                                <div>
                                    <span>{{ \Carbon\Carbon::parse($blg->created_at)->format('d M, Y')}}</span>
                                    <h4><a href="{{route('blogs.detail', $blg->slug)}}">{{$blg->title}}</a></h4>
                                </div>
                            </li>
                            @endforeach
                        </ul><!-- End .posts-list -->
                        </div>
                    </div><!-- End .widget -->

                    <div class="widget">
                        <h3 class="widget-title">Browse Tags</h3><!-- End .widget-title -->
                        <div class="sidebar-wrapper-inner">
                        <div class="tagcloud">
                            @foreach($tags as $key=>$value)
                            @php
                            $aTag = explode(',',Request::get('tag'));
                            $aTag = array_filter($aTag);
                            if(in_array($value->tag, $aTag)){
                            $k = array_search($value->tag, $aTag);
                            unset($aTag[$k]);
                            $active_tag = 'active';
                            }else{
                            array_push($aTag,$value->tag);
                            $active_tag = '';
                            }
                            @endphp
                            <a class="{{$active_tag}}" href="{{Request::fullUrlWithQuery(['page'=>1,'tag' => implode(',',$aTag)])}}">{{$value->tag}}</a>
                            @endforeach
                        </div><!-- End .tagcloud -->
                        </div>
                    </div><!-- End .widget -->


                </div><!-- End .sidebar -->
            </aside><!-- End .col-lg-3 -->
        </div><!-- End .row -->
    </div>
</div>
@endsection