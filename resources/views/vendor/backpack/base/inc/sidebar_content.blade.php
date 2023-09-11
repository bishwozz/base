<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('slider') }}'><i class='nav-icon la la-sliders'></i> Slider</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('games') }}'><i class='nav-icon la la-sliders'></i> games</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('services') }}'><i class='nav-icon la la-sliders'></i> Services</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('payments') }}'><i class='nav-icon la la-sliders'></i> payments</a></li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-database"></i>Master</a>
	<ul class="nav-dropdown-items">
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('app-settings') }}'><i class='nav-icon la la-blog'></i> App Settings</a></li>

	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-user"></i> User Management</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-users"></i> <span>Users</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-renren"></i> <span>Roles</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>