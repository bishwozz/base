<div class="row">
  <div class="col-md-12">
        <ul class="nav nav-tabs flex-column flex-sm-row mt-2"id="myTab" role="tablist">
            @foreach ($links as $link)   
            @php
            $activeClass = '';
            $activeClass .= url($link['href']) == url()->current() ? ' active ': '';
            $activeClass .= url($link['href']) == url()->current() ? '': 'bg-primary text-white';
            @endphp
            <li role="presentation" class="nav-item border border-white">
                <a class="nav-link tab-link {{ $activeClass }} p-0 pr-2 pl-2" 
                href="{{ url($link['href']) }}" role="tab">{{ $link['label'] }}</a>
            </li>
            @endforeach
        </ul>
      </div>
</div>