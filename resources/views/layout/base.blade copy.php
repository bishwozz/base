<!doctype html>
<html class="no-js" lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Lincon College</title>
        <meta name="description" content="lincoln institute of business and technology">
        <meta name="keywords" content="lincoln, lincoln international, libt, best college, college in australia lincoln college, lincoln australia, college, australia, lincoln college australia">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        
		<!-- favicon
		============================================ -->		
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/') . '/storage/uploads/' .$header_footer_data['college_details']->logo}}" />

	   
		<!-- Bootstrap CSS
		============================================ -->		
        <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/material-design-iconic-font.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/plugin.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
            <style>
                .breadcrumb-banner-area,.testimonial-area {
                    background: rgba(0, 0, 0, 0) url("{{ asset('img/bg_img.jpg')}}") no-repeat scroll 0 0 !important;
                    background-size: cover !important;
                }
                .testimonial-area,.fun-factor-area {
                    background: rgba(0, 0, 0, 0) url("{{ asset('img/saying_bg.jpg')}}") no-repeat scroll 0 0 !important;
                    background-size: cover !important;
                }
                .fun-factor-area {
                    background: rgba(0, 0, 0, 0) url("{{ asset('img/count_bg.jpg')}}") no-repeat scroll 0 0 !important;
                    background-size: cover !important;
                }
            </style>
            @yield('css')
    </head>
    <body>
        <!--Loading Area Start-->
        <div class="loading">
    		<div class="text-center middle">
    			<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    		</div>
    	</div>
        <!--Loading Area End-->
        <!--Main Wrapper Start-->
        <div class="as-mainwrapper">
            <!--Bg White Start-->
            <div class="bg-white">
                @include('inc.header')
                @yield('content')
                @include('inc.footer')
            </div>   
            <!--End of Bg White--> 
        </div>    
        <!--End of Main Wrapper Area--> 
		  
		<!-- jquery
		============================================ -->	
        @yield('js')
        <script type="text/javascript" src="{{url('frontend/js/vendor/modernizr-3.6.0.min.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/vendor/jquery-3.6.0.min.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/vendor/jquery-migrate-3.3.2.min.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/popper.min.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/bootstrap.min.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/ajax-mail.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/plugins.js')}}"></script>
        <script type="text/javascript" src="{{url('frontend/js/main.js')}}"></script>
    </body>
</html>