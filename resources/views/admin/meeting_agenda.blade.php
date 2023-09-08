@php 
    $lang = "name_".lang();
@endphp

<style>
    .table {
        border-radius: 15px;
    }

    .table td {
        padding: 1em !important;
    }

    .table td span {
        padding-right: 1.1em !important;
    }


    .table-card {
        background-clip: border-box;
        background-color: #fff;
        border: 1px solid rgba(0, 40, 100, .12);
        border-radius: 3px;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 5%);
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .agenda {
        /* text-align: center; */
    }

    .table tr .agenda-span {
        padding-top: 1200px;
    }

    .toggle-form {
        width: 63%;
        height: 20px;
        box-sizing: border-box;
        background-color: white;
        box-shadow: 0px 1px 2px 1px rgba(0, 0, 0, 0.4);
        text-align: center;
        position: relative;
        border-radius: 4px;
        display: inline-flex;
    }

    .toggle-form>div {
        color: white;
        padding-top: 24px;
        display: block;
        position: absolute;
        top: -4px;
        left: -4px;
        bottom: -4px;
        width: calc(33.33% + 8px);
        background-color: blue;
        border-radius: 4px;
        box-shadow: 0px 1px 2px 1px rgba(0, 0, 0, 0.4);
        z-index: 1;
        pointer-events: none;
        transition: transform 0.3s;
    }

    .toggle-form>div .middle-div {
        color: white;
        padding-top: 24px;
        display: block;
        position: absolute;
        top: -4px;
        left: -4px;
        bottom: -4px;
        width: calc(33.33% + 8px);
        background-color: blue;
        border-radius: 4px;
        box-shadow: 0px 1px 2px 1px rgba(0, 0, 0, 0.4);
        z-index: 1;
        pointer-events: none;
        transition: transform 0.3s;
    }

    .toggle-form::after {
        content: "";
        display: block;
        clear: both;
    }

    .toggle-form label {
        float: left;
        width: calc(33.333% - 0px);
        position: relative;
        padding: 0px 0px 20px;
        overflow: hidden;
        border-left: solid 1px rgba(0, 0, 0, 0.2);
        transition: color 0.3s;
        cursor: pointer;
        -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
    }

    .toggle-form label:first-child {
        border-left: none;
    }

    .toggle-form label input {
        position: absolute;
        top: -200%;
    }

    .toggle-form label div {
        z-index: 5;
        position: absolute;
        width: 100%;
    }

    .toggle-form label.selected {
        color: white;
    }

</style>
@php
    $committees = App\Models\Committee::all();
    if($action=='edit'){
        $edit = true;
        $steps = App\Models\MstStep::all();
        $meeting_id = $meeting_request_id;
        $ministry_id = 0;

    }else{
        $edit=false;
        $ministry_id = 0;
    }
    $i=1;
@endphp

