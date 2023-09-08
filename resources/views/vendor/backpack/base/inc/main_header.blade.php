<header class="app-header border-0 navbar">
  <!-- Logo -->
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto ml-3" type="button" data-toggle="sidebar-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="{{ url(config('backpack.base.home_link')) }}" title="{{ config('backpack.base.project_name') }}">
    <img src="{{ lang()=='en' ? asset('img/cabinet-logo-en.png') : asset('img/cabinet-logo-np.png') }}" style="max-width: 120px" alt="E-cabinet logo">
    <!-- {!! config('backpack.base.project_logo') !!} -->
  </a>
  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
    <span class="navbar-toggler-icon"></span>
  </button>

  

  
  @include(backpack_view('inc.menu'))
  
</header>

