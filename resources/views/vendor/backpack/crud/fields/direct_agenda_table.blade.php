@php 
    $minute_tab = Session::get('minute_tab');

    $meeting_request_id = $field['value'];
@endphp

<link rel="stylesheet" type="text/css" href="{{ asset('packages/datatable-editor/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<div @include('crud::inc.field_wrapper_attributes') >
    {{-- <label class="table-title">{!! $field['label'] !!}</label> --}}
    <div class="card-footer">
        @if (isset($field['value']))
        <a class="btn btn-sm btn-success fancybox text-white add-new-btn" type="button" data-type="ajax"  data-src="{{'/admin/getdirectAgendaViewFromMeetingRequest/'.$meeting_request_id.'/agenda-view'}}" title="ठाडो प्रस्ताब प्रबिस्ट गर्ने"><i class="fa fa-plus"></i> ठाडो प्रस्ताब प्रबिस्ट गर्ने</a>
        @endif
    </div>
 

    <div class="table-card">
        <table id="meeting_agenda_table" class="table table-striped">
            <thead>
                <colgroup width="6%"></colgroup>
                <colgroup width="38%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="46%"></colgroup>
                <tr>
                    <th style="text-align: center">{{trans('common.row_number')}}</th>
                    <th style="text-align: center">{{trans('common.ministry')}}</th>
                    <th style="text-align: center">निर्णय नं.</th>
                    <th style="text-align: center">प्रस्तावको बिषय</th>
                </tr>
            </thead>
            <tbody id="agenda-body-table-direct-agenda-from-request" data-id="{{$meeting_request_id}}">
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    var jq = $.noConflict();
    // var url ;
            
    jq(document).ready(function() {

        $('.fancybox').fancybox({
            openEffect: 'elastic',
            closeEffect: 'elastic',
            autoSize:true,
            clickSlide:false,
            touch:false
        });
    });
    
    </script>

<style>
    #meeting_agenda_table{
      margin: 0 auto;
      width: 100%;
      clear: both;
      border-collapse: collapse;
      table-layout: fixed; 
      word-wrap:break-word;
      text-align: left;
    }
    #meeting_agenda_table td{
        font-size: 14px;
        padding:5px 0px 5px 20px;    
        color:black;
        border-right:1px solid lightgray;
    }
    /* #meeting_agenda_table td:nth-child(4){
        font-weight:bold;
    } */
    
    .table-title{
        color:blue;
        font-weight:bold;
        font-size:16px;
    }
    </style>

