<div class="card card-style">
    <div class="content mb-4">
        <h1 class="text-center mb-0">Testimonials</h1>
        <p class="text-center color-highlight font-11 mt-n1 pb-0">
            Look at what our client says.
        </p>
        
        <div class="splide single-slider slider-no-arrows slider-no-dots" id="single-slider-home-quotes">
            <div class="splide__track">
                <div class="splide__list">
                    @foreach ($reviews as $review )
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
                    @endforeach
                    

                    {{-- <div class="splide__slide">
                        <p class="text-center font-20 mt-n2">
                          <i class="fa fa-star color-yellow-dark"></i>
                          <i class="fa fa-star color-yellow-dark"></i>
                          <i class="fa fa-star color-yellow-dark"></i>
                          <i class="fa fa-star color-yellow-dark"></i>
                          <i class="fa fa-star "></i>
                      </p>
                        <h2 class="text-center font-300 line-height-xl content mb-0 mt-0">
                            The best support I have ever had, it's so good I purchased
                            another theme. Highlighy Recommended.
                        </h2>
                    </div> --}}

                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div style="text-align: center;">
          <a href="/review"
              class="btn btn-m btn-center-l text-uppercase font-900 bg-highlight rounded-sm shadow-xl mt-4 mb-0" style="text-align: center;">Gives us your review</a>
        </div>
    </div>
</div>
