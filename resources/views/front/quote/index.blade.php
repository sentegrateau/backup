@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        @if(Auth::check() && \Illuminate\Support\Facades\Auth::user()->role2=='owner')
            <div class="container-about-portfolio mb-50" id="home-owner-with-room"></div>
        @else
            <iframe src="https://staging.sentegrate.com?token={{$token}}&fromCheckout=true" title="Sentegrate"
                    frameborder="none" width="100%" height="800px"
                     style="height: 800px;"></iframe>
        @endif
    </div>
@endsection
@section('js')

    <script src="{{asset('js/app.js')}}"></script>
@stop


{{--<iframe src="{{url('public/webdemo/')}}?token={{$token}}&fromCheckout=true" title="Sentegrate"
         frameborder="none" width="100%" height="800px"
          style="height: 800px;"></iframe>--}}
