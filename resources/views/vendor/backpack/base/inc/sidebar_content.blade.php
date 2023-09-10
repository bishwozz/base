<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
{{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li> --}}
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i class='nav-icon la la-bars'></i> Menus</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('slider') }}'><i class='nav-icon la la-sliders'></i> Slider</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('course') }}'><i class='nav-icon la la-graduation-cap'></i> Courses</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('news-notice') }}'><i class='nav-icon la la-newspaper-o'></i> News Notice</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('gallery') }}'><i class='nav-icon la la-picture-o'></i> Gallery</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('scholarship') }}'><i class='nav-icon la la-graduation-cap'></i> Scholarship</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('agent-detail') }}'><i class='nav-icon la la-user-secret'></i> Agent Details</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('human-resource') }}'><i class='nav-icon la la-user'></i> Human Resource</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('event') }}'><i class='nav-icon la la-calendar'></i> Events</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file'></i> Page</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('category') }}'><i class='nav-icon la la-list-alt'></i> Categories</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('college-details') }}'><i class='nav-icon la la-copyright'></i> College Details</a></li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-database"></i>Master</a>
	<ul class="nav-dropdown-items">
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('blog') }}'><i class='nav-icon la la-blog'></i> Blog</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('fee-type') }}'><i class='nav-icon la la-plus'></i> Fee Types</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-department-type') }}'><i class='nav-icon la la-building'></i> Department Type</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-class') }}'><i class='nav-icon la la-building'></i>Class</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('contact-us') }}'><i class='nav-icon la la-phone'></i> Contact Us</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('about-us') }}'><i class='nav-icon la la-address-card'></i> About us</a></li>
	{{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('header') }}'><i class='nav-icon la la-header'></i> Header</a></li> --}}
	{{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('footer-address') }}'><i class='nav-icon la la-copyright'></i> Footer</a></li> --}}
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-social-media') }}'><i class='nav-icon la la-users'></i> Mst Social Media</a></li>
	<li class='nav-item'><a class='nav-link' href='{{ backpack_url('saying') }}'><i class='nav-icon la la-compass'></i> Sayings</a></li>
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
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('services') }}'><i class='nav-icon la la-question'></i> Services</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('app-settings') }}'><i class='nav-icon la la-question'></i> AppSettings</a></li>