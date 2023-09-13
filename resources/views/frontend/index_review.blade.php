@extends('layout.base')
@section('content')
<div class="page-content header-clear-small">
    <div
      class="card card-style preload-img"
      data-src="images/pictures/18w.jpg"
      data-card-height="150"
    >
      <div class="card-center ms-3">
        <h1 class="color-white mb-0">Components</h1>
        <p class="color-white mt-n1 mb-0">Ready built to create Pages</p>
      </div>
      <div class="card-center me-3">
        <a
          href="#"
          class="back-button btn btn-m float-end rounded-xl shadow-xl text-uppercase font-800 bg-highlight"
          >Back Home</a
        >
      </div>
      {{-- <div class="card-overlay bg-black opacity-80"></div> --}}
    </div>


  </div>


<div class="card card-style">
    <div class="content mb-4">
      <h1 class="text-center mb-0">Care & Quality</h1>
      <p class="text-center color-highlight font-11 mt-n1 pb-0">
        No stone left unturned, no aspect overlooked.
      </p>
      <p class="text-center font-20 mt-n2">
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
      </p>
      <div
        class="splide single-slider slider-no-arrows slider-no-dots"
        id="single-slider-home-quotes"
      >
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide">
              <h2
                class="text-center font-300 line-height-xl content mb-0 mt-0"
              >
                The code is always great with any Enabled template, the
                customer support that wins me over always.
              </h2>
            </div>
            <div class="splide__slide">
              <h2
                class="text-center font-300 line-height-xl content mb-0 mt-0"
              >
                The best support I have ever had, it's so good I purchased
                another theme. Highlighy Recommended.
              </h2>
            </div>
          </div>
        </div>
      </div>
      <a
        href="#"
        class="btn btn-m btn-center-l text-uppercase font-900 bg-highlight rounded-sm shadow-xl mt-4 mb-0"
        >More Testimonials</a
      >
    </div>
  </div>

  @include('frontend.index_footer')
  <div class="divider"></div>
  <div class="divider"></div>
  <div class="divider"></div>
 
    @endsection
