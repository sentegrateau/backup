<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,800,500,600,300,700' rel='stylesheet'
          type='text/css'>
    <!-- ionicons Fonts for icons -->
    <link href="{{asset('front/css/ionicons.min.css')}}" rel="stylesheet">
    <!-- bootstrap -->
    <link href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">
    <!-- Styles CSS-->
    <link href="{{asset('front/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <!-- Animate CSS-->
    <link href="{{asset('front/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/swiper-bundle.min.css')}}" rel="stylesheet">
    <link href="{{ asset('front/css/toastme.min.css')}}" rel="stylesheet" type="text/css"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <script>
        var SITE_URL = '{{url('')}}';
        var USER_EMAIL = '{{(Auth::check())?Auth::user()->email:''}}';
    </script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

</head>
<body class="box-layout {{\Request::route()->getName()=='home'?'homeclass':''}}">
<!-- Page Loader -->
<div class="page-loader">
    <div class="loader">
        <span class="cssload-loading"></span>
    </div>
</div>
<!-- End Page Loader -->
<div class="inner-conterner">
    @include('layouts.elements.header')
    @yield('content')
    @include('layouts.elements.footer')
</div>
<!-- End inner-conterner -->
<div id="myModal" class="modal fade youtube-videos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="  ">

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class=" ">
                    <iframe width="100%" height="500" src="https://www.youtube.com/embed/bT9Tta7xWdo"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            title="YouTube video player" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ================================================== -->
<!-- Placed js files at the end of the document so the pages load faster -->
<script src="{{asset('front/js/jquery.min.js')}}"></script>
<script src="{{asset('front/js/custom.js')}}"></script>
<script src="{{asset('front/js/bootstrap.min.js')}}"></script>
<script src="{{asset('front/js/superfish.js')}}"></script>
<script src="{{asset('front/js/jquery.easing.js')}}"></script>
<script src="{{asset('front/js/wow.js')}}"></script>
<script src="{{asset('front/js/jquery.isotope.min.js')}}"></script>
<script src="{{asset('front/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('front/js/jquery.magnific-popup.js')}}"></script>
<script src="{{asset('front/js/jflickrfeed.min.js')}}"></script>
<script src="{{asset('front/js/jquery.fitvids.js')}}"></script>
<script src="{{asset('front/js/jquery.fractionslider.min.js')}}"></script>
<script src="{{asset('front/js/jquery-ui-1.10.4.custom.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/js/SmoothScroll.js')}}"></script>
<script src="{{asset('front/js/main.js')}}"></script>
<script src="{{asset('front/js/swiper-bundle.min.js')}}"></script>
<script src="{{ asset('/front/js/toastme.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 3,
        spaceBetween: 15,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            478: {
                slidesPerView: 2,
            },
            576: {
                slidesPerView: 4,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 3,
            },
        },
    });


    var swiper = new Swiper(".mySwiper2", {
        slidesPerView: 3,
        spaceBetween: 15,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            478: {
                slidesPerView: 2,
            },
            576: {
                slidesPerView: 4,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 3,
            },
        },
    });


</script>
<script>
    const config = {
        timeout: 5000,
        positionY: "top", // top or bottom
        positionX: "right", // right left, center
        distanceY: 120, // Integer value
        distanceX: 5, // Integer value
        zIndex: 100, // Integer value
        theme: "default", // default, ligh or  dark (leave empty for "default" theme)
        duplicates: false, // true or false - by default it's false
        animations: true, // Show animations - by default it's true
    };
    //Create a new Toastmejs class instance
    const mytoast = new Toastme(config);

    $(function () {

        $('body #contact-form-submit-home').on('submit', function (e) {

            e.preventDefault();

            $.ajax({
                type: 'post',
                url: '{{route('page.saveContactForm')}}',
                data: $('#contact-form-submit-home').serialize(),
                success: function (res) {
                    console.log('herer',res);
                    var html = res.message.map(function (error) {
                        return `<p class="text-success">${error}</p>`;
                    });
                    $('#contact-form-errors').html(html);
                    $('#captcha-code-updated').attr('src', res.data.captcha_img+'?v='+new Date());
                    document.getElementById("contact-form-submit-home").reset();
                },
                error: function (err) {
                    if (err.responseText) {
                        var errors = JSON.parse(err.responseText);
                        var html = errors.message.map(function (error) {
                            return `<p class="text-danger">${error}</p>`;
                        });
                        $('#contact-form-errors').html(html);
                        $('#captcha-code-updated').attr('src', errors.data.captcha_img+'?v='+new Date());
                    }
                }
            });

        });

    });

</script>
@if ($message = Session::get('success'))
    <script>
        $(function () {
            mytoast.success('{{ $message }}');
        });
    </script>
@endif
@if ($message = Session::get('error'))
    <script>
        $(function () {
            mytoast.error('{{ $message }}');
        });
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            $(function () {
                mytoast.error('{{ $error }}');
            });
        </script>
    @endforeach
@endif

@yield('js')
@stack('js')
</body>
</html>
