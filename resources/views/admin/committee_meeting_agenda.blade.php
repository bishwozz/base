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
                <th scope="col">{{trans('common.row_number')}}</th>
                <th scope="col" colspan="2">{{trans('common.ministry')}}</th>
                <th scope="col" colspan="2">{{trans('common.agenda')}}</th>
                {{-- <th scope="col" colspan="2">{{trans('common.file_upload')}}</th> --}}
                @if($edit)
                    <th scope="col" colspan="2">{{trans('common.step')}}</th>
                    <th scope="col" colspan="2">{{trans('common.actions')}}</th>
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
                        $ministry_agenda_count = $agenda->ministry_entity->transfered_agendas()->where('committee_id',$committee_id)->where('meeting_request_id',null)->where('is_hold',false)->count();
                    }
                @endphp
                <tr scope="row">
                    {{-- ministry lists --}}
                    @if($ministry_id != $agenda->ministry_id)
                        <td rowspan="{{$ministry_agenda_count}}">{{$i++}}</td>
                        <td colspan="2" rowspan="{{$ministry_agenda_count}}"> {{ $agenda->ministry_entity->$lang}}</td>
                    @endif
                    <td colspan="2" class="agenda">
                        <span class="agenda-span">
                            <span>{{ $agenda->agenda->agenda_number }}&emsp;{{ $agenda->agenda->agenda_title }}</span>
                        </span>
                    </td>
                    @if($edit)
                        <td colspan="2">
                            <select name="agenda_step_id[{{$agenda->transfered_agenda_id}}]" id="agenda_step_id-{{$agenda->transfered_agenda_id}}">
                                @foreach($steps as $step)
                                    <option value="{{$step->id}}" {{ isset($agenda->step_id) ? ($agenda->step_id == $step->id ? 'selected' : '' ) : ''}}>{{$step->$lang}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            @if($agenda->transfered_to == 1)
                                <?php
                                    $transfered_committee = TransferedAgenda::where('agenda_history_id',$agenda->id)->latest()->get()->first()->committee->name_lc;
                                ?>
                                <p>यो प्रस्ताव {{ $transfered_committee }} मा स्थान्तरण गरिएको छ</p>
                            @elseif($agenda->transfered_to == 2)
                                <p>{{ trans('common.transferedToNextMeeting') }}</p>
                            @else
                            {{-- transfer section --}}
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transferModal{{$agenda->id}}" data-whatever="@getbootstrap">{{trans('common.transfer')}}</button>
                                <div class="modal fade" id="transferModal{{$agenda->id}}" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel{{$agenda->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title font-weight-bold" id="transferModalLabel{{$agenda->id}}">{{trans('common.transfer')}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="form-group col-md-6">
                                                <label for="transfer_to{{$agenda->id}}" class="font-weight-bold">{{ trans('common.transferTo') }} <span class="text-danger">*</span></label>
                                                <select name="transfer_to{{$agenda->id}}" id="transfer_to{{$agenda->id}}" data-id="{{$agenda->id}}" class="form-control transfer_to">
                                                    <option value="2">{{ trans('common.nextMeeting') }}</option>
                                                    {{-- <option value="1">{{ trans('common.committee') }}</option> --}}
                                                </select>
                                            </div>
                                            {{-- <div style="display: none;" id="committee_div{{$agenda->id}}" class="form-group col-md-6">
                                                <label for="committee_id{{$agenda->id}}" class="font-weight-bold">{{ trans('common.committee') }} <span class="text-danger">*</span></label>
                                                <select multiple name="committee_id{{$agenda->id}}[]" id="committee_id{{$agenda->id}}" class="chosen-select form-control" style="width:100% !important">
                                                    @foreach($committees as $committee)
                                                        <option value="{{ $committee->id }}">{{ $committee->name_lc }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="form-group col-md-12">
                                                <label for="message-text" class="font-weight-bold">{{ trans('common.remarks') }}</label>
                                                <textarea class="form-control" name="remarks{{$agenda->id}}" id="remarks{{$agenda->id}}"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('common.transferNo') }}</button>
                                        <button onclick="transferAgenda({{$agenda->id}})" type="button" class="btn btn-success">{{ trans('common.transferYes') }}</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                {{-- end transfer section --}}
                                {{-- decision section start--}}
                                {{-- <button type="button" class="decisionBtn btn btn-sm btn-success" data-id="{{$agenda->id}}" data-decision_of_committee="{{$agenda->decision_of_committee}}">{{trans('common.decision')}}</button>
                                <div class="modal fade" id="decisionModal{{$agenda->id}}" tabindex="-1" role="dialog" aria-labelledby="decisionModalLabel{{$agenda->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title font-weight-bold" id="decisionModalLabel{{$agenda->id}}">{{trans('common.decision')}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="form-group col-md-12">
                                                <label for="message-text" class="font-weight-bold">{{ trans('common.decision_of_committee') }}</label>
                                                <textarea class="form-control" name="decision_of_committee{{$agenda->id}}" id="decision_of_committee{{$agenda->id}}"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                                        <button onclick="storeAgendaDecision({{$agenda->id}})" type="button" class="btn btn-primary">{{ trans('common.save') }}</button>
                                        </div>
                                    </div>
                                    </div>
                                </div> --}}
                                {{-- decision section end --}}
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
        // $('.transfer_to').on('change',function(){
        //     const agenda_history_id = $(this).data('id');
        //     if($(`#transfer_to${agenda_history_id}`).val()==1){
        //         $(`#committee_div${agenda_history_id}`).css('display','block');
        //     }else{
        //         $(`#committee_div${agenda_history_id}`).css('display','none');
        //     }
        // });
    });
    function transferAgenda(agendaHistory_id){
        swal({
            title: "{{ trans('common.agnedaTransferConfirmTitle') }}",
            icon: "warning",
            closeOnClickOutside: false,
            buttons: [
                {
                    text: "{{ trans('common.yes') }}",
                    value: true,
                    visible: true,
                    className: "btn-success",
                },
                {
                    text: "{{ trans('common.no') }}",
                    value: false,
                    visible: true,
                    className: "btn-danger",
                },
            ],
            dangerMode: true,
        }).then((value) => {
            if(value){
                transfer_to = $(`#transfer_to${agendaHistory_id}`).val();
                committee_id = $(`#committee_id${agendaHistory_id}`).val();
                remarks = $(`#remarks${agendaHistory_id}`).val();
                $.ajax({
                    url:'/admin/transfer-agenda-from-committee',
                    type:'post',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data:{
                        agendaHistory_id:agendaHistory_id,
                        transfer_to:transfer_to,
                        committee_id:committee_id,
                        remarks:remarks
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
    //     const decision_of_committee = $(this).data('decision_of_committee');

    //     // Set data to Form Edit
    //     $(`#decisionModal${id}`).modal('show');

    //     // set the form is being edit
    //     $(`#decision_of_committee${id}`).val(decision_of_committee);

    // });
    function storeAgendaDecision(agendaHistory_id){
        decision_of_committee = $(`#decision_of_committee${agendaHistory_id}`).val();
        $.ajax({
            url:'/admin/store-committee-decision',
            type:'post',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data:{
                agendaHistory_id:agendaHistory_id,
                decision_of_committee:decision_of_committee,
            },
            success: response =>
            {
                $(`#decisionModal${agendaHistory_id}`).modal('hide');
                location.reload();
            }
        });
    }
</script>