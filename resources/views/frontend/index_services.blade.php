<div class="card card-style">
    <div class="content mb-0">
      <h1 class="text-center mb-0"> Our Services </h1>
      <p class="text-center color-highlight font-11 mt-n1 pb-0">Tons of Awesome Features just for You.</p>
      <div class="row">
        @foreach ($services as $service )
          
          <div class="service_card">
            <section class="top-card">
              <img src="{{ url('storage/uploads/'. $service->service_img) }}"" alt="img">
            </section>
            <section class="middle-card">
              <h1>{{ $service->title }}</h1>
            </section>
          </div>
        @endforeach
        

      </div>

    </div>
  </div>