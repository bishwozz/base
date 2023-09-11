

    <div class="splide single-slider slider-no-arrows slider-no-dots" id="single-slider-home">
      <div class="splide__track">
        <div class="splide__list">
            @foreach ($sliders as $slider )
                <div class="splide__slide">
                    <div class="card rounded-m shadow-l mx-3">
                        <div class="card-bottom text-center mb-0">
                        <h1 class="color-white font-700 mb-n1">{{ $slider->title }}</h1>
                        <p class="color-white opacity-80 mb-4">{!! $slider->description !!}</p>
                        </div>
                        <div class="card-overlay bg-gradient"></div>
                        <img class="img-fluid" src="{{ url('storage/uploads/'. $slider->file_upload) }}">
                    </div>
                </div>
            @endforeach
        </div>
      </div>
    </div>