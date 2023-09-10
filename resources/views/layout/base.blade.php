<!DOCTYPE html>
<html class="no-js" lang="zxx" dir="ltr">


<!-- Mirrored from htmldemo.net/mitech/index-software-innovation.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Jul 2023 05:06:14 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Technology IT Solutions HTML Template">
    <!-- Favicon -->
    <link rel="icon" href="frontend/images/favicon.webp">

    <!-- CSS -->

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/css/img/icons/icon-192x192.html') }}">

    <!-- Font Family CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/fontawesome-all.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com/">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <!-- Vendor & Plugins CSS (Please remove the comment from below vendor.min.css & plugins.min.css for better website load performance and remove css files from avobe) -->
    <link rel="stylesheet" href="{{ asset('frontend/css/vendor/vendor.min.css') }}">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">

    <!-- Custom Style CSS -->
    @yield('css')
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
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>

    <!-- Custom JS -->
    @yield('js')
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "103305029503917");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>
    
      <!-- Your SDK code -->
    <script>
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
    </script>
    {{-- <script type="text/javascript" src="{{ url('frontend/js/main.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('frontend/js/custom.js') }}" defer></script>



</body>

</html>
