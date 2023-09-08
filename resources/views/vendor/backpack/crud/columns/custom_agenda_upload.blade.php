@if ($crud->hasAccess('update'))
@php
    $actionlist= \App\Models\MeetingMinuteDetail::find($entry->getKey());
    if($actionlist->file_upload){
        $data = explode('.', $actionlist->file_upload);
        // dd($data);
        $extension = $data[1];
        // dd($extension);
    }

@endphp
    @if($actionlist->file_upload)

        @if($extension == 'pdf')
            <a href="{{asset('storage/uploads/'. $actionlist->file_upload)}}" target='_blank'><i class="la la-file-pdf-o fa-2x" style="color:red; text-decoration:none;"></i></a>
        @else
            <i class="fa fa-image"></i>
        @endif
    {{-- <span><i class="fa fa-check-circle" style="color:green; font-weight:bold;"></i></span> --}}
    @else
        <a data-fancybox data-type="ajax" href={{backpack_url('meeting-minute-detail/'.$entry->getKey().'/uploaddialog')}}  class="btn btn-sm btn-primary"><i class="fa fa-upload">अपलोड </i></a>
    @endif
@endif