@extends('layouts.app')
@section('content')
<!-- Slider -->
<div class="swiper sliderhome">
   <div class="swiper-wrapper">
      @foreach($banners as $banner)
      <div class="swiper-slide">
         <a href="{{$banner->url}}">
            <img data-fixed class="slide-bgs" src="{{$banner->full}}" alt="Slide">
            <div class="container caption-center">
               <h1>{{$banner->title}}</h1>
            </div>
         </a>
      </div>
      @endforeach
   </div>
   <div class="swiper-pagination"></div>
</div>
<!-- End Slider -->
<div class="container-about-portfolio">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="tabbable-panel">
               <div class="tabbable-line">
                  <ul class="nav nav-tabs ">
                     @foreach($aboutPortfolio as $key=>$value)
                     <li class="{{$key==0?'active':''}}">
                        <a href="#tab-{{$value->slug}}" data-toggle="tab">
                        {{$value->name}} </a>
                     </li>
                     @endforeach
                  </ul>
                  <div class="tab-content">
                     @foreach($aboutPortfolio as $key=>$value)
                     <div class="tab-pane {{$key==0?'active':''}}" id="tab-{{$value->slug}}">
                        {!! $value->description  !!}
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-about-portfolio" id="product-services">
   <div class="container">
      <div class="row">
         <!-- Service Section -->
         <div class="col-md-12">
            <h3 class="title-un"><b>Products and Services</b></h3>
         </div>
         <!-- End Service item -->
         <div class="col-md-12 ">
            <div class="tabbable-panel container-about-portfolio">
               <div class="tabbable-line ProductsServices">
                  <ul class="nav nav-tabs ">
                     @foreach($productServices as $key=>$value)
                     <li class="{{$key==0?'active':''}}">
                        <a href="#{{$value->slug}}" data-toggle="tab">
                        {{$value->name}} </a>
                     </li>
                     @endforeach
                  </ul>
                  <div class="tab-content">
                     @foreach($productServices as $key=>$value)
                     <div class="tab-pane {{$key==0?'active':''}}" id="{{$value->slug}}">
                        {!! $value->description  !!}
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-about-portfolio mb-50">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h3 class="title-un"><b>Discover Smart Homes</b></h3>
         </div>
         <div class="slider-play">
            <div class="swiper mySwiper">
               <div class="swiper-wrapper">
                  @if(!empty($videosSmartHomes))
                  @foreach($videosSmartHomes as $key=>$val)
                  @php
                  $url = $val->link;
                  $value = explode("v=", $url);
                  $videoId = $value[1];
                  @endphp
                  <div class="swiper-slide">
                     <div class="elementor-carousel-image"
                        style="background-image: url('https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg')">
                        <div class="elementor-custom-embed-play">
                           <a href="javascript:void(0);" class="play-video"
                              data-url="{{$val->link}}"><img
                              src="{{asset('front/images/play-button.png')}}" width="250"></a>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @endif
               </div>
               <div class="swiper-pagination"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <h3 class="title-un"><b>Sentegrate Smart Home Products</b></h3>
         </div>
         <div class="slider-play">
            <div class="swiper mySwiper2">
               <div class="swiper-wrapper">
                  @if(!empty($videosSentegrateHomes))
                  @foreach($videosSentegrateHomes as $key=>$val)
                  @php
                  $url = $val->link;
                  $value = explode("v=", $url);
                  $videoId = $value[1];
                  @endphp
                  <div class="swiper-slide">
                     <div class="elementor-carousel-image"
                        style="background-image: url('https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg')">
                        <div class="elementor-custom-embed-play">
                           <a href="javascript:void(0);" class="play-video"
                              data-url="{{$val->link}}"><img
                              src="{{asset('front/images/play-button.png')}}" width="250"></a>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @endif
               </div>
               <div class="swiper-pagination"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-about-portfolio mb-50" id="front-packages-devices"></div>
<div class="container-about-portfolio mb-50" id="about-us">
   <div class="container">
      <div class="row">
         <div class="col-md-6">
            <div class="about-content">
               @if(!empty($aboutSentegrate->description))
               {!! $aboutSentegrate->description !!}
               @endif
            </div>
         </div>
         <div class="col-md-6">
            <div class="about-content">
               @if(!empty($whySentegrate->description))
               {!! $whySentegrate->description !!}
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')
<script>
   $(document).ready(function () {
       $(".play-video").click(function () {
           var url = $(this).attr('data-url');
           var splitLink = url.split('watch?v=')
           var embedLink = splitLink.join("embed/");
           $('#myModal').find('iframe').attr('src', embedLink + '?autoplay=1');
           $("#myModal").modal('show');
       });


       $("#myModal").on('hide.bs.modal', function () {
           $('#myModal iframe').attr('src', '')
       });
   });
</script>
<script src="{{asset('js/app.js')}}"></script>
<script>
    var swiper = new Swiper(".sliderhome", {
        loop: true,
        autoplay: {
            delay: {{$setting->content}},
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>

@stop
