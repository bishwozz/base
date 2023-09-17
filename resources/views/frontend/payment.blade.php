@extends('layout.base')
@section('content')
    <style>

        .qr_img {
            height: 189px;
            width: 40%;
        }

        a {
            text-decoration: none;
            color: white;
            font-size: 24px;
        }

        .qr-content {
            white-space: nowrap;
            /* Prevent text from wrapping */
            overflow: hidden;
            /* Hide overflow */
            text-overflow: ellipsis;
            /* Display ellipsis (...) for long content */
        }

        .qrs a {
            word-wrap: break-word;
        }

        .selcls {
            padding: 9px;
            border: solid 4px black;
            outline: 0;
            background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #CAD9E3), to(#FFFFFF));
            background: -moz-linear-gradient(top, #FFFFFF, #CAD9E3 1px, #FFFFFF 25px);
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 8px;
            -moz-box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 8px;
            -webkit-box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 8px;
            height: 70px !important;
            font-size: 28px;

        }

        .load_qsn {
            font-size: 60px;
            ;
        }
    </style>

    <!-- Main css -->
    <link href="z-assets/css/z-assets.css" rel="stylesheet">

    <link rel="stylesheet" href="z-assets/css/themify-icons.css">
    <!-- Owl carousel -->
    <link rel="stylesheet" href="z-assets/css/owl.carousel.min.css">
    <!-- Main css -->
    <link href="z-assets/css/z-assets.css" rel="stylesheet">
    <!--  icon -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="page-content header-clear-small">
        <div class="card card-style preload-img" data-src="images/pictures/18w.jpg" data-card-height="150" style="background-color: black;">
            <div class="card-center ms-3">
                <h1 class="color-white mb-0">Payment</h1>
                {{-- <p class="color-white mt-n1 mb-0">Ready built to create Pages</p> --}}
            </div>
            <div class="card-center me-3">
                <a href="/home"
                    class="back-button btn btn-m float-end rounded-xl shadow-xl text-uppercase font-800 bg-highlight">Back
                    Home</a>
            </div>


        </div>
    </div>

    @auth
        <div class="card card-style">
            <div class="content mb-0">
                <h1 class="text-center mb-0"> Banks Payments Qr </h1>
                <div class="divider"></div>
            </div>


            <div class="container-fluid " style="margin-top: 100px;">
                <h1 class="text-center load_qsn">How much you waana load ?</h1>
                <br>
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-7">
                        <select class="form-control selcls qr_filter" id="qr_filter" aria-label="Large select example">
                            <option value="">Select Amount </option>
                        </select>
                    </div>
                </div>
                <br>
            </div>


            <div class="container">
                <div class="row">
                    <div class="qr_container" style="display: contents;">
                    </div>
                </div>
              </div>

            {{-- <div class="container-fluid">
                <div class="row">
                    
                </div>
            </div> --}}
        </div>
    @else
        <div class="card card-style" style="margin-top: 2em;">
            <div class="content mb-0">
                <h1 class="text-center mb-0"> Other Payments </h1>
                <div class="divider"></div>
            </div>
            <div style="min-height:100px;">
                <h1 style="text-align: center;"><a href="/login"><span style="color:black;"> login to pay...</span></a></h1>
            </div>
            <div class="divider"></div>

            <div class="divider"></div>

        </div>
    @endauth
    @include('frontend.index_footer')
@endsection
