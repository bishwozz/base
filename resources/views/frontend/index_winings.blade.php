{{-- <div class="card card-style" style="">
  <div class="content mb-0"> --}}

      <div class="winning wrapper">
        <div class="my-slider" id="my-slider">
            @foreach ($slideshows as $slideshow )
              <div >
                <img src="{{ url('storage/uploads/'.$slideshow->img_path) }}">
              </div>
            @endforeach
          </div>
      </div>

  {{-- </div>
</div> --}}