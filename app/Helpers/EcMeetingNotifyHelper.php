<?php

use App\Models\MeetingsRequestApprovalHistory;
use App\Models\EcMeetingRequest;

    // cabinet1
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))) {
        $role_id = Config::get('roles.id.cabinet_creator');
        $reject_ec_meeting = 0;
        $ec_meeting_number_notfy = 0;

        $reject_ec_meeting = MeetingsRequestApprovalHistory::join('ec_meetings_requests', 'ec_meetings_requests.id', '=', 'meetings_request_approval_history.meetings_request_id')
            ->select('ec_meetings_requests.id', 'meetings_request_approval_history.meetings_request_id', 'meetings_request_approval_history.role_id', 'meetings_request_approval_history.status_id')
            ->where('ec_meetings_requests.is_submitted',false)
            ->where('meetings_request_approval_history.role_id',$role_id-1)
            ->where('meetings_request_approval_history.status_id',0)
            ->count();

        $ec_meeting_number_notfy = App\Models\EcMeetingRequest::where('is_submitted', true)
            ->where('is_approved', true)
            ->where('is_mailed',false)
            ->count();


        $total_ec_meeting = $reject_ec_meeting + $ec_meeting_number_notfy;

    }

    // cabiner2
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))) {
        $role_id = Config::get('roles.id.cabinet_approver');
        $cabinet2_ec_meeting = 0;
        $reject_ec_meeting = 0;

        $cabinet2_ec_meeting = EcMeetingRequest::where('is_submitted', true)
            ->where('level_id', 2)
            ->count();

        $reject_ec_meeting = MeetingsRequestApprovalHistory::join('ec_meetings_requests', 'ec_meetings_requests.id', '=', 'meetings_request_approval_history.meetings_request_id')
            ->select('ec_meetings_requests.id', 'meetings_request_approval_history.meetings_request_id', 'meetings_request_approval_history.role_id', 'meetings_request_approval_history.status_id')
            ->where('ec_meetings_requests.is_submitted',false)
            ->where('meetings_request_approval_history.role_id',$role_id-1)
            ->where('meetings_request_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_ec_meeting;
        $total = $cabinet2_ec_meeting;
        $total_ec_meeting = ($total_reject + $total );
    }

    // cabiner3
    if (backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))) {
        $role_id = Config::get('roles.id.chief_secretary');
        $cabinet2_ec_meeting = 0;
        $reject_ec_meeting = 0;

        $cabinet2_ec_meeting = EcMeetingRequest::where('is_submitted', true)
            ->where('level_id', 3)
            ->count();

        $reject_ec_meeting = MeetingsRequestApprovalHistory::join('ec_meetings_requests', 'ec_meetings_requests.id', '=', 'meetings_request_approval_history.meetings_request_id')
            ->select('ec_meetings_requests.id', 'meetings_request_approval_history.meetings_request_id', 'meetings_request_approval_history.role_id', 'meetings_request_approval_history.status_id')
            ->where('ec_meetings_requests.is_submitted',false)
            ->where('meetings_request_approval_history.role_id',$role_id-1)
            ->where('meetings_request_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_ec_meeting;
        $total = $cabinet2_ec_meeting;
        $total_ec_meeting = ($total_reject + $total );
    }

?>