<div class="table-card">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="text-align: center">{{trans('common.row_number')}}</th>
                <th  style="text-align: center">{{trans('common.ministry')}}</th>
                <th  style="text-align: center">निर्णय नं.</th>
                <th  style="text-align: center">प्रस्तावको बिषय</th>
                @if($edit)
                    <th  style="text-align: center">{{trans('common.actions')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($agendas as $agenda)
                @php
                    if($edit){
                        $ministry_agenda_count = App\Models\AgendaHistory::where('ec_meeting_request_id',$meeting_id)
                                                        ->where('ministry_id',$agenda->ministry_id)->count();
                    }else{
                        $ministry_agenda_count = $agenda->ministry_entity->agendas()->where('ec_meeting_request_id',null)
                                            ->where('is_approved',true)->where('is_hold',false)
                                            ->where('is_rejected',false)->whereNotNull('agenda_number')->count();
                    }
                @endphp
                <tr scope="row">
                    {{-- ministry lists --}}
                        <td >{{$i++}}</td>
                        <td > {{ $agenda->ministry_entity->$lang}}</td>
                    <td  class="agenda">
                        <span class="agenda-span">
                            <span>{{ $edit?isset($agenda->agenda->agenda_number)? $agenda->agenda->agenda_number : '' :isset($agenda->agenda_number)? $agenda->agenda_number : ''  }}</span>
                        </span>
                    </td>
                    <td  class="agenda">
                        <span class="agenda-span">
                            <span>{{ ($edit)?isset($agenda->agenda->agenda_title)? $agenda->agenda->agenda_title : '' :$agenda->agenda_title }}</span>
                        </span>
                    </td>
                    @if($edit)
                        {{-- <td colspan="2"> --}}
                            {{-- @if($agenda->transfered_to == null) --}}
                                {{-- <select name="agenda_step_id[{{$agenda->agenda_id}}]" id="agenda_step_id-{{$agenda->agenda_id}}">
                                    @foreach($steps as $step)
                                        <option value="{{$step->id}}" {{ isset($agenda->step_id) ? ($agenda->step_id == $step->id ? 'selected' : '' ) : ''}}>{{$step->$lang}}</option>
                                    @endforeach
                                </select> --}}
                            {{-- @endif --}}
                        {{-- </td> --}}
                        <td>
                            @if($agenda->transfered_to == 1)
                                @if(lang() == 'en')
                                    This agenda has been transfered to committee
                                        <span class="text-primary">
                                            (
                                                @foreach($agenda->transfered_to_committee as $committee)
                                                    {{$committee->name_en}},
                                                @endforeach
                                            )
                                        </span>
                                @else
                                    यो एजेन्डा
                                        <span class="text-primary">
                                            (
                                                @foreach($agenda->transfered_to_committee as $committee)
                                                    {{$committee->name_lc}},
                                                @endforeach
                                            )
                                        </span> समितिमा स्थानान्तरण गरिएको छ
                                @endif
                            @elseif($agenda->transfered_to == 2)
                                <p>{{ trans('common.transferedToNextMeeting') }}</p>
                            @else
                            {{-- transfer section --}}
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transferModal{{$agenda->id}}" data-whatever="@getbootstrap">{{trans('common.holdAgenda')}}</button>
                                <div class="modal fade" id="transferModal{{$agenda->id}}" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel{{$agenda->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title font-weight-bold" id="transferModalLabel{{$agenda->id}}"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="form-group col-md-6">
                                                <label for="transfer_to{{$agenda->id}}" class="font-weight-bold">{{ trans('common.holdTo') }} <span class="text-danger">*</span></label>
                                                <select name="transfer_to{{$agenda->id}}" id="transfer_to{{$agenda->id}}" data-id="{{$agenda->id}}" class="form-control transfer_to">
                                                    <option value="2">{{ trans('common.nextMeeting') }}</option>
                                                    {{-- <option value="1">{{ trans('common.committee') }}</option> --}}
                                                </select>
                                            </div>
                                            <div style="display: none;" id="committee_div{{$agenda->id}}" class="form-group col-md-6">
                                                <label for="committee_id{{$agenda->id}}" class="font-weight-bold">{{ trans('common.committee') }} <span class="text-danger">*</span></label>
                                                <select multiple name="committee_id{{$agenda->id}}[]" id="committee_id{{$agenda->id}}" class="chosen-select form-control" style="width:100% !important">
                                                    @foreach($committees as $committee)
                                                        <option value="{{ $committee->id }}">{{ $committee->name_lc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="form-group col-md-12">
                                                <label for="message-text" class="font-weight-bold">{{ trans('common.remarks') }}</label>
                                                <textarea class="form-control" name="remarks{{$agenda->id}}" id="remarks{{$agenda->id}}"></textarea>
                                            </div> --}}
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('common.unholdAgenda') }}</button>
                                        <button onclick="transferAgenda({{$agenda->id}})" type="button" class="btn btn-success">{{ trans('common.holdAgenda') }}</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    @endif
                </tr>
                @php
                    $ministry_id = $agenda->ministry_id;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $('.modal').appendTo("body");
    $(document).ready(function(){
        $(".chosen-select").select2();
        $('.transfer_to').on('change',function(){
            const agenda_history_id = $(this).data('id');
            if($(`#transfer_to${agenda_history_id}`).val()==1){
                $(`#committee_div${agenda_history_id}`).css('display','block');
            }else{
                $(`#committee_div${agenda_history_id}`).css('display','none');
            }
        });
    });
    function transferAgenda(agendaHistory_id){
        Swal.fire({
            title: "होल्ड गर्ने हो?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'होईन',
            confirmButtonText: 'हो',
            dangerMode: true,
        }).then((result) => {
            if(result.isConfirmed){
                transfer_to = $(`#transfer_to${agendaHistory_id}`).val();
                committee_id = $(`#committee_id${agendaHistory_id}`).val();
                // remarks = $(`#remarks${agendaHistory_id}`).val();
                $.ajax({
                    url:'/admin/transfer-agenda',
                    type:'post',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data:{
                        agendaHistory_id:agendaHistory_id,
                        transfer_to:transfer_to,
                        committee_id:committee_id,
                        // remarks:remarks
                    },
                    success: response =>
                    {
                        $(`#transferModal${agendaHistory_id}`).modal('hide');
                        location.reload();
                    }
                });
            }else{
                $(`#transferModal${agendaHistory_id}`).modal('hide');
            }
        });
    }
    // $(".decisionBtn").click(function() {
    //     const id = $(this).data('id');
    //     const decision_of_cabinet = $(this).data('decision_of_cabinet');

    //     // Set data to Form Edit
    //     $(`#decisionModal${id}`).modal('show');

    //     // set the form is being edit
    //     $(`#decision_of_cabinet${id}`).val(decision_of_cabinet);

    // });
    function storeAgendaDecision(agendaHistory_id){
        decision_of_cabinet = $(`#decision_of_cabinet${agendaHistory_id}`).val();
        $.ajax({
            url:'/admin/store-cabinet-decision',
            type:'post',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data:{
                agendaHistory_id:agendaHistory_id,
                decision_of_cabinet:decision_of_cabinet
            },
            success: response =>
            {
                $(`#decisionModal${agendaHistory_id}`).modal('hide');
                // location.reload();
            }
        });
    }
</script>