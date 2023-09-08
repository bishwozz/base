<?php

use App\Models\MeetingMinuteApprovalHistory;
use App\Models\MeetingMinuteDetail;

    // cabinet1
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))) {
        $role_id = Config::get('roles.id.cabinet_creator');
        $reject_ec_meeting = 0;
        $meeting_minute_number_notfy = 0;

        $reject_ec_meeting = MeetingMinuteApprovalHistory::join('ec_meeting_minute_details', 'ec_meeting_minute_details.id', '=', 'meeting_minute_approval_history.meeting_minute_id')
            ->select('ec_meeting_minute_details.id', 'meeting_minute_approval_history.meeting_minute_id', 'meeting_minute_approval_history.role_id', 'meeting_minute_approval_history.status_id')
            ->where('ec_meeting_minute_details.is_submitted',false)
            ->where('meeting_minute_approval_history.role_id',$role_id-1)
            ->where('meeting_minute_approval_history.status_id',0)
            ->count();


        $meeting_minute_number_notfy = App\Models\MeetingMinuteDetail::where('is_submitted', true)
            ->where('is_approved', true)
            ->where('is_mailed',false)
            ->count();

        $total_meeting_minute = $reject_ec_meeting + $meeting_minute_number_notfy;
    }

    // cabiner2
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))) {
        $role_id = Config::get('roles.id.cabinet_approver');
        $cabinet2_ec_meeting = 0;
        $reject_ec_meeting = 0;

        $cabinet2_ec_meeting = MeetingMinuteDetail::where('is_submitted', true)
            ->where('level_id', 2)
            ->count();

        $reject_ec_meeting = MeetingMinuteApprovalHistory::join('ec_meeting_minute_details', 'ec_meeting_minute_details.id', '=', 'meeting_minute_approval_history.meeting_minute_id')
            ->select('ec_meeting_minute_details.id', 'meeting_minute_approval_history.meeting_minute_id', 'meeting_minute_approval_history.role_id', 'meeting_minute_approval_history.status_id')
            ->where('ec_meeting_minute_details.is_submitted',false)
            ->where('meeting_minute_approval_history.role_id',$role_id-1)
            ->where('meeting_minute_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_ec_meeting;
        $total = $cabinet2_ec_meeting;
        $total_meeting_minute = ($total_reject + $total );
    }

    // cabiner3
    if (backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))) {
        $role_id = Config::get('roles.id.chief_secretary');
        $cabinet2_ec_meeting = 0;
        $reject_ec_meeting = 0;

        $cabinet2_ec_meeting = MeetingMinuteDetail::where('is_submitted', true)
            ->where('level_id', 3)
            ->count();

        $reject_ec_meeting = MeetingMinuteApprovalHistory::join('ec_meeting_minute_details', 'ec_meeting_minute_details.id', '=', 'meeting_minute_approval_history.meeting_minute_id')
            ->select('ec_meeting_minute_details.id', 'meeting_minute_approval_history.meeting_minute_id', 'meeting_minute_approval_history.role_id', 'meeting_minute_approval_history.status_id')
            ->where('ec_meeting_minute_details.is_submitted',false)
            ->where('meeting_minute_approval_history.role_id',$role_id-1)
            ->where('meeting_minute_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_ec_meeting;
        $total = $cabinet2_ec_meeting;
        $total_meeting_minute = ($total_reject + $total );
    }

?>
