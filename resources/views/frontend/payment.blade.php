@extends('layout.base')
@section('content')
    @auth
    <style>
      body {
          background-color: rgb(108, 116, 157);
          /* background: linear-gradient(90deg, rgba(108, 116, 157, 1) 0%, rgba(113, 128, 208, 1) 0%, rgba(196, 131, 144, 1) 100%); */
      }

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
          border: solid 1px #517B97;
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


  <div class="card card-style">
      <div class="content mb-0">
          <h1 class="text-center mb-0"> Other Payments </h1>
          <div class="divider"></div>
      </div>



      <div class="nav-menu fixed-top">
          <div class="row ">
              <div class="col-md-12">
                  <nav class="navbar navbar-dark navbar-expand-lg ">
                      <a class="navbar-brand ml-5" href="index.html"><i style="font-size: 30px;">Bonanzagaming</i></a>
                      <a class="navbar-brand ml-5" href="https://www.facebook.com/bonanzagamin">
                          <img src="images/fb.png">
                      </a>
                      <a class="navbar-brand ml-5" href="https://discord.gg/9y8DZky4">
                          <img src="images/discord.png" />
                      </a>
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                          aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"> <span
                              class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse mr-5" id="navbar">
                          <ul class="navbar-nav ml-auto">
                              <li class="nav-item"> <a class="nav-link active" href="index.html">HOME <span
                                          class="sr-only">(current)</span></a> </li>
                              <!-- <li class="nav-item"> <a class="nav-link" href="#features">FEATURES</a> </li> -->
                              <!--  <li class="nav-item"> <a class="nav-link" href="#offers">Offers</a> </li> -->
                              <li class="nav-item"> <a class="nav-link"
                                      href="https://www.facebook.com/profile.php?id=100094956450038">FACEBOOK PAGE</a>
                              </li>
                          </ul>
                      </div>
                  </nav>
              </div>
          </div>
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




      <div class="container-fluid">
          <div class="row">
              <div class="col qr_container">
              </div>
          </div>
      </div>
  </div>
    @else
    @endauth
    <div class="card card-style" style="margin-top: 2em;">
      <div class="content mb-0">
          <h1 class="text-center mb-0"> Other Payments </h1>
          <div class="divider"></div>
      </div>
      <div style="min-height:100px;">
        <h1 style="text-align: center;"><a href="/login" ><span> login to pay...</span></a></h1>
      </div>
    </div>
 
    @endsection
