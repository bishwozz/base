{{-- regular object attribute --}}
@php
    $color = $entry[$column['name']];
@endphp

@if ($color)
    <svg width="60" height="20">
        <rect width="60" height="20" style="fill: {{ $entry[$column['name']] }};" />
    </svg>
@endif

