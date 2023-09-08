@php
    require app_path('Helpers/AgendaNotifyHelper.php');
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
                <a class="nav-link tab-link {{ $new_tab }} p-1 px-3 mr-2"
                    href="{{ url($crud->route) }}?agenda_status=new"
                    role="tab">{{ $lang == 'lc' ? 'नया प्रस्तावहरु' : 'New Agendas' }}
                </a>
            </li>
            <li role="presentation" class="nav-item ">
                <a class="nav-link tab-link {{ $all_tab }} p-1 px-3"
                    href="{{ url($crud->route) }}?agenda_status=all"
                    role="tab">{{ $lang == 'lc' ? 'पुराना  प्रस्तावहरु' : 'Old Agendas' }}</a>
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
