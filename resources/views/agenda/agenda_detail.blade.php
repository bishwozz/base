@php 
    $value = json_decode($agenda_detail->file_upload);
@endphp

<div class="card" style="width:90%">
    <div class="card-header">
        <h3>{{ trans('common.agendaDetails') }} </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <b>{{ trans('common.agendaNumber') }}:&nbsp;</b> {{ $agenda_detail->agenda_number }}
            </div>
            
            <div class="col-4">
                <b>{{ trans('common.agendaType') }}:&nbsp;</b> {{ $agenda_detail->agenda_type->name_lc }}
            </div>
            <div class="col-4">
                <b>फाइल: &nbsp;</b>
                @if($value && count($value))
                    @foreach ($value as $file_path)
                        <?php
                            $data = explode('.', $file_path);
                            $extension = $data[1];
                            $path = $data[0];
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
                    @endforeach
                @else
                    ----
                @endif
            </div>
            <div class="col-12 mt-2">
                <b>{{ trans('common.agendaTitle') }}:&nbsp;</b> {{ $agenda_detail->agenda_title }}
            </div>
            <div class="col-12 mt-2">
                <b>{{ trans('common.agendaDescription') }}:&nbsp;</b> {{ $agenda_detail->agenda_description }}
            </div>
        </div>
    </div>
</div>