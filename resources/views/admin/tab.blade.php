
{{-- @if ($links)
  <ul class="nav nav-tabs" style="margin-bottom: 10px; margin-top: 10px;">
@foreach ($links as $link)
  <li class="nav-item">
    <a class="nav-link tab-link {{ url()->full() == $link['href'] ? 'active' : ''}}" href="{{ $link['href'] }}">{{ $link['name'] }}</a>
  </li>
  @endforeach
</ul>

@endif --}}

<div class="row">
  <div class='col-md-12' style="margin:10px;">
          <ul class="nav nav-tabs flex-column flex-sm-row"id="myTab" role="tablist">
              @foreach ($links as $tab)   
              @php
                  $active = url($tab['href']) == url()->current() ? 'active': '';
                  // dd($active);
              @endphp
                  <li role="presentation" class="nav-item {{ $active }}">
                  <a class="nav-link tab-link {{ $active }}" href="{{ url($tab['href']) }}" role="tab" >{{ $tab['label'] }} </a>
                  </li>
              @endforeach
          </ul>
  </div>
</div>