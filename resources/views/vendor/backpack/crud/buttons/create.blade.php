@if ($crud->hasAccess('create'))
	@if(App::getLocale()=='en')
		<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('common.add') }} {{ $crud->entity_name }}</span></a>
	@else
		<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i>{{ $crud->entity_name }} {{ trans('common.add') }}</span></a>
	@endif
@endif