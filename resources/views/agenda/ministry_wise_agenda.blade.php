<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div>
    <table class="table table-striped table-bordered table-hover my-4 mr-2" style="background-color:#eefdf9; display:inline-table !important;">
        {{-- current organization --}}
        <thead>
            <tr><th colspan="10" class="text-center font-weight-bold text-white bg-success" style="font-size: 18px;">{{ $ministry->name_lc??''}}</th></tr>
            <tr>
                <th class="text-center w-1">{{trans('common.row_number')}}</th>
                <th class="report-heading-second">{{trans('common.agendaTitle')}}</th>
                <th class="report-heading-second">{{trans('common.agendaType')}}</th>
                <th class="report-heading-second">{{trans('common.meeting_count_date')}}</th>
                <th class="report-heading-second">{{trans('common.file_upload')}}</th>
                <th class="report-heading-second">{{trans('common.maapa')}}</th>
                <th class="report-heading-second">{{trans('common.samiti')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agendas as $agenda)
            <tr>
                <td class="report-data text-center">{{convertToNepaliNumber($loop->iteration) }}</td>
                <td class="report-data-second text-left">{{$agenda->agenda_title}}</td>
                <td class="report-data-second text-left">{{$agenda->agenda_type->name_lc}}</td>
                <td class="report-data-second text-left">{{convertToNepaliNumber($agenda->ecMeetingRequest->meeting_code??'')}} - {{ convertToNepaliNumber($agenda->created_at->format('Y-m-d')) }}</td>
                <td>
                    @php
                        $files = json_decode($agenda->file_upload);
                    @endphp
                    @if ($files)
                        @foreach($files as $file)
                                <a class="fancybox" target="_blank" type="iframe" href="{{ asset('storage/uploads/').'/'.$file}}"  title='हेर्नुहोस'>
                                    <i class="fas fa-file-pdf text-danger ml-2"></i>
                                </a>
                        @endforeach
                    @endif

                </td>
                <td class="report-data-second"> {{ $agenda->decision_of_cabinet }} </td>
                <td class="report-data-second"> {{ $agenda->decision_of_committee }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>

    .fas {
        text-align: center !important;
    }
</style>
