<!-- inner-conterner -->
<header class="site-header">
    <div class="header-inner">
        <!-- navigation panel -->
        <div class="container-fluid">
            <div class="row">
                <div class="header-table col-md-12">
                    <div class="brand">
                        <a href="{{route('home')}}">
                            <img src="{{asset('front/images/logo.png')}}" alt="{{config('app.name')}}"> <span
                                class="logo-tiitle">sentegrate</span>
                        </a>
                    </div>
                    <nav id="nav-wrap" class="main-nav">
                        <div id="mobnav-btn"></div>

                        <ul class="contectnum">
                            <li><a href=""> <i class="ion-ios-telephone-outline"></i> 02 8607 1808</a></li>

                        </ul>
                        <ul class="sf-menu">
                            <li class="current">
                                <a href="{{route('home')}}#product-services">Products and Services</a>
                            </li>
                            <li>
                                <a href="{{route('blogs.listing')}}">Blog</a>
                            </li>
                            <li>
                                <a href="{{url('case-studies-2')}}">Case Studies </a>
                            </li>
                            <li>
                                <a href="{{route('home')}}#about-us">About</a>
                            </li>
                            <li>
                                <a href="{{route('home')}}#contactforms">Contact</a>
                            </li>
                            <li class="menu-search-bar">
                                <div class=" align-center">
                                    <a class="button btn btn-warning" href="{{route('quote')}}">ORDER</a>
                                </div>
                            </li>
                            @if(Auth::check())

                                <li class="  dropdown">
                                    <a href="#" class="dropbtn">
                                        <i class="fas fa-user"></i>{{Auth::user()->name}}</a>
                                    <div class="dropdown-content">
                                        @if(Auth::user()->role2!='owner')
                                            <a href="{{route('user.profile')}}"><span><i class="far fa-user"></i></span>Profile</a>
                                            <a href="{{route('user.myOrder')}}"><span><i class="fas fa-shopping-cart"></i></span>My Orders</a>
                                        @endif
                                        @if(Auth::user()->customer)
                                            <a href="{{route('support.ticket.listing')}}"><span><i
                                                        class="fas fa-ticket-alt"></i></span>Support Ticket</a>
                                        @endif
                                        <a href="{{route('logout')}}" onclick="sessionStorage.clear();"><span><i
                                                    class="fas fa-sign-out-alt"></i></span>Logout</a>
                                    </div>

                                </li>
                            @else
                                <li class="menu-search-bar">
                                    <div class=" align-center">
                                        <a class="button btn btn-warning"
                                           href="{{route('login')}}">Register / Login</a>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- End navigation panel -->
        </div>
        <!-- End navigation panel -->
    </div>
</header>
