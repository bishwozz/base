<div class="card card-style" style="background: antiquewhite !important;">
  <div class="content mb-0">
    <h1 class="text-center mb-0"> Pay Us Via </h1>
    <p class="text-center color-highlight font-11 mt-n1 pb-0">Tons of Awesome Features just for You.</p>
    <div class="row">
      <div class="Tiles">
        @foreach ($payments as $payment )
          <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-{{ $payment->id }}">
              <div class="Card-image">
                <img src="{{ url('storage/uploads/'.$payment->icon) }}" alt="Visa">
              </div>
              {{-- <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; 1234</code></pre> --}}
              <p class="Card-expiry">{{ $payment->title }}</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-{{ $payment->id }}" 
              aria-hidden="true">
              @auth
                <div class="Tile-content">
                  <div class="Grid Grid--withGutter">
                    <div class="Grid-cell u-md-size1of2">
                      <div class="u-textRight">
                        <span style="margin:0; padding-right: 5em;">Scan and Pay</span>
                        <button class="Button js-toggle-tile" type="button"><i class="fa fa-times"></i></button>
                      </div>
                      <div class="Grid Grid--withGutter u-marginTop">
                        <div class="Grid-cell u-size1of2">
                          <img src="{{ url('storage/uploads/'.$payment->qr_img) }}" alt="" width="100%;">
                          <h5 class="text-center">
                            {{ $payment->qr_address }}
                          </h5>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              @else
                <div class="Tile-content">
                  <div class="Grid Grid--withGutter">
                    <div class="Grid-cell u-md-size1of2">
                      <div class="u-textRight">
                        <span style="margin:0; padding-right: 5em;">Scan and Pay</span>
                        <button class="Button js-toggle-tile" type="button"><i class="fa fa-times"></i></button>
                      </div>

                      <div class="Grid Grid--withGutter u-marginTop">
                        <div class="Grid-cell u-size1of2">
                          <h5 class="text-center">
                           <a href="/login"> Login to pay...</a>
                          </h5>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              @endauth
              
            </form>
          </div>
        @endforeach

      </div>
    </div>
  </div>
</div>