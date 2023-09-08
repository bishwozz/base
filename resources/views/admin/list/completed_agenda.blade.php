@extends(backpack_view('blank'))


@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
  </div>
  @if (isset($crud->back_url))
  <small><a href="{{ backpack_url($crud->back_url) }}" class="hidden-print back-btn ml-5"><i class="la la-angle-double-left"></i> {{ trans('Back') }}</a></small>

  {{-- <small><a href="{{ backpack_url($crud->back_url) }}" class="d-print-none font-sm back-btn"><i class="la la-angle-double-left"></i> <span>Back</span></a></small> --}}
@endif
@endsection

@section('content')
  <!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      @if(!empty($list_tab_header_view))
        @include($list_tab_header_view)
      @endif

      @if(isset($tab_links))
        @include('admin.tab', ['links' => $tab_links])
      @endif
        <div class="row mb-0">
          <div class="col-sm-6">
            @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
              <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">

                @include('crud::inc.button_stack', ['stack' => 'top'])

              </div>
            @endif
          </div>
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif
        @if(isset($admin_user))
          <table id="ministry_data_table" class="table table-bordered table-sm table-striped mr-2 pr-2 mt-3" style="background-color:#f8f9fa;">
              <thead>
                  <tr>
                      <th class="report-heading text-center">{{trans('common.row_number')}}</th>
                      <th class="report-heading th_large">{{trans('common.ministry')}}</th>
                      <th class="report-heading text-center">Action</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($ministrys as $ministry)
                      @php
                          $rowId = 'ministry-'.$ministry->id;
                        @endphp
      
                      <tr data-toggle="collapse" data-target="{{ '#'.$rowId}}" class="accordion-toggle">
                        
                          <td class="report-data text-center">{{$loop->iteration}}</td>
                          <td class="report-data">{{$ministry->name_lc}}</td>
                          <td class="text-center">
                              <a class="fancybox show-btn" data-type="ajax" data-src="{{backpack_url('agenda/ministry-wise/'.$ministry->id)}}" title='View Detail'>
                                  <i class="la la-eye text-white font-weight-bold " style="font-size:16px;"></i>
                              </a>
                          </td>
                      </tr>
                  
                  @endforeach    
              </tbody>
          </table>
        @else
          @include('agenda.ministry_wise_agenda',['agendas'=>$ministry_agendas])
        @endif



    </div>

  </div>

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

  
<style>
    .report-heading {
        font-size: 15px !important;
    }
    tr>td {
        border-bottom: 1px solid grey !important;
        border-right: 1px solid grey !important;
        line-height: 2.0rem;
    }

    .bg-bisque{
        background-color: bisque !important;
        text-decoration: underline;
        text-decoration-style: double;
        text-decoration-color: blue;
        text-align: center !important;
        font-size: 16px !important;
    }
    

    .report-data {
        font-size: 15px !important;
        font-weight: 500;
        color: black;
        max-width:150px !important;
        padding-left:20px !important;

    }
    .report-data-second {
        text-align: center;
    }

    tr>th {
        border-bottom: 1px solid white !important;
        border-right: 1px solid white !important;
        background-color: #c8ced3 !important;
        color: black;
    }

    tr>td:hover{
        cursor: pointer;
    }
    .table th{
        padding:.75rem .75rem !important;
    }
    #fancybox-inner {
    overflow-x: hidden !important;
}
</style>

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
<script>
 $('#ministry_data_table').DataTable({
        searching: true,
        paging: false,
        ordering: true,
        select: false,
        bInfo: true,
        lengthChange: false
    });

    $('.fancybox').fancybox({
        openEffect: 'elastic',
        closeEffect: 'elastic',
        width: 1000
    });
</script>  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
