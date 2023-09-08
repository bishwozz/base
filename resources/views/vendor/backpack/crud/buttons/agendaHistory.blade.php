@php
use App\Models\Agenda;
use App\Models\AgendaApprovalHistory;
use Illuminate\Support\Facades\Config;
use App\Models\AgendaButtonHideShowStatus;


        // $user_role = backpack_user()->getRoleNames()[0];

        // switch($user_role){

        //     case Config::get('roles.name.ministry_creator'):
        //         $roleIds = [Config::get('roles.id.ministry_reviewer')];
        //     break;

        //     case Config::get('roles.name.ministry_reviewer'):

        //         $roleIds = [Config::get('roles.id.ministry_secretary'),Config::get('roles.id.ministry_reviewer')];

        //     break;

        //     case Config::get('roles.name.ministry_secretary'):

        //         $roleIds = [Config::get('roles.id.cabinet_creator'),Config::get('roles.id.ministry_secretary')];

        //     break;
        //     case Config::get('roles.name.cabinet_creator'):

        //         $roleIds = [Config::get('roles.id.cabinet_approver'),Config::get('roles.id.cabinet_creator')];
        //     break;
        //     case Config::get('roles.name.cabinet_approver'):

        //         $roleIds = [Config::get('roles.id.chief_secretary'),Config::get('roles.id.cabinet_approver')];

        //     break;
        //     case Config::get('roles.name.chief_secretary'):

        //         $roleIds = [Config::get('roles.id.chief_secretary')];

        //     break;
        //     case 'admin':
        //         $roleIds = [];
        //     break;
        //     default:
        //         $roleIds = [];

        //     break;
        // }

// $agenda_history = AgendaApprovalHistory::where('agenda_id', $entry->getKey())->orderBy('created_at','desc')->where('status_id',0)->whereIn('role_id',$roleIds)->get();
$agenda_history = AgendaApprovalHistory::where('agenda_id', $entry->getKey())->orderBy('created_at','desc')->get();
$agenda = Agenda::find($entry->getKey());
// $latest_agenda_history = AgendaApprovalHistory::select('role_id')->whereAgendaId($entry->getKey())->latest('updated_at')->first();


$status = null;
// if(backpack_user()->hasRole(Config::get('roles.id.chief_secretary')) == $agenda_history->roles_id || backpack_user()->hasRole(Config::get('roles.id.ministry_secretary')) == $agenda_history->roles_id){
//     $status = 'स्वीकृत';
// }else{
//     $status = 'पेश';

// }

@endphp
    @if(count($agenda_history) > 0)
    <small>
    <a href="javascript:;" class="btn  btn-danger show-btn-return" data-toggle="modal" data-target="#showAgendaHistoryModal-{{ $entry->getKey() }}" title="प्रस्ताव फिर्ता हुनुको कारण">
    <i class="las la-undo-alt"></i>    </a>
    </small>
    @endif


<style>
  
	

    /* Style for the table */
    .table_custom {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid black;
    }

    /* Style for the table header */
    .table_custom thead th {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        background-color: #979090;
        position: sticky;
        top: 0;
		color:white;
        z-index: 1;
    }

    /* Style for the table data cells */
    .table_custom tbody td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

	.even-row {
        background-color: #f2f2f2;
    }

    /* Style for alternating odd rows */
    .odd-row {
        background-color: #ffffff; /* Change this to the desired color */
    }
	.header-cell {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        background-color: #101010;
    }

	.modal-xl {
        max-width: 60%; /* Adjust this value to your preference */
        max-height: 100%; /* Adjust this value to your preference */
		font-size: 14px;
    }

    .modal-title {
        font-size: 24px;
        line-height: 10px;
        font-weight: bold;
        margin-top: 10px;
        margin-right:20px;
        display:inherit;
    }

    .modal-subtitle {
        font-size: 14px;
        color: #999;
        /* margin-top: 10px; */
    }

    .agenda-title {
        font-size: 18px;
        color: #333;
        margin-top: 5px;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #ccc;
        padding: 0.3rem;
    }

    .close {
        font-size: 1rem;
    }
    .modal-body{
        padding:0px !important;
        margin-left: 4px
    }
    .modal-footer{
        padding: 0.3rem !important;
    }
    .agenda-footer-title{
        flex: left !important;
    }



  </style>

<div class="modal fade bd-example-modal-lg" id="showAgendaHistoryModal-{{ $entry->getKey() }}" tabindex="-1" role="dialog" aria-labelledby="showAgendaModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAgendaModalTitle">प्रस्ताव समय रेखा</h5><br>
                <p class="modal-subtitle">{{$agenda->ministry_entity->name_lc}}</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button><br>
            </div>

            <div class="modal-body">
                <div style="max-height: 300px; overflow-y: scroll;">
                    <div class="table-container">
						<table class="table_custom">
							<thead>
                                <tr>
                                    <th rowspan="2" class="header-cell">क्र.सं.</th>
                                    <th  colspan="2" class="header-cell">पठाउनेको विवरण</th>
                                    <th rowspan="2" class="header-cell">समय</th>
                                    <th  colspan="2" class="header-cell">पाउनेको विवरण</th>
                                    <th rowspan="2" class="header-cell">स्थिति</th>
                                    <th rowspan="2" class="header-cell">कैफियत</th>
                                </tr>
                                <tr>
                                    {{-- <th class="header-cell">भूमिका</th> --}}
                                    <th class="header-cell">नाम</th>
                                    <th class="header-cell">पद</th>
                                    {{-- <th class="header-cell">भूमिका</th> --}}
                                    <th class="header-cell">नाम</th>
                                    <th class="header-cell">पद</th>
                                </tr>
                             
							</thead>
							<tbody>
								<!-- Your foreach loop here -->
								@foreach($agenda_history as $index => $history)
									<tr class="{{ $index % 2 == 0 ? 'even-row' : 'odd-row' }}">
										<td style="text-align: center">{{$loop->iteration}}.</td>
										{{-- <td>
                                            {{$history->role->field_name}}
                                        </td> --}}
										<td>
                                            {{$history->user->name}}
                                        </td>
										<td>
                                            {{$history->designation()}}
                                        </td>

										<td style="text-align: center">{{$history->created_at}}</td>
                                        @php
                                            $agendaButtonHideShowStatus = AgendaButtonHideShowStatus::where('approval_history_id', $history->id)->first();
                                            if(Config::get('roles.id.chief_secretary') == $history->role_id || Config::get('roles.id.ministry_secretary') == $history->role_id){
                                                $status = 'स्वीकृत गर्नुभयो';
                                            }else{
                                                $status = 'पेश गर्नुभयो';
                                            }
                                        @endphp
										<td>{{$agendaButtonHideShowStatus ? $agendaButtonHideShowStatus->user->name : ''}}</td>
										<td> {{($agendaButtonHideShowStatus ? $agendaButtonHideShowStatus->designation(): '')}}</td>
                                        
										<td style="text-align: center">{{$history->status_id  ? $status : 'फिर्ता गर्नुभयो'}}</td>
										<td>{{$history->remarks}}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					
					
                </div>
            </div>
            <div class="modal-footer">
                <span class="agenda-footer-title"><b>प्रस्तावको विषय:</b>{{substr($agenda->agenda_title, 0, 200)}}</span>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
	$('.modal').appendTo('body');
</script>