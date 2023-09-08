@php
    require app_path('Helpers/AgendaNotifyHelper.php');
    require app_path('Helpers/EcMeetingNotifyHelper.php');
    require app_path('Helpers/MeetingMinuteNotifyHelper.php');
    use App\Models\CoreMaster\AppSetting;
    $id = AppSetting::pluck('id')->first();



@endphp
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<style>
    .hr-line {
        opacity: .20 !important;
        color: azure;
    }

    hr.hr-line {
        border: 1px solid azure;
        box-shadow: 4px 4px 4px black;
    }
</style>
@php
    $user = backpack_user();
    $new_agenda_request = App\Models\Agenda::where('is_submitted', true)
        ->where('is_approved', false)
        ->where('is_rejected', false)
        ->count();
@endphp
@if (
    $user->hasAnyRole(
        'superadmin|admin|minister|chief_secretary|cabinet_approver|cabinet_creator|ministry_secretary|ministry_reviewer|ministry_creator'))
    <hr class="hr-line m-2">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="las la-home nav-icon"></i>
        {{ trans('menu.dashboard') }}</a></li>
        <hr class="hr-line m-2">
    <li class='nav-item'><a class='nav-link' href="/admin/report"><i class="las la-file-excel"></i> रिपोर्ट </a></li>

@endif


@if ($user->hasAnyRole('superadmin|admin'))
    <hr class="hr-line m-2">
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon la la-tasks"></i>{{ trans('menu.mp_committee') }}</a>
        <ul class="nav-dropdown-items" style="overflow-x:hidden">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ministry') }}'><i
                        class='nav-icon la la-cogs'></i> {{ trans('menu.ministries') }}</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('committee') }}'><i
                        class='nav-icon la la-cogs'></i> {{ trans('menu.committee') }}</a></li>
        </ul>
    </li>
@endif

    <hr class="hr-line m-2">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('agenda') }}'><i class='nav-icon  las la-calendar-check'></i>
            {{ trans('common.agenda') }}
            @if((int)$total_agenda_notification > 0)
                &emsp;<span style="background: red;color:white;padding:1px 5px 5px 5px;border-radius:10px;">( new {{ $total_agenda_notification }} )</span>
            @endif
        </a></li>



@if ($user->hasAnyRole('minister'))
    <hr class="hr-line m-2">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ec-meeting-request') }}'><i
                class='nav-icon las la-bullhorn'></i> {{ trans('menu.ecMeetingRequest') }}
                @if((int)$total_metting_request_notification > 0)
                    &emsp;<span style="background: red;color:white;padding:1px 5px 5px 5px;border-radius:10px;">( new {{ $total_metting_request_notification }} )</span>
                @endif
            </a></li>
@endif

<hr class="hr-line m-2">

@if ($user->hasAnyRole('superadmin|admin|minister|chief_secretary|cabinet_approver|cabinet_creator|mp'))

    <li class='nav-item'>
        <a class='nav-link' href='{{ backpack_url('ec-meeting-request') }}'>
            <i class='nav-icon las la-bullhorn'></i>
            {{ trans('menu.ecMeetingRequest') }}
            @if((int)$total_metting_request_notification > 0)
                &emsp;<span style="background: red;color:white;padding:1px 5px 5px 5px;border-radius:10px;">( new {{ $total_metting_request_notification }} )</span>
            @endif
        </a>
    </li>

<hr class="hr-line m-2">

    <li class='nav-item'>
        <a class='nav-link' href='{{ backpack_url('meeting-minute-detail') }}'>
            <i class='nav-icon las la-file-alt'></i> {{ trans('menu.meetingMinute') }}
            @if((int)$total_metting_minute_notification > 0)
                &emsp;<span style="background: red;color:white;padding:1px 5px 5px 5px;border-radius:10px;">( new {{ $total_metting_minute_notification }} )</span>
            @endif
        </a>
    </li>
@endif

@if ($user->hasAnyRole('superadmin|admin|chiefsecretary|secretary'))

    <hr class="hr-line m-2">

    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon la la-tasks"></i>{{ trans('menu.secondarymaster') }}</a>
        <ul class="nav-dropdown-items" style="overflow-x:hidden">
            {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-meeting') }}'><i class='nav-icon la la-cogs'></i> {{ trans('menu.meeting') }}</a></li> --}}
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-post') }}'><i
                        class='nav-icon la la-cogs'></i> {{ trans('menu.post') }}</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-step') }}'><i
                        class='nav-icon la la-cogs'></i>{{ trans('menu.steps') }}</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('agenda-decision-type') }}'><i
                        class='nav-icon la la-question'></i>{{ trans('common.agenda_decision_type') }}</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('agenda-file-type') }}'><i
                        class='nav-icon fa fa-file'></i>{{ trans('common.file_type') }}</a></li>


            {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ministry-member-type') }}'><i class='nav-icon la la-cogs'></i> {{trans('menu.ministryMemberType')}}</a></li> --}}
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ec-mp') }}'><i
                        class='nav-icon la la-cogs'></i> {{ trans('menu.ecMps') }}</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-agenda-types') }}'><i
                        class='nav-icon la la-cogs'></i> {{ trans('menu.agenda_type') }}</a></li>
        </ul>
    </li>





    <hr class="hr-line m-2">

    <!-- Users, Roles, Permissions -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i>
            {{ trans('menu.authentication') }}</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i
                        class="nav-icon la la-user"></i> <span>{{ trans('menu.user') }}</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i
                        class="nav-icon la la-id-badge"></i> <span>{{ trans('menu.role') }}</span></a></li>
            @if ($user->hasRole('superadmin'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i
                            class="nav-icon la la-key"></i> <span>{{ trans('menu.permission') }}</span></a></li>
            @endif
        </ul>
    </li>



    <hr class="hr-line m-2">

    <li class='nav-item'><a href="/admin/app-setting/{{ $id }}/edit" class='nav-link'><i
                class='nav-icon la la-cog'></i> {{ trans('menu.appSetting') }}</a></li>

    <hr class="hr-line m-2">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('session-log') }}'><i
                class='nav-icon la la-list'></i>{{ trans('menu.session_log') }} </a></li>

    @if ($user->hasRole('superadmin'))
        <hr class="hr-line m-2">
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i
                    class="nav-icon la la-tasks"></i>{{ trans('menu.master') }}</a>
            <ul class="nav-dropdown-items" style="overflow-x:hidden">
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-province') }}'><i
                            class='nav-icon la la-cogs'></i>{{ trans('menu.province') }}</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-district') }}'><i
                            class='nav-icon la la-cogs'></i> {{ trans('menu.district') }}</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-local-level-type') }}'><i
                            class='nav-icon la la-cogs'></i>{{ trans('menu.localLevelType') }}</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-local-level') }}'><i
                            class='nav-icon la la-cogs'></i> {{ trans('menu.localLevel') }}</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-nepali-month') }}'><i
                            class='nav-icon la la-cogs'></i>{{ trans('menu.month') }}</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fiscal-year') }}'><i
                            class='nav-icon la la-cogs'></i> {{ trans('menu.fiscalYear') }}</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-gender') }}'><i
                            class='nav-icon la la-cogs'></i> {{ trans('menu.gender') }}</a></li>
            </ul>
        </li>
    @endif

    <hr class="hr-line m-2">
    <li class='nav-item'><a class='nav-link'target="_blank" href="/admin/manual"><i class='la la-file-pdf-o'
                style="color: red"></i> {{ trans('menu.manuals') }}</a></li>
@endif

