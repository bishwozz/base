<style>
.bubble{
        background: red;
        font-size:12px;
        color:white;
        padding:0px 8px 3px 8px;
        border-radius:10px;
        margin-left: 10px;
}
.hr-line{
        opacity: .20 !important;
        color: azure;
}
hr.hr-line{
        border:1px solid azure;
        box-shadow: 4px 4px 4px black;
}
</style>
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('menu.dashboard') }}</a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('office-initiative') }}"><i class="nav-icon la la-cogs"></i>{{trans('actLaw.office_initiatives')}}</a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('ministry-act-law') }}"><i class="nav-icon la la-cogs"></i> <span>{{trans('actLaw.act_laws')}}</span></a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('office-detail') }}"><i class="nav-icon la la-cogs"></i><span> {{trans('actLaw.office_details')}}</span></a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('ministry-progress-info') }}"><i class="nav-icon la la-cogs"></i> मन्त्रालय प्रगति विवरण</a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('ministry-budget-info') }}"><i class="nav-icon la la-cogs"></i> मन्त्रालय बजेट विवरण</a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('ministry-program-progress') }}"><i class="nav-icon la la-cogs"></i> कार्यक्रम प्रगति</a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('pt-project') }}"><i class="nav-icon la la-cogs"></i> कार्यक्रम/आयोजना </a></li>
<hr class="hr-line m-2">
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-ministry') }}'><i class='nav-icon la la-cogs'></i> मन्त्रालयहरू </a></li>



@if(backpack_user()->hasAnyRole('superadmin||admin'))
        <hr class="hr-line m-2">
        <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>प्रतिवेदन</a>
                <ul class="nav-dropdown-items" style="overflow-x:hidden">
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('office-report/filter') }}'><i class='nav-icon la la-file'></i> प्रतिवेदन </a></li>
                <!-- <li class='nav-item'><a class='nav-link' href='/admin/report/pivot_report'><i class='nav-icon la la-file'></i>Pivot Report</a></li> -->
                </ul>
        </li>

@endif

@hasrole('ministry')
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>{{ trans('menu.user') }}</span></a></li>
@endhasrole

@hasanyrole('superadmin|admin')
        <hr class="hr-line m-2">
        <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> {{ trans('menu.users_roles') }}</a>
        <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>{{ trans('menu.user') }}</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>{{ trans('menu.role') }}</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>{{ trans('menu.permission') }}</span></a></li>
        </ul>
        </li>
@endhasrole

<!-- End of Authentication-->
@hasanyrole('superadmin|admin')
        <hr class="hr-line m-2">
        <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>{{ trans('menu.master') }}</a>
                <ul class="nav-dropdown-items" style="overflow-x:hidden">
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-province') }}'><i class='nav-icon la la-cogs'></i>{{ trans('menu.province') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-district') }}'><i class='nav-icon la la-cogs'></i> {{ trans('menu.district') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-local-level-type') }}'><i class='nav-icon la la-cogs'></i>{{ trans('menu.localLevelType') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-local-level') }}'><i class='nav-icon la la-cogs'></i> {{ trans('menu.localLevel') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-nepali-month') }}'><i class='nav-icon la la-cogs'></i>{{ trans('menu.month') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fiscal-year') }}'><i class='nav-icon la la-cogs'></i> {{ trans('menu.fiscalYear') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-gender') }}'><i class='nav-icon la la-cogs'></i> {{ trans('menu.gender') }}</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-level') }}'><i class='nav-icon la la-cogs'></i>श्रेणी/तह </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('mst-milestones-status') }}"><i class="nav-icon la la-feed"></i>माइलस्टोन स्थितिहरू</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('mst-posts') }}"><i class="nav-icon la la-question"></i> पद </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('mst-groups') }}"><i class="nav-icon la la-question"></i> समूह </a></li>
                </ul>
        </li>
@endhasrole

<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('app-setting') }}"><i class="nav-icon la la-book"></i> <span> सेटिंग </span></a></li>
<hr class="hr-line m-2">
<li class="nav-item"><a class="nav-link" target="blank" href="{{ backpack_url('user-manual') }}"><i class="nav-icon la la-book"></i> <span> प्रयोगकर्ता पुस्तिका </span></a></li>
<!-- <hr class="hr-line m-2"> -->


