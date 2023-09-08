<?php

use App\Models\AgendaApprovalHistory;
    $lang = lang();
    $total_reject = null;
    //ministry1
    if (backpack_user()->hasRole(Config::get('roles.name.ministry_creator'))) {
        $role_id = Config::get('roles.id.ministry_creator');
        $total_reject = 0;
        $total = 0;

        $reject_agenda = AgendaApprovalHistory::join('agendas', 'agendas.id', '=', 'agenda_approval_history.agenda_id')
            ->select('agendas.id', 'agenda_approval_history.agenda_id', 'agenda_approval_history.role_id', 'agenda_approval_history.status_id')
            ->where('agendas.is_submitted',false)
            ->where('agenda_approval_history.role_id',$role_id-1)
            ->where('agenda_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_agenda;
        $final_total = $total_reject;
    }

    //ministry2
    if (backpack_user()->hasRole(Config::get('roles.name.ministry_reviewer'))) {
        $role_id = Config::get('roles.id.ministry_reviewer');
        $total_reject = 0;
        $total = 0;

        $ministry2_agenda = App\Models\Agenda::where('is_submitted', true)
            ->where('level_id', 2)
            ->count();

        $reject_agenda = AgendaApprovalHistory::join('agendas', 'agendas.id', '=', 'agenda_approval_history.agenda_id')
            ->select('agendas.id', 'agenda_approval_history.agenda_id', 'agenda_approval_history.role_id', 'agenda_approval_history.status_id')
            ->where('agendas.is_submitted',false)
            ->where('agenda_approval_history.role_id',$role_id-1)
            ->where('agenda_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_agenda;
        $total = $ministry2_agenda;
        $final_total = ($total_reject + $total );
    }

    //ministry3
    if (backpack_user()->hasRole(Config::get('roles.name.ministry_secretary'))) {
        $role_id = Config::get('roles.id.ministry_secretary');
        $total_reject = 0;
        $total = 0;

        $ministry3_agenda = App\Models\Agenda::where('is_submitted', true)
            ->where('level_id', 3)
            ->count();

        $reject_agenda = AgendaApprovalHistory::join('agendas', 'agendas.id', '=', 'agenda_approval_history.agenda_id')
            ->select('agendas.id', 'agenda_approval_history.agenda_id', 'agenda_approval_history.role_id', 'agenda_approval_history.status_id')
            ->where('agendas.is_submitted',false)
            ->where('agenda_approval_history.role_id',$role_id-1)
            ->where('agenda_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_agenda;
        $total = $ministry3_agenda;
        $final_total = ($total_reject + $total );
    }

    // cabinet1
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))) {
        $role_id = Config::get('roles.id.cabinet_creator');
        $total_reject = 0;
        $agenda_number_notfy = 0;
        $total = 0;

        $cabinet1_agenda = App\Models\Agenda::where('is_submitted', true)
            ->where('level_id', 4)
            ->count();

        $reject_agenda = AgendaApprovalHistory::join('agendas', 'agendas.id', '=', 'agenda_approval_history.agenda_id')
            ->select('agendas.id', 'agenda_approval_history.agenda_id', 'agenda_approval_history.role_id', 'agenda_approval_history.status_id')
            ->where('agendas.is_submitted',false)
            ->where('agenda_approval_history.role_id',$role_id-1)
            ->where('agenda_approval_history.status_id',0)
            ->count();

        $agenda_number_notfy = App\Models\Agenda::where('is_submitted', true)
            ->where('is_approved', true)
            ->where('agenda_number',null)
            ->count();


        $total_reject = $reject_agenda;
        $total = $cabinet1_agenda;
        $final_total = ($total_reject + $total + $agenda_number_notfy);
    }

    // cabiner2
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))) {
        $role_id = Config::get('roles.id.cabinet_approver');
        $total_reject = 0;
        $total = 0;

        $cabinet2_agenda = App\Models\Agenda::where('is_submitted', true)
            ->where('level_id', 5)
            ->count();

        $reject_agenda = AgendaApprovalHistory::join('agendas', 'agendas.id', '=', 'agenda_approval_history.agenda_id')
            ->select('agendas.id', 'agenda_approval_history.agenda_id', 'agenda_approval_history.role_id', 'agenda_approval_history.status_id')
            ->where('agendas.is_submitted',false)
            ->where('agenda_approval_history.role_id',$role_id-1)
            ->where('agenda_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_agenda;
        $total = $cabinet2_agenda;
        $final_total = ($total_reject + $total );
    }

    // cabiner3
    if (backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))) {
        $role_id = Config::get('roles.id.chief_secretary');
        $total_reject = 0;
        $total = 0;

        $cabinet3_agenda = App\Models\Agenda::where('is_submitted', true)
            ->where('level_id', 6)
            ->count();

        $reject_agenda = AgendaApprovalHistory::join('agendas', 'agendas.id', '=', 'agenda_approval_history.agenda_id')
            ->select('agendas.id', 'agenda_approval_history.agenda_id', 'agenda_approval_history.role_id', 'agenda_approval_history.status_id')
            ->where('agendas.is_submitted',false)
            ->where('agenda_approval_history.role_id',$role_id-1)
            ->where('agenda_approval_history.status_id',0)
            ->count();

        $total_reject = $reject_agenda;
        $total = $cabinet3_agenda;
        $final_total = ($total_reject + $total );
    }



    $notification_details = new App\Models\Notifications;
    $total_agenda_notification = 0;
    $total_metting_request_notification = 0;
    $total_metting_minute_notification = 0;
    $user_id = backpack_user()->id;

    if (backpack_user()->hasRole(Config::get('roles.name.ministry_creator'))) {
        $roles_id = Config::get('roles.id.ministry_creator');

        $total_agenda_notification = count($notification_details->where('type','Agenda')->where('status_id',1)
        ->where('roles_id', $roles_id)
        ->where('user_id', $user_id)
        ->where('read_at',NULL)
        ->get());

        $total_metting_request_notification = count($notification_details->where('type','MeetingRequest')
            ->where('status_id',1)
            ->where('roles_id', $roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_minute_notification = count($notification_details->where('type','MeetingMinute')
            ->where('status_id',1)
            ->where('roles_id', $roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

    }

    //ministry2
    if (backpack_user()->hasRole(Config::get('roles.name.ministry_reviewer'))) {
        $roles_id = Config::get('roles.id.ministry_reviewer');
        $total_agenda_notification = count($notification_details->where('type','Agenda')
            ->where('status_id',1)
            ->where('roles_id', $roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_request_notification = count($notification_details->where('type','MeetingRequest')
            ->where('status_id',1)
            ->where('roles_id', $roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_minute_notification = count($notification_details->where('type','MeetingMinute')
            ->where('status_id',1)
            ->where('roles_id', $roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

    }

    //ministry3
    if (backpack_user()->hasRole(Config::get('roles.name.ministry_secretary'))) {
        $roles_id = Config::get('roles.id.ministry_secretary');
        $total_agenda_notification = count($notification_details->where('type','Agenda')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_request_notification = count($notification_details->where('type','MeetingRequest')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_minute_notification = count($notification_details->where('type','MeetingMinute')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

    }

    // cabinet1
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))) {
        $roles_id = Config::get('roles.id.cabinet_creator');
        $total_agenda_notification = count($notification_details->where('type','Agenda')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_request_notification = count($notification_details->where('type','MeetingRequest')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_minute_notification = count($notification_details->where('type','MeetingMinute')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

    }

    // cabiner2
    if (backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))) {
        $roles_id = Config::get('roles.id.cabinet_approver');
        $total_agenda_notification = count($notification_details->where('type','Agenda')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_request_notification = count($notification_details->where('type','MeetingRequest')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_minute_notification = count($notification_details->where('type','MeetingMinute')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

    }

    // cabiner3
    if (backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))) {
        $roles_id = Config::get('roles.id.chief_secretary');
        $total_agenda_notification = count($notification_details->where('type','Agenda')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_request_notification = count($notification_details->where('type','MeetingRequest')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

        $total_metting_minute_notification = count($notification_details->where('type','MeetingMinute')
            ->where('status_id',1)
            ->where('roles_id',$roles_id)
            ->where('user_id', $user_id)
            ->where('read_at',NULL)
            ->get());

    }

?>
