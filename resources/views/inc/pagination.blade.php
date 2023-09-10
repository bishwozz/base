@if ($paginator->hasPages())
<div class="pagination-content number">
    <ul class="pagination">
        @if ($paginator->onFirstPage())
        @else
            <li>
                <a href="#" tabindex="-1"><i class="zmdi zmdi-chevron-left"></i></a>
            </li>
        @endif
      
        @foreach ($elements as $element)
            @if (is_string($element))
                <li>{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item current">
                            <a>{{ $page }}</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach
        
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="zmdi zmdi-chevron-right"></i></a>
            </li>
        @else
        @endif
    </ul>
</div>
@endif
