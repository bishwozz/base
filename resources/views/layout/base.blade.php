<!DOCTYPE html>
<html class="no-js" lang="zxx" dir="ltr">


<!-- Mirrored from htmldemo.net/mitech/index-software-innovation.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Jul 2023 05:06:14 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bonanzagaming</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Technology IT Solutions HTML Template">
    {{-- <meta http-equiv="refresh" content="0;url=/"> --}}
    <!-- Favicon -->
    <link rel="icon" href="frontend/images/favicon.webp">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/css/img/icons/icon-192x192.html') }}">


    <!-- Font Family CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/fontawesome-all.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com/">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">
    <!-- FancyBox CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="{{ asset('frontend/css/vendor/vendor.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    <!-- Custom Style CSS -->
    @yield('css')
    <style>
    body {
            background-color: {{ $app_setting->background_color ?? '#ffffff' }};
        }
    </style>
</head>

<body class="theme-light" data-highlight="highlight-red" data-gradient="body-default">

    @include('inc.loader')
    <div id="page">

        @include('inc.header')

        @yield('content')
        @include('inc.footer')
        @include('inc.extra')
    </div>


    <!-- Main JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <!-- FancyBox JS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>


    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <!-- Custom JS -->
    @yield('js')
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "103305029503917");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>
    
      <!-- Your SDK code -->
    {{-- <script>
        window.fbAsyncInit = function () {
          FB.init({
            xfbml: true,
            version: 'v17.0'
          });
        };
    
        (function (d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script> --}}
    {{-- <script type="text/javascript" src="{{ url('frontend/js/main.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('frontend/js/custom.js') }}" defer></script>



</body>

</html>
