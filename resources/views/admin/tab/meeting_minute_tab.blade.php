@php
    require app_path('Helpers/MeetingMinuteNotifyHelper.php');
@endphp

<style>
    .notification-badge {
        background-color: red;
        color: white;
        padding: 2px 8px;
        border-radius: 50%;
        font-size: 12px;
        position: absolute;
        font-weight: bold;
        top: -10px;
    }

    li {
        padding-right: 10px;
    }
</style>

<div class="row mb-2 ml-5">
    <div class="col-md-12">
        <ul class="nav nav-tabs flex-column flex-sm-row mt-2" id="agendaTab" role="tablist">
            <li role="presentation" class="nav-item">
                <a class="nav-link tab-link {{$ministry_tab}} p-1 px-3 mr-2" href="{{ url($crud->route)}}?meeting=ministry"
                    role="tab">{{ trans('common.ministryMeeting') }}
                    {{-- @if (isset($total_meeting_minute) && $total_meeting_minute > 0)
                        <span class="notification-badge position-absolute top-0 end-0">
                            {{ $total_meeting_minute }}
                        </span>
                    @endif --}}
                </a>
            </li>
            <li role="presentation" class="nav-item ">
                <a class="nav-link tab-link {{$committee_tab}} p-1 px-3"
                    href="{{ url($crud->route)}}?meeting=committee" role="tab">{{ trans('common.committeeMeeting') }}</a>
            </li>
        </ul>
    </div>
</div>

<style>
    #agendaTab li a.active {
        border: 1px solid lightgray !important;
        border-bottom: 3px solid blue !important;
    }
</style>
