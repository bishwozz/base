@php
    $value = data_get($entry, $column['name']);

    if (is_string($value)) {
        $values = json_decode($value, true) ?? [];
    } else {
        $values = $value;
    }
@endphp
<span>
    @if ($values && count($values))
        @foreach ($values as $file_path)

        @php
            $data = explode('.', $file_path);
            $extension = $data[1];
        @endphp

        @if($extension == 'pdf')
            <a href="{{asset('storage/uploads/'. $file_path)}}" target='_blank'><i class="la la-file-pdf-o fa-2x text-danger text-decoration-none"></i></a>
        @else
           <a href="{{asset('storage/uploads/'. $file_path)}}" target='_blank'>
            <img src="{{asset('storage/uploads/'. $file_path)}}" height='30px' width='30px'>
        @endif
        @endforeach
    @else
        ----
    @endif
</span>
