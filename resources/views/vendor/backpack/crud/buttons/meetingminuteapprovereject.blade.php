@php
    $user = backpack_user();
    $show_btn = false;
    $m1_m2_c1_c2 = false;

    // Cabinet 2nd level Permissions
    if($user->hasRole(Config::get('roles.name.cabinet_approver'))){
        if(is_null($entry->meetingMinuteApprovalHistory)){
            $show_btn = true;
        }elseif($entry->meetingMinuteApprovalHistory->role_id >= Config::get('roles.id.chief_secretary') && $entry->meetingMinuteApprovalHistory->status_id == 0){
            $show_btn = true;
        }
        $m1_m2_c1_c2 = true;
        

    }
    // Cabinet 3rd level Permissions
    if($user->hasRole(Config::get('roles.name.chief_secretary'))){

        if($entry->meetingMinuteApprovalHistory->role_id >= Config::get('roles.id.cabinet_approver') && $entry->meetingMinuteApprovalHistory->status_id == 1){
            $show_btn = true;
        }
       
    }
@endphp

@if($show_btn)
    @if($entry->getStatusRejection())
        @if($m1_m2_c1_c2)
            <a onclick="ECABINET.confirmationMeetingMinute('{{$entry->id}}', this)"
            id="approvemeetingMinute-submit"
            class="btn btn-sm btn-info text-white"
            style="cursor: pointer;"
            title="बैठक माइनुट पेश गर्नुहोस">{{ trans('common.resubmitAgenda') }} </a>
        @else
            <a onclick="ECABINET.confirmationMeetingMinute('{{$entry->id}}', this)"
            id="approvemeetingMinute"
            class="btn btn-sm btn-info text-white"
            style="cursor: pointer;"
            title="बैठक माइनुट स्वीकृत गर्नुहोस">{{ trans('common.reapproveAgenda') }} </a>
        @endif
    @else
        @if($m1_m2_c1_c2)
            <a onclick="ECABINET.confirmationMeetingMinute('{{$entry->id}}', this)"
            id="approvemeetingMinute-submit"
            class="btn btn-sm btn-info text-white"
            style="cursor: pointer;"
            title="बैठक माइनुट पेश गर्नुहोस">{{ trans('common.submitAgenda') }} </a>
        @else
            <a onclick="ECABINET.confirmationMeetingMinute('{{$entry->id}}', this)"
            id="approvemeetingMinute"
            class="btn btn-sm btn-info text-white"
            style="cursor: pointer;"
            title="बैठक माइनुट स्वीकृत गर्नुहोस">{{ trans('common.approveAgenda') }} </a>
        @endif
    @endif
    <a data-fancybox data-type="ajax" data-src="{{backpack_url('/meeting-minute-reject-view/'.$entry->id)}}" class="btn btn-sm btn-danger text-white" data-toggle="tooltip" style="cursor: pointer;" title="बैठक माइनुट फिर्ता गर्नुहोस">{{ trans('common.rejectAgenda')}}</a>
@endif