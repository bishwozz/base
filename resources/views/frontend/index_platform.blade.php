<div class="card card-style">
    <div class="content mb-0">
      <h1 class="text-center mb-0"> Our Hottest Platforms </h1>
      {{-- <p class="text-center color-highlight font-11 mt-n1">The Absolute Best Products & Care for You</p> --}}
     
      <div class="divider"></div>
    </div>

    <div class="row me-2 ms-2 mb-0">
      @foreach ($games as $game )
          <div class="col-6 text-center">
              {{-- 43X39 --}}
              <img src="{{ url('storage/uploads/'. $game->game_img) }}" alt="game" width="45"/>
          {{-- <i class="fa fa-trophy color-yellow-dark fa-3x"></i> --}}
          <h2 class="mt-3 mb-1">{{ $game->title }}</h2>
          </div>
      @endforeach
    </div>

  </div>