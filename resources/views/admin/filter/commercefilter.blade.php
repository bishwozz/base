@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
//   $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
      {{-- dd('here'); --}}
	</section>
@endsection

@section('content')

<div class="row">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')
		  <form method="get" target="blank" action="{{ url($fitler_url ?? $crud->route) }}">
			

		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
                  <div class="card">
                    <div class="card-body row">
                      {{-- {{ dd('sd') }} --}}
                      @include('crud::inc.show_fields', ['fields' => $crud->fields()])
                    </div>
                  </div>
            @endif

            @if(isset($custom_save_button))
            <button type="$custom_save_button['type']" class="{{$custom_save_button['class']}}">
                <span class="{{$custom_save_button['icon']}}" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="{{ $custom_save_button['name'] }}">{{ $custom_save_button['name'] }}</span>
            </button>
            @endif
            <button type="submit" class ="btn btn-success" formaction="{{'/'.$crud->route.'/excelexport'}}"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Export Excel</button>
		  </form>
	</div>
</div>

@endsection
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
  $(document).ready(function(){
      setTimeout(function(){
          $('#commerceMenu').trigger('click'); 
      },1);
  });
</script>
