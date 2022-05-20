@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="mini-header our-blogs-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mini-header-content">
                        <h1>Case Studies</h1>
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
                                        <a href="{{route('case_study.detail', $blg->slug)}}">
                                            <img src="{{(!empty($blg->images[0]))?$blg->images[0]->thumb:''}}"
                                                 alt="image desc">
                                        </a>
                                    </figure><!-- End .entry-media -->
                                </div><!-- End .col-md-5 -->

                                <div class="col-md-8">
                                    <div class="entry-body">
                                        <div class="entry-meta">
                                    <span class="entry-author">
                                        By <a href="{{route('case_study.detail', $blg->slug)}}">Admin</a>
                                    </span>
                                            <span class="meta-separator">|</span>
                                            <a href="{{route('case_study.detail', $blg->slug)}}">{{ \Carbon\Carbon::parse($blg->created_at)->format('d M, Y')}}</a>
                                            <span class="meta-separator">|</span>
                                        </div><!-- End .entry-meta -->

                                        <h2 class="entry-title">
                                            <a href="{{route('case_study.detail', $blg->slug)}}">{{$blg->title}}</a>
                                        </h2><!-- End .entry-title -->


                                        <div class="entry-content">
                                            <p>{!! substr(preg_replace("/<img[^>]+\>/i", "", strip_tags($blg->content)),0,400) !!}
                                                ...</p>
                                            <a href="{{route('case_study.detail', $blg->slug)}}" class="read-more">continue
                                                reading....</a>
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
                                    <label for="ws" class="sr-only">Search</label>
                                    <input type="search" class="form-control" type="text" name="filter"
                                           value="{{Request::get('filter')}}" id="ws" placeholder="Search">
                                    <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div><!-- End .widget -->
                        <div class="widget">
                            <h3 class="widget-title">Recent Studies</h3><!-- End .widget-title -->
                            <div class="sidebar-wrapper-inner">
                                <ul class="posts-list">
                                    @foreach($recentBlog as $key=>$blg)
                                        <li>
                                            <figure>
                                                <a href="{{route('case_study.detail', $blg->slug)}}">
                                                    <img src="{{(!empty($blg->images[0]))?$blg->images[0]->thumb:''}}"
                                                         alt="post">
                                                </a>
                                            </figure>

                                            <div>
                                                <span>{{ \Carbon\Carbon::parse($blg->created_at)->format('d M, Y')}}</span>
                                                <h4>
                                                    <a href="{{route('case_study.detail', $blg->slug)}}">{{$blg->title}}</a>
                                                </h4>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul><!-- End .posts-list -->
                            </div>
                        </div><!-- End .widget -->
                    </div><!-- End .sidebar -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div>
    </div>
@endsection