<!DOCTYPE html>
<html class="no-js" lang="zxx" dir="ltr">


<!-- Mirrored from htmldemo.net/mitech/index-software-innovation.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Jul 2023 05:06:14 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mitech - Technology IT Solutions HTML Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Technology IT Solutions HTML Template">
    <!-- Favicon -->
    <link rel="icon" href="frontend/images/favicon.webp">

    <!-- CSS -->
    <!-- Font Family CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <!-- Vendor & Plugins CSS (Please remove the comment from below vendor.min.css & plugins.min.css for better website load performance and remove css files from avobe) -->
    <link rel="stylesheet" href="frontend/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="frontend/css/plugins/plugins.min.css">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="frontend/css/style.css">

    <!-- Custom Style CSS -->
    @yield('css')
</head>

<body>
    @include('inc.loader')

    @include('inc.header')
    <div id="main-wrapper">

        @yield('content')
        @include('inc.footer')
        @include('inc.extra')
    </div>

    <!-- JS -->
    <!-- Modernizer JS -->
    <script src="frontend/js/vendor/modernizr-2.8.3.min.js"></script>

    <!-- jQuery JS -->
    <script src="frontend/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="frontend/js/vendor/jquery-migrate-3.3.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="frontend/js/vendor/bootstrap.min.js"></script>

    <!-- Plugins JS (Please remove the comment from below plugins.min.js for better website load performance and remove plugin js files from avobe) -->

    <script src="frontend/js/plugins/plugins.min.js"></script>

    <!-- Main JS -->
    <script src="frontend/js/main.js"></script>

    <!-- Custom JS -->
    @yield('js')
    {{-- <script type="text/javascript" src="{{ url('frontend/js/main.js') }}"></script> --}}
</body>

</html>
