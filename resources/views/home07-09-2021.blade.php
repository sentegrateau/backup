@extends('layouts.app')
@section('content')
    <!-- Slider -->
    <div class="slider-wrapper">
        <div class="fr-slider">
            <div class="fs_loader"></div>
            @foreach($banners as $banner)
                <div class="slide">
                    <img data-fixed class="slide-bg" src="{{$banner->full}}" alt="Slide">
                    <div class="caption-center">
                        <h1>{{$banner->title}}</h1>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- End Slider -->
    <div class="container-about-portfolio">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                        Installers / Electricians </a>
                                </li>
                                <li>
                                    <a href="#tab_default_2" data-toggle="tab">
                                        Home Owners </a>
                                </li>
                                <li>
                                    <a href="#tab_default_3" data-toggle="tab">
                                        Developers </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                    <div class=" ">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="title-un-des">In 3 easy steps we deliver ready to install Home
                                                    Automation and Security kits, pre-configured to match the needs of
                                                    your clients, eliminating the need for the installers to configure
                                                    individual components </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <!-- Service item -->
                                                <div class="service-box-sb wow fadeInUp animated"
                                                     style="visibility: visible;">
                                                    <div class="service-img">
                                                        <img src="{{asset('front/images/sas.jpg')}}" alt="config('app.name')">
                                                    </div>
                                                    <div class="service-info">
                                                        <h5><b>1.UNIQUE FEATURES</b></h5>
                                                    </div>
                                                </div>
                                                <!-- End Service item -->
                                            </div>
                                            <div class="col-md-4">
                                                <!-- Service item -->
                                                <div class="service-box-sb wow fadeInUp animated" data-wow-delay=".2s"
                                                     style="visibility: visible;-webkit-animation-delay: .2s; -moz-animation-delay: .2s; animation-delay: .2s;">
                                                    <div class="service-img">
                                                        <img src="{{asset('front/images/sasa.jpg')}}"
                                                             alt="config('app.name')">
                                                    </div>
                                                    <div class="service-info">
                                                        <h5><b>2. Configure and develop installation kits to suit your
                                                                customer specific requirements </b></h5>
                                                    </div>
                                                </div>
                                                <!-- End Service item -->
                                            </div>
                                            <div class="col-md-4">
                                                <!-- Service item -->
                                                <div class="service-box-sb wow fadeInUp animated" data-wow-delay=".4s"
                                                     style="visibility: visible;-webkit-animation-delay: .4s; -moz-animation-delay: .4s; animation-delay: .4s;">
                                                    <div class="service-img">
                                                        <img src="{{asset('front/images/sasaa.jpg')}}"
                                                             alt="config('app.name')">
                                                    </div>
                                                    <div class="service-info">
                                                        <h5><b>3. Deliver plug-n-play kit to you </b></h5>
                                                    </div>
                                                </div>
                                                <!-- End Service item -->
                                            </div>
                                            <div class="col-md-12 text-center Get-quote">
                                                <div class="align-center ">
                                                    <a class="button btn btn-warning" href="#"><b>Get a quote today</b></a>
                                                </div>
                                                <p>Register / Login for package prices and generate an <br>automated
                                                    quote.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_2">
                                    <div class=" ">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="title-un-des">We work with you to create your dream Smart Home
                                                    living experience in 3 easy steps. Tell us about your home
                                                    automation needs to get started today… </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <!-- Service item -->
                                                <div class="service-box-sb  ">
                                                    <div class="service-img">
                                                        <img src="{{asset('front/images/Workoutneeds.jpg')}}"
                                                             alt="config('app.name')">
                                                    </div>
                                                    <div class="service-info">
                                                        <h5><b>1. Workout your needs</b></h5>
                                                    </div>
                                                </div>
                                                <!-- End Service item -->
                                            </div>
                                            <div class="col-md-4">
                                                <!-- Service item -->
                                                <div class="service-box-sb  ">
                                                    <div class="service-img">
                                                        <img src="{{asset('front/images/sasa.jpg')}}"
                                                             alt="config('app.name')">
                                                    </div>
                                                    <div class="service-info">
                                                        <h5><b>2. Source devices and pre-configure for installation </b>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <!-- End Service item -->
                                            </div>
                                            <div class="col-md-4">
                                                <!-- Service item -->
                                                <div class="service-box-sb  ">
                                                    <div class="service-img">
                                                        <img src="{{asset('front/images/Quick-installation.jpg')}}"
                                                             alt="config('app.name')">
                                                    </div>
                                                    <div class="service-info">
                                                        <h5><b>3. Quick installation to deliver a smart home solution to
                                                                you</b></h5>
                                                    </div>
                                                </div>
                                                <!-- End Service item -->
                                            </div>
                                            <div class="col-md-12 text-center Get-quote">
                                                <div class="align-center ">
                                                    <a class="button btn btn-warning" href="#"><b>Tell us about your
                                                            home</b></a>
                                                </div>
                                                <p>Register / Login as a home owner to provide us <br>your requirement.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <div class="row ">
                                        <div class="col-md-7">
                                            <div class="content-left">
                                                <p>If you are a Property Developer, we can assist with Home Automation
                                                    offerings for your clients. We can then closely work with your
                                                    electricians to deliver a Smart Home solution on your behalf.
                                                    Contact us to get started…</p>
                                                <div class="align-center ">
                                                    <a class="button btn btn-warning" href="#"><b>Contact Us</b></a>
                                                </div>
                                                <p>Or if you would like to provide us your requirements to get an
                                                    automated quote, please register / login as a developer</p>
                                                <div class="align-center ">
                                                    <a class="button btn btn-warning" href="#"><b>Contact Us</b></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="rightimage">
                                                <img src="{{asset('front/images/f2.jpg')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-about-portfolio">
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
                                <li class="active">
                                    <a href="#Home" data-toggle="tab">
                                        Home </a>
                                </li>
                                <li>
                                    <a href="#Lighting" data-toggle="tab">
                                        Lighting </a>
                                </li>
                                <li>
                                    <a href="#Entertainment" data-toggle="tab">
                                        Entertainment </a>
                                </li>
                                <li>
                                    <a href="#AirConditioning" data-toggle="tab">
                                        Air Conditioning </a>
                                </li>
                                <li>
                                    <a href="#Security" data-toggle="tab">
                                        Security </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="Home">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="tabimg">
                                                <img src="{{asset('front/images/SMART-HOME.jpg')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="tab-right-content">
                                                <div class="elementor-image-box-wrapper">
                                                    <figure class="elementor-image-box-img">
                                                        <img width="" height="" src="{{asset('front/images/Untitled.png')}}"
                                                             class="attachment-full size-full" alt="" loading="lazy">
                                                    </figure>
                                                    <div class="elementor-image-box-content">
                                                        <h3 class="elementor-image-box-title">Smart Home</h3>
                                                    </div>
                                                </div>
                                                <div class="elementor-widget-container">
                                                    <div class="elementor-text-editor elementor-clearfix">
                                                        <ul>
                                                            <li>Let your home learn your preferences and offer you the
                                                                ultimate comfort, safety and enjoyment
                                                            </li>
                                                            <li>A fully automated home works with the rhythm of your
                                                                life and performs actions for you automatically to suit
                                                                your everyday routine
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Captureimg">
                                                <img src="{{asset('front/images/Capture-1024x67.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="Lighting">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="tabimg">
                                                <img src="{{asset('front/images/LIGHTING.jpg')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="tab-right-content">
                                                <div class="elementor-image-box-wrapper">
                                                    <figure class="elementor-image-box-img">
                                                        <img width="" height="" src="{{asset('front/images/LIGHTING-s.png')}}"
                                                             class="attachment-full size-full" alt="" loading="lazy">
                                                    </figure>
                                                    <div class="elementor-image-box-content">
                                                        <h3 class="elementor-image-box-title">LIGHTING</h3>
                                                    </div>
                                                </div>
                                                <div class="elementor-widget-container">
                                                    <div class="elementor-text-editor elementor-clearfix">
                                                        <ul>
                                                            <li>Save on energy by turning lights and fans off when no
                                                                one is around
                                                            </li>
                                                            <li>Control lights by motion or using a schedule</li>
                                                            <li>Vacation lighting helps create an impression of occupied
                                                                house
                                                            </li>
                                                            <li>Eliminate blue light from your lighting in the evening
                                                                to assist with your circadian rhythm and promote restful
                                                                sleep
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Captureimg">
                                                <img src="{{asset('front/images/Capture-1024x67.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="Entertainment">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="tabimg">
                                                <img src="{{asset('front/images/ENTERTAINMENT.jpg')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="tab-right-content">
                                                <div class="elementor-image-box-wrapper">
                                                    <figure class="elementor-image-box-img">
                                                        <img width="" height=""
                                                             src="{{asset('front/images/ENTERTAINMENT-s.png')}}"
                                                             class="attachment-full size-full" alt="" loading="lazy">
                                                    </figure>
                                                    <div class="elementor-image-box-content">
                                                        <h3 class="elementor-image-box-title">ENTERTAINMENT</h3>
                                                    </div>
                                                </div>
                                                <div class="elementor-widget-container">
                                                    <div class="elementor-text-editor elementor-clearfix">
                                                        <ul>
                                                            <li>With just one voice command start watching a movie with
                                                                your favourite theatre setup – let your smart home take
                                                                care of the lighting, blinds setup and play the movie
                                                                using your favourite streaming service or Blu Ray player
                                                            </li>
                                                            <li>Play music or&nbsp;radio anywhere round the house with
                                                                our smart wireless speakers
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Captureimg">
                                                <img src="{{asset('front/images/Capture-1024x67.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="AirConditioning">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="tabimg">
                                                <img src="{{asset('front/images/CLIMATECONTROL.jpg')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="tab-right-content">
                                                <div class="elementor-image-box-wrapper">
                                                    <figure class="elementor-image-box-img">
                                                        <img width="" height=""
                                                             src="{{asset('front/images/CLIMATECONTROL-s.png')}}"
                                                             class="attachment-full size-full" alt="" loading="lazy">
                                                        <img width="" height=""
                                                             src="{{asset('front/images/CLIMATECONTROL-s.png')}}"
                                                             class="attachment-full size-full" alt="" loading="lazy">
                                                    </figure>
                                                    <div class="elementor-image-box-content">
                                                        <h3 class="elementor-image-box-title">CLIMATE CONTROL</h3>
                                                    </div>
                                                </div>
                                                <div class="elementor-widget-container">
                                                    <div class="elementor-text-editor elementor-clearfix">
                                                        <ul>
                                                            <li>Control zone or even room based temperature settings on&nbsp;your
                                                                Air Conditioner based on individual and seasonal
                                                                preferences
                                                            </li>
                                                            <li>Save energy by automatically opening and closing blinds
                                                                during the day to adjust room temperature depending on&nbsp;external
                                                                temperature
                                                            </li>
                                                            <li>Turn on/off dehumidifier based on humidity level</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Captureimg">
                                                <img src="{{asset('front/images/Capture-1024x67.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="Security">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="tabimg">
                                                <img src="{{asset('front/images/SAFETY.jpg')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="tab-right-content">
                                                <div class="elementor-image-box-wrapper">
                                                    <figure class="elementor-image-box-img">
                                                        <img width="" height="" src="{{asset('front/images/SAFETY-s.png')}}"
                                                             class="attachment-full size-full" alt="" loading="lazy">
                                                    </figure>
                                                    <div class="elementor-image-box-content">
                                                        <h3 class="elementor-image-box-title">SAFETY AND SECURITY</h3>
                                                    </div>
                                                </div>
                                                <div class="elementor-widget-container">
                                                    <div class="elementor-text-editor elementor-clearfix">
                                                        <ul>
                                                            <li>Home Automation Controller based Security System</li>
                                                            <li>Automated arming and disarming of security system based
                                                                on home occupancy tracking
                                                            </li>
                                                            <li>Get&nbsp;alerted on your mobile phone should there be a
                                                                security breach when you are away from home
                                                            </li>
                                                            <li>Check on your home any time with CCTV app on your mobile
                                                                or view recorded videos
                                                            </li>
                                                            <li>Get alerted&nbsp;on your mobile when smoke or flooding
                                                                is detected in your hom
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Captureimg">
                                                <img src="{{asset('front/images/Capture-1024x67.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                            <div class="swiper-slide">


                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture1.jpg)">

                                    <div class="elementor-custom-embed-play">
                                        <a href="#myModal" class=" " data-toggle="modal"><img
                                                src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>


                            </div>
                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture2.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture3.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture4.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture5.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>


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
                            <div class="swiper-slide">


                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture6.jpg)">

                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>


                            </div>
                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture7.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture8.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture9.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture10.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture11.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture12.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="elementor-carousel-image"
                                     style="background-image: url(images/Picture13.jpg )">
                                    <div class="elementor-custom-embed-play">
                                        <a href=""><img src="{{asset('front/images/play-button.png')}}"></a>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>


            </div>

        </div>
    </div>


    <div class="container-about-portfolio mb-50">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="about-content">
                        <h2>About Sentegrate</h2>
                        <p>Inspired by a vision of true smart living with automated homes and businesses, Sentegrate has
                            been formed to offer safe, comfortable and delightful homes and workplaces for everyone. We
                            strive to deliver excellence in home automation through property developers. installers,
                            electricians or home owners themselves.</p>
                        <p>At Sentegrate we work hard to achieve the following goals for our customers:</p>

                        <ul>
                            <li>Simplify life with cleverly hidden automation</li>
                            <li>Provide service excellence</li>
                            <li>Deliver economic value</li>
                        </ul>

                        <p>While modern lifestyles have created ‘technology overload’ with multiple devices, appliances,
                            apps and remote controls to grapple with, Sentegrate strives for simplification of “in your
                            face” technologies. We offer real value by focusing on our customers and use home automation
                            technology to improve comfort and convenience instead of adding complexity.</p>

                    </div>

                </div>
                <div class="col-md-6">
                    <div class="about-content">
                        <h2>Why Sentegrate</h2>
                        <p><i>“We make technology work harder to help you rediscover simplicity and comfort in your
                                life”</i></p>
                        <p>At Sentegrate we work hard to achieve the following goals for our customers:</p>

                        <ul class="Sentegrate">
                            <li><span><img src="{{asset('front/images/Untitled-19.png')}}"></span>
                                <p><b>Service Approach</b> – We take special care to ensure you not only get good
                                    service but also a great experience in dealing with us</p></li>

                            <li><span><img src="{{asset('front/images/Untitled-20.png')}}"></span>
                                <p><b>Economic Value </b> – We work hard to ensure your Smart Home is designed to save
                                    by reducing power consumption</p></li>

                            <li><span><img src="{{asset('front/images/Untitled-22.png')}}"></span>
                                <p><b>Engineering </b> – You benefit from our extensive engineering experience spanning
                                    over 20 years in the fields of electrical, electronics, software and IT </p></li>

                            <li><span><img src="{{asset('front/images/Untitled-17.png')}}"></span>
                                <p class="elementor-image-box-description"><b>Customer Care</b>&nbsp;– Our Smart Home
                                    maintenance plan is designed to provide our customers peace of mind through quality
                                    on-going support</p></li>

                            <li><span><img src="{{asset('front/images/Untitled-18.png')}}"></span>
                                <p class="elementor-image-box-description"><b>Project Management</b>&nbsp;– We bring
                                    over 30 years combined experience of technology implementations for top 50 ASX
                                    companies
                                </p></li>

                        </ul>


                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
