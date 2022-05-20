@extends('layouts.app')
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="blogdetail">
                    <div class="topheaderhding">
                        <div class="entry-meta">
                            <span class="entry-author">by <a href="javascript:void(0);">Admin</a>
                            </span>
                            <span class="meta-separator">|</span>
                            <a href="javascript:void(0);">{{ \Carbon\Carbon::parse($blog->created_at)->format('d M, Y')}}</a>

                        </div>
                        <!-- End .entry-meta -->
                        <h1 class="entry-title">
                            {{$blog->title}}
                        </h1>

                    </div>

                    <div class="detailblogimg">
                        <img src="{{(!empty($blog->blogImages[0]))?asset('blog/'.$blog->blogImages[0]->image):''}}">
                    </div>
                    <div class="entry-body">


                        <div class="entry-content editor-content">
                            {!! $blog->content !!}
                            <!-- End .mb-1 -->
                        </div><!-- End .entry-content -->
                        <!-- End .entry-footer row no-gutters -->
                    </div>
                </div>
            </div><!-- End .col-lg-9 -->

            <aside class="col-lg-3">
                <div class="sidebar">
                    <div class="widget widget-search">
                        <h3 class="widget-title">Search</h3><!-- End .widget-title -->
                        <div class="sidebar-wrapper-inner">
                            <form method="get" action="{{ route('blogs.listing') }}">
                                <label for="ws" class="sr-only">Search in blog</label>
                                <input type="search" class="form-control" type="text" name="filter" value="" id="ws" placeholder="Search in blog">
                                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                            </form>
                        </div>

                    </div><!-- End .widget -->

                    <div class="widget widget-cats">
                        <h3 class="widget-title">Categories</h3><!-- End .widget-title -->
                        <div class="sidebar-wrapper-inner">
                        <ul>
                            @foreach($categories as $key=>$value)
                            <li><a class="" href="{{route('blogs.listing',['page'=>1,'cat'=>$value->slug])}}">{{$value->name}}
                                    <span>{{$value->blogs_count}}</span></a></li>
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
                            <a class="" href="{{route('blogs.listing',['page'=>1,'tag'=>$value->tag])}}">{{$value->tag}}</a>
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
