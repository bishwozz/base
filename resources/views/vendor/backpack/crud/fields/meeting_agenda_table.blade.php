@php 
    $minute_tab = Session::get('minute_tab');

    $meeting_minutedetail = $field['value'];
@endphp

<link rel="stylesheet" type="text/css" href="{{ asset('packages/datatable-editor/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<div class="form-group col-md-12">
<div @include('crud::inc.field_wrapper_attributes') >
    <label class="table-title">{!! $field['label'] !!}</label>
    <div class="card col-md-12">
            <table id="meeting_agenda_table" class="display responsive" cellspacing="0" width="100%">
            <thead>
                <colgroup width="7%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="15%"></colgroup>
                <colgroup width="20%"></colgroup>
                <colgroup width="30%"></colgroup>
                <colgroup width="15%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="15%"></colgroup>
                <tr>
                    <th style="font-size:12px;text-align:center;">क्र.सं.</th>
                    <th style="font-size:12px;text-align:center;">प्रस्ताव नं.</th>
                    <th style="font-size:12px;text-align:center;">मन्त्रालय</th>
                    <th style="font-size:12px;text-align:center;">विषय/प्रस्ताव</th>
                    <th style="font-size:12px;text-align:center;; color:blue;">{{$minute_tab == "committee" || backpack_user()->hasRole('committee') ? 'समितिको निर्णय' : 'म.प.को निर्णय'}} </th>
                    <th style="font-size:12px;text-align:center;">निर्णयको प्रकार</th>   
                    <th style="font-size:12px;text-align:center;">प्रिन्ट</th>
                    <th style="font-size:12px;text-align:center;">अपलोड</th>

                </tr>
            </thead>
            <tbody id="agenda-body-table">
            </tbody>
            </table>
    </div>
 
    <label class="table-title">   ठाडो प्रस्ताव </label>

    <div class="card col-md-12">
            <table id="meeting_agenda_table" class="display responsive" cellspacing="0" width="100%">
            <thead>
                <colgroup width="7%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="15%"></colgroup>
                <colgroup width="20%"></colgroup>
                {{-- <colgroup width="15%"></colgroup> --}}
                <colgroup width="30%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="15%"></colgroup>
                <tr>
                    <th style="font-size:12px;">क्र.सं.</th>
                    <th style="font-size:12px;">निर्णय नं.</th>
                    <th style="font-size:12px;">मन्त्रालय</th>
                    <th style="font-size:12px;">विषय/प्रस्ताव</th>
                    {{-- <th style="font-size:12px;">निर्णयको प्रकार</th>    --}}
                    <th style="font-size:12px;; color:blue;">{{$minute_tab == "committee" || backpack_user()->hasRole('committee') ? 'समितिको निर्णय' : 'म.प.को निर्णय'}} </th>
                    <th style="font-size:12px;">प्रिन्ट</th>
                    <th style="font-size:12px;">अपलोड</th>

                </tr>
            </thead>
            <tbody id="agenda-body-table-direct-agenda">
            </tbody>
           
            </table>
            <div class="card-footer">
                {{-- @if (isset($field['value'])) --}}
                {{-- <a class="btn btn-sm btn-success fancybox text-white add-new-btn" type="button" data-type="ajax"  data-src="{{'/admin/getdirectAgendaView/'.$meeting_minutedetail.'/agenda-view'}}" title="ठाडो प्रस्ताब प्रबिस्ट गर्ने"><i class="fa fa-plus"></i> ठाडो प्रस्ताब प्रबिस्ट गर्ने</a> --}}
                {{-- @endif --}}
            </div>

    </div>
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

