
@extends(backpack_view('blank'))

@php

  $ministry_id = null;
  $ministry_name = null;
  $output_string = null;
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => backpack_url('dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.edit') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
  if(($crud->getRoute() == "admin/ministry")){
		$ministry_name = \App\Models\Ministry::where('id',$entry->getKey())->pluck('name_lc')->first();
  }
  $segments = explode("/", $crud->getRoute());

	// Remove the segment at index 2 (0-based index)
	if (isset($segments[2])) {
		$ministry_id = $segments[2];
		unset($segments[2]);
	}
	if($segments){

		$output_string = implode("/", $segments);
	}
  if($output_string == "admin/ministry/ministryemployee" || $output_string == "admin/ministry/ministrymember"){

		$ministry_name = \App\Models\Ministry::where('id', $ministry_id)->pluck('name_lc')->first();
  }
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!} - {!!  $ministry_name !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="{{ $crud->getEditContentClass() }}">
		<!-- Default box -->
		@if(isset($tab_links))
			@include('admin.tab', ['links' => $tab_links])
		@endif


		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route.'/'.$entry->getKey()) }}"
				{{-- @if ($crud->hasUploadFields('update', $entry->getKey())) --}}
				enctype="multipart/form-data"
				{{-- @endif --}}
		  		>
		  {!! csrf_field() !!}
		  {!! method_field('PUT') !!}

		  	@if ($crud->model->translationEnabled())
		    <div class="mb-2 text-right">
		    	<!-- Single button -->
				<div class="btn-group">
				  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('_locale')?request()->input('_locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
					  	<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?_locale={{ $key }}">{{ $locale }}</a>
				  	@endforeach
				  </ul>
				</div>
		    </div>
		    @endif
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', ['fields' => $crud->fields(), 'action' => 'edit'])
		      @else
		      	@include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'edit'])
              @endif
              <!-- This makes sure that all field assets are loaded. -->
            <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
			@if(isset($crud->denySave) && ($crud->denySave==true))
			@else
            @include('crud::inc.form_save_buttons')
			@endif
		  </form>
	</div>
</div>
<!-- loading custom scripts related to the FORM -->
@if (isset($crud->enableDialog) && $crud->enableDialog)

	@if(!empty($load_scripts))
		@foreach ($load_scripts as $script)
			<script type="text/javascript" src={{ $script }}></script>
		@endforeach
	@endif

		{{-- Specific style in $this->data['script_js'] --}}
		@if(!empty($script_js))
		<script type="text/javascript">
			{{!! html_entity_decode($script_js) !!}}
		</script>
	@endif
@endif
@endsection

