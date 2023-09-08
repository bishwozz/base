@php
    $file_path = data_get($entry, $column['name']);
@endphp
<span>
    @if ($file_path)
        
        <?php
        $data = explode('.', $file_path);
		$extension = $data[1];
        $path=$data[0];
        // dd($path); 
        ?>

        @if($extension == 'pdf')
            <a href="{{asset('storage/uploads/'. $file_path)}}" data-fancybox data-caption="" ><i class="las la-file-pdf"  style="color:red; font-size:40px "></i></a>
        @elseif($extension == 'xlsx' || $extension == 'xls')
            <a href="{{asset('storage/uploads/'. $file_path)}}"  ><i class="las la-file-excel" style="color:green;font-size:40px"></i></a>
        @elseif($extension == 'doc' || $extension == 'docx')
            <a href="{{asset('storage/uploads/'. $file_path)}}"  ><i class="las la-file-word" style="color:blue;font-size:40px"></i></a>
        @else
            <a href="{{asset('storage/uploads/'. $file_path)}}" data-fancybox data-caption=""  >
                <img src="{{asset('storage/uploads/'. $file_path)}}" height='70px' width='70px'>
            </a>
        @endif
    @else
        ----
    @endif
</span>

