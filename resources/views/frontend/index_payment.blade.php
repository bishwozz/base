<div class="card card-style">
  <div class="content mb-0 payment">
    <h1 class="text-center mb-0"> Pay Us Via </h1>
    <p class="text-center color-highlight font-11 mt-n1 pb-0">Tons of Awesome Features just for You.</p>
          <div class="container">
            <div class="row">
              @foreach ($payments as $payment )
                <div class="col-6">
                    @auth
                      <div class="modal_wrap">
                        <input id="trigger-{{ $payment->id }}" type="checkbox">
                        <div class="modal_overlay">
                          <label for="trigger-{{ $payment->id }}" class="modal_trigger"></label>
                          <div class="modal_content">
                            <label for="trigger-{{ $payment->id }}" class="close_button">✖️</label>
                            <h2>Scan and Pay </h2>
                            <img src="{{ url('storage/uploads/'.$payment->qr_img) }}" alt="" width="100%;">
                            <p style="text-align: center; font-size:12px;font-weight: 600;">{{ $payment->qr_address }}</p>
                          </div>
                        </div>
                      </div>
                    @else
                      <div class="modal_wrap">
                        <input id="trigger-{{ $payment->id }}" type="checkbox">
                        <div class="modal_overlay">
                          <label for="trigger-{{ $payment->id }}" class="modal_trigger"></label>
                          <div class="modal_content">
                            <label for="trigger-{{ $payment->id }}" class="close_button">✖️</label>
                            <h2>Scan and Pay </h2>
                            <a href="/login"> Login to pay...</a>
                          </div>
                        </div>
                      </div>
                    @endauth
                    <label for="trigger-{{ $payment->id }}" class="card">
                      <h5>{{ $payment->title }}</h5>
                      <p><img src="{{ url('storage/uploads/'.$payment->icon) }}" width="80" alt="Visa"></p>
                    </label>
                </div>
              @endforeach



            <div class="col-6">
              @auth
                {{-- <div class="modal_wrap">
                  <input id="trigger" type="checkbox">
                  <div class="modal_overlay">
                    <label for="trigger" class="modal_trigger"></label>
                    <div class="modal_content">
                      <label for="trigger" class="close_button">✖️</label>
                      <h2>Scan and Pay </h2>
                      <img src="{{ url('storage/uploads/'.$payment->qr_img) }}" alt="" width="100%;">
                      <p style="text-align: center; font-size:12px;font-weight: 600;">{{ $payment->qr_address }}</p>
                    </div>
                  </div>
                </div> --}}
                <a href="/payment">
                  <label for="trigger" class="card">
                    <h5>Bank</h5>
                    <p><img src=""{{ asset('frontend/css/img/icons/bank.png') }}" width="80" alt="bank"></p>
                  </label>
                </a>
              @else
                <div class="modal_wrap">
                  <input id="trigger" type="checkbox">
                  <div class="modal_overlay">
                    <label for="trigger" class="modal_trigger"></label>
                    <div class="modal_content">
                      <label for="trigger" class="close_button">✖️</label>
                      <h2>Scan and Pay </h2>
                      <a href="/login"> Login to pay...</a>
                    </div>
                  </div>
                </div>
                <label for="trigger" class="card">
                  <h5>Bank</h5>
                  <p><img src="{{ asset('frontend/css/img/icons/bank.png') }}" width="80" alt="bank"></p>
                </label>
              @endauth
             
          </div>


              

 
            </div>
          </div>

    




  </div>
</div>