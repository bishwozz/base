<!--Header Area Start-->
<header>
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-12">
                    {{-- <span>Have any question? <a style="color: white;" href="tel:.'{{$header_footer_data['footer']->phone}}'.">{{$header_footer_data['footer']->phone}}</a></span> --}}
                    <span>Welcome To {{$header_footer_data['college_details']->title}}</span>
                </div>
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="header-top-right">
                        <span><a style="color: white;" href="tel:.'{{$header_footer_data['college_details']->phone}}'.">Phone: {{$header_footer_data['college_details']->phone}}</a></span>
                        <span><a style="color: white;" href="mailto:'.{{$header_footer_data['college_details']->email}}.'">Email: {{$header_footer_data['college_details']->email}}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-logo-menu sticker">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12">
                    @php
                     $logo = $header_footer_data['college_details']->logo;
                    @endphp
                    <div class="logo">
                        <a href="{{url('/home')}}"><img style="height: 60px;" src="{{url('/') . '/storage/uploads/' .$logo}}"alt="LINCON"></a>
                    </div>
                </div>
                <div class="col-lg-9 col-12">
                    <div class="mainmenu-area pull-right">
                        <div class="mainmenu d-none d-lg-block">
                            <nav>
                                @if(count($menus))
                                    <ul id="nav" class="nav-menu">
                                        @foreach($menus as $menu)
                                           <li><a href="{{url($menu->link)}}">{{$menu->title}}</a>
                                                @if(count($menu->subMenus))
                                                    <ul id="sub-menu{{$menu->id}}" class="sub-menu">
                                                        @foreach ($menu->subMenus as $subMenu)
                                                           <li><a href="{{url($subMenu->link)}}">{{$subMenu->title}}</a>
                                                                @if(count($subMenu->subMenus))
                                                                    <ul class="inside-menu">
                                                                        @foreach ($subMenu->subMenus as $second_level_sub_menu)
                                                                            <li><a href="{{url($second_level_sub_menu->link)}}">{{$second_level_sub_menu->title}}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </nav>
                        </div>
                        {{-- <ul class="header-search">
                            <li class="search-menu">
                                <i id="toggle-search" class="zmdi zmdi-search"></i>
                            </li>
                        </ul> --}}
                        <!--Search Form-->
                        {{-- <div class="search">
                            <div class="search-form">
                                <form id="search-form" action="#">
                                    <input type="search" placeholder="Search here..." name="search" />
                                    <button type="submit">
                                        <span><i class="fa fa-search"></i></span>
                                    </button>
                                </form>                                
                            </div>
                        </div> --}}
                        <!--End of Search Form-->
                    </div> 
                </div>
            </div>
        </div>
    </div>  
    <!-- Mobile Menu Area start -->
    <div class="mobile-menu-area">
        <div class="container clearfix">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            @if(count($menus))
                                <ul>
                                    @foreach($menus as $menu)
                                        <li><a href="{{$menu->link}}">{{$menu->title}}</a>
                                            @if(count($menu->subMenus))
                                                <ul>
                                                    @foreach ($menu->subMenus as $subMenu)
                                                        <li><a href="{{$subMenu->link}}">{{$subMenu->title}}</a>
                                                            @if(count($subMenu->subMenus))
                                                                <ul>
                                                                    @foreach ($subMenu->subMenus as $second_level_sub_menu)
                                                                        <li><a href="{{$second_level_sub_menu->link}}">{{$second_level_sub_menu->title}}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </nav>
                    </div>					
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area end -->   
</header>
<!--End of Header Area-->