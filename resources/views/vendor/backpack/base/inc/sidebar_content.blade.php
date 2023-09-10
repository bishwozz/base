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

        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

        <hr class="hr-line m-2">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('app-setting') }}"><i class="nav-icon la la-book"></i> <span> AppSettings </span></a></li>
        <hr class="hr-line m-2">

@hasanyrole('superadmin|admin')
        <hr class="hr-line m-2">
        <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-user"></i> User Management</a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-users"></i> <span>Users</span></a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-renren"></i> <span>Roles</span></a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
                </ul>
        </li>
@endhasrole

@hasanyrole('superadmin|admin')
        <hr class="hr-line m-2">
        <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>Masrer</a>
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