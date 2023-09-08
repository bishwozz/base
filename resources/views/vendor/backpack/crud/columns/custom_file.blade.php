@php
$value = data_get($entry, $column['name']);
@endphp

<center>
    <span>
        @if($value)
            @php
            $data = explode('.', $value);
            $extension = end($data);
            @endphp

            @if($extension == 'pdf')
                <a href="{{asset('storage/uploads/'. $value)}}" target='_blank'><i class="fa fa-file-pdf-o fa-2x text-danger text-decoration-none"></i></a>
            @elseif($extension == 'xlsx' || $extension == 'xls')
                <a href="{{asset('storage/uploads/'. $value)}}" target='_blank'><i class="fa fa-file-excel-o fa-2x text-success text-decoration-none"></i></a>
            @else
                <a href="{{asset('storage/uploads/'. $value)}}" target='_blank'>
                <img src="{{asset('storage/uploads/'. $value)}}" height='30px' width='30px'>
            @endif
        @else
        @endif
    </span>
</center>

