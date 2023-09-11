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


          {{-- <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-1">
              <div class="Card-image">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/62127/creditcard-mastercard.svg" alt="Mastercard">
              </div>
              <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; 0000</code></pre>
              <p class="Card-expiry">Expires July 2018</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-1" 
              aria-hidden="true">
              <div class="Tile-content">
                <div class="Grid Grid--withGutter">
                  <div class="Grid-cell u-md-size1of2">
                    <label>
                      Card Number
                      <input class="Input is-mastercard" type="text" value="XXXX XXXX XXXX 0000">
                    </label>
                    <label>
                      Full Name on Card
                      <input class="Input" type="text" value="John Smith">
                    </label>
                    <div class="Grid Grid--withGutter u-marginTop">
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Expiration Date
                          <input class="Input" type="text" value="07 / 18" placeholder="MM / YY">
                        </label>
                      </div>
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Security Code
                          <input class="Input" type="text" value="XXX">
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="Grid-cell u-md-size1of2 u-marginTop u-md-no-marginTop">
                    <label>
                      Address Line 1
                      <input class="Input" type="text" value="208 SW 1st Ave">
                    </label>
                    <label>
                      Address Line 2
                      <input class="Input" type="text" value="Ste. 240">
                    </label>
                    <label>
                      Zip/Postal Code
                      <input class="Input" type="text" value="97204">
                    </label>
                  </div>
                </div>
                <div class="u-textRight">
                  <button class="Button js-toggle-tile" type="button">Never Mind</button>
                  <button class="Button Button--primary js-toggle-tile" type="submit">Save</button>
                </div>
              </div>
            </form>
          </div>
          <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-2">
              <div class="Card-image">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/62127/creditcard-amex.svg" alt="American Express">
              </div>
              <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679;&#9679;&#9679; &#9679;1001</code></pre>
              <p class="Card-expiry">Expires February 2019</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-2" 
              aria-hidden="true">
              <div class="Tile-content">
                <div class="Grid Grid--withGutter">
                  <div class="Grid-cell u-md-size1of2">
                    <label>
                      Card Number
                      <input class="Input is-amex" type="text" value="XXXX XXXXXX X1001">
                    </label>
                    <label>
                      Full Name on Card
                      <input class="Input" type="text" value="John Smith">
                    </label>
                    <div class="Grid Grid--withGutter u-marginTop">
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Expiration Date
                          <input class="Input" type="text" value="02 / 19" placeholder="MM / YY">
                        </label>
                      </div>
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Security Code
                          <input class="Input" type="text" value="XXX">
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="Grid-cell u-md-size1of2 u-marginTop u-md-no-marginTop">
                    <label>
                      Address Line 1
                      <input class="Input" type="text" value="208 SW 1st Ave">
                    </label>
                    <label>
                      Address Line 2
                      <input class="Input" type="text" value="Ste. 240">
                    </label>
                    <label>
                      Zip/Postal Code
                      <input class="Input" type="text" value="97204">
                    </label>
                  </div>
                </div>
                <div class="u-textRight">
                  <button class="Button js-toggle-tile" type="button">Never Mind</button>
                  <button class="Button Button--primary js-toggle-tile" type="submit">Save</button>
                </div>
              </div>
            </form>
          </div>
          <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-3">
              <div class="Card-image">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/62127/creditcard-visa.svg" alt="Visa">
              </div>
              <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; 1234</code></pre>
              <p class="Card-expiry">Expires May 2017</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-3" 
              aria-hidden="true">
              <div class="Tile-content">
                <div class="Grid Grid--withGutter">
                  <div class="Grid-cell u-md-size1of2">
                    <label>
                      Card Number
                      <input class="Input is-visa" type="text" value="XXXX XXXX XXXX 1234">
                    </label>
                    <label>
                      Full Name on Card
                      <input class="Input" type="text" value="John Smith">
                    </label>
                    <div class="Grid Grid--withGutter u-marginTop">
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Expiration Date
                          <input class="Input" type="text" value="05 / 17" placeholder="MM / YY">
                        </label>
                      </div>
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Security Code
                          <input class="Input" type="text" value="XXX">
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="Grid-cell u-md-size1of2 u-marginTop u-md-no-marginTop">
                    <label>
                      Address Line 1
                      <input class="Input" type="text" value="208 SW 1st Ave">
                    </label>
                    <label>
                      Address Line 2
                      <input class="Input" type="text" value="Ste. 240">
                    </label>
                    <label>
                      Zip/Postal Code
                      <input class="Input" type="text" value="97204">
                    </label>
                  </div>
                </div>
                <div class="u-textRight">
                  <button class="Button js-toggle-tile" type="button">Never Mind</button>
                  <button class="Button Button--primary js-toggle-tile" type="submit">Save</button>
                </div>
              </div>
            </form>
          </div>
          <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-4">
              <div class="Card-image">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/62127/creditcard-mastercard.svg" alt="Mastercard">
              </div>
              <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; 0000</code></pre>
              <p class="Card-expiry">Expires July 2018</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-4" 
              aria-hidden="true">
              <div class="Tile-content">
                <div class="Grid Grid--withGutter">
                  <div class="Grid-cell u-md-size1of2">
                    <label>
                      Card Number
                      <input class="Input is-mastercard" type="text" value="XXXX XXXX XXXX 0000">
                    </label>
                    <label>
                      Full Name on Card
                      <input class="Input" type="text" value="John Smith">
                    </label>
                    <div class="Grid Grid--withGutter u-marginTop">
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Expiration Date
                          <input class="Input" type="text" value="07 / 18" placeholder="MM / YY">
                        </label>
                      </div>
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Security Code
                          <input class="Input" type="text" value="XXX">
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="Grid-cell u-md-size1of2 u-marginTop u-md-no-marginTop">
                    <label>
                      Address Line 1
                      <input class="Input" type="text" value="208 SW 1st Ave">
                    </label>
                    <label>
                      Address Line 2
                      <input class="Input" type="text" value="Ste. 240">
                    </label>
                    <label>
                      Zip/Postal Code
                      <input class="Input" type="text" value="97204">
                    </label>
                  </div>
                </div>
                <div class="u-textRight">
                  <button class="Button js-toggle-tile" type="button">Never Mind</button>
                  <button class="Button Button--primary js-toggle-tile" type="submit">Save</button>
                </div>
              </div>
            </form>
          </div>
          <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-5">
              <div class="Card-image">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/62127/creditcard-amex.svg" alt="American Express">
              </div>
              <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679;&#9679;&#9679; &#9679;1001</code></pre>
              <p class="Card-expiry">Expires February 2019</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-5" 
              aria-hidden="true">
              <div class="Tile-content">
                <div class="Grid Grid--withGutter">
                  <div class="Grid-cell u-md-size1of2">
                    <label>
                      Card Number
                      <input class="Input is-amex" type="text" value="XXXX XXXXXX X1001">
                    </label>
                    <label>
                      Full Name on Card
                      <input class="Input" type="text" value="John Smith">
                    </label>
                    <div class="Grid Grid--withGutter u-marginTop">
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Expiration Date
                          <input class="Input" type="text" value="02 / 19" placeholder="MM / YY">
                        </label>
                      </div>
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Security Code
                          <input class="Input" type="text" value="XXX">
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="Grid-cell u-md-size1of2 u-marginTop u-md-no-marginTop">
                    <label>
                      Address Line 1
                      <input class="Input" type="text" value="208 SW 1st Ave">
                    </label>
                    <label>
                      Address Line 2
                      <input class="Input" type="text" value="Ste. 240">
                    </label>
                    <label>
                      Zip/Postal Code
                      <input class="Input" type="text" value="97204">
                    </label>
                  </div>
                </div>
                <div class="u-textRight">
                  <button class="Button js-toggle-tile" type="button">Never Mind</button>
                  <button class="Button Button--primary js-toggle-tile" type="submit">Save</button>
                </div>
              </div>
            </form>
          </div> --}}

          <div class="Tile js-tile">
            <div class="Tile-content Tile-content--toggle js-toggle-tile"
              role="button"
              tabindex="0"
              aria-expanded="false"
              aria-controls="edit-flyout-4">
              <div class="Card-image">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/62127/creditcard-mastercard.svg" alt="Mastercard">
              </div>
              <pre class="Card-code"><code>&#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; &#9679;&#9679;&#9679;&#9679; 0000</code></pre>
              <p class="Card-expiry">Expires July 2018</p>
            </div>
            <form class="Tile-flyout js-tile-flyout"
              id="edit-flyout-4" 
              aria-hidden="true">
              <div class="Tile-content">
                <div class="Grid Grid--withGutter">
                  <div class="Grid-cell u-md-size1of2">
                    <label>
                      Card Number
                      <input class="Input is-mastercard" type="text" value="XXXX XXXX XXXX 0000">
                    </label>
                    <label>
                      Full Name on Card
                      <input class="Input" type="text" value="John Smith">
                    </label>
                    <div class="Grid Grid--withGutter u-marginTop">
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Expiration Date
                          <input class="Input" type="text" value="07 / 18" placeholder="MM / YY">
                        </label>
                      </div>
                      <div class="Grid-cell u-size1of2">
                        <label>
                          Security Code
                          <input class="Input" type="text" value="XXX">
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="Grid-cell u-md-size1of2 u-marginTop u-md-no-marginTop">
                    <label>
                      Address Line 1
                      <input class="Input" type="text" value="208 SW 1st Ave">
                    </label>
                    <label>
                      Address Line 2
                      <input class="Input" type="text" value="Ste. 240">
                    </label>
                    <label>
                      Zip/Postal Code
                      <input class="Input" type="text" value="97204">
                    </label>
                  </div>
                </div>
                <div class="u-textRight">
                  <button class="Button js-toggle-tile" type="button">Never Mind</button>
                  <button class="Button Button--primary js-toggle-tile" type="submit">Save</button>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>