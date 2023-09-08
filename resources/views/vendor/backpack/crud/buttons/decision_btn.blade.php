@php
use App\Models\AgendaHistory;
use App\Models\AgendaApprovalHistory;

$agenda_history = AgendaApprovalHistory::where('agenda_id', $entry->getKey())->orderBy('created_at','desc')->get();
$agenda_details = AgendaApprovalHistory::where('agenda_id',$entry->getKey())->orderBy('created_at','DESC')->first();

@endphp
    @if(isset($agenda_details) && ($agenda_details->status_id === 1 && $agenda_details->roles_id === Config::get('roles.id.chief_secretary')))
    <a  target="_blank" href="{{backpack_url('/print-agenda/'.$entry->getKey(). '/report')}}" class="btn btn-success btn-sm" style="cursor: pointer;">{{trans("common.decisionTaken")}} </a>
    @endif
<script>
	$('.modal').appendTo('body');
</script>