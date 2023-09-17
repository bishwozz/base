@extends('layout.base')
@section('content')
    <div class="page-content header-clear-small">
        <div class="card card-style preload-img" data-src="images/pictures/18w.jpg" data-card-height="150" style="background-color: black;">
            <div class="card-center ms-3">
                <h1 class="color-white mb-0">Review</h1>
                {{-- <p class="color-white mt-n1 mb-0">Ready built to create Pages</p> --}}
            </div>
            <div class="card-center me-3">
                <a href="#"
                    class="back-button btn btn-m float-end rounded-xl shadow-xl text-uppercase font-800 bg-highlight">Back
                    Home</a>
            </div>


        </div>
    </div>

    <div class="page-content header-clear-small">
        <div class="card card-style preload-img" data-src="images/pictures/18w.jpg" data-card-height="200">
            <div class="p-3">
                <h1> Look like you have not given feedback ...</h1>
            </div>
            <br>
            <button type="button" class="back-button btn btn-m rounded-xl shadow-xl text-uppercase font-800 bg-highlight"
                id="reviewFancyBox">
                Rate us
            </button>




        </div>

    </div>
@include('frontend.review_fancy_box')

    @if ($review)
        <div class="card card-style">
            <div class="content mb-4">
                <h1 class="text-center mb-0">Testimonials</h1>
                <p class="text-center color-highlight font-11 mt-n1 pb-0">
                    Look at what our client says.
                </p>
                <div class="splide single-slider slider-no-arrows slider-no-dots" id="single-slider-home-quotes">
                    <div class="splide__track">
                        <div class="splide__list">

                            <div class="splide__slide">
                                <p class="text-center font-20 mt-n2">
                                    <i class="fa fa-star @if ($review->rating >= 1) color-yellow-dark @endif "></i>
                                    <i class="fa fa-star @if ($review->rating >= 2) color-yellow-dark @endif "></i>
                                    <i class="fa fa-star @if ($review->rating >= 3) color-yellow-dark @endif "></i>
                                    <i class="fa fa-star @if ($review->rating >= 4) color-yellow-dark @endif "></i>
                                    <i class="fa fa-star @if ($review->rating >= 5) color-yellow-dark @endif "></i>
                                </p>
                                <h2 class="text-center font-300 line-height-xl content mb-0 mt-0">
                                    {!! $review->comment !!}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div style="text-align: center;">
                    <a href="/review"
                        class="btn btn-m btn-center-l text-uppercase font-900 bg-highlight rounded-sm shadow-xl mt-4 mb-0"
                        style="text-align: center;">Gives us your review</a>
                </div>
            </div>
        </div>
    @endif


    @include('frontend.index_footer')
    <div class="divider"></div>
    <div class="divider"></div>
    <div class="divider"></div>
@endsection
