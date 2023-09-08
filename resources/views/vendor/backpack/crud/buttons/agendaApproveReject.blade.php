
@php
use Illuminate\Support\Facades\Config;
use App\Models\AgendaButtonHideShowStatus;
use App\Models\Role;
use App\Models\User;
$user_backpack = backpack_user();
$show_btn = false;
$m1_m2_c1_c2 = false;
$agenda = App\Models\Agenda::findOrFail($entry->id);
$user_role = backpack_user()->getRoleNames()[0];
$ministry_id = backpack_user()->ministry_id;
$role_id = null;
switch($user_role){


case Config::get('roles.name.ministry_creator'):
    $userIdsDetail = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.ministry_reviewer'))
        ->where('u.ministry_id', $ministry_id)
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
break;

case Config::get('roles.name.ministry_reviewer'):

    $userIdsDetail = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.ministry_secretary'))
        ->where('u.ministry_id', $ministry_id)
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
    $rejectionUserDetails = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.ministry_creator'))
        ->where('u.ministry_id', $ministry_id)
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();

break;

case Config::get('roles.name.ministry_secretary'):

    $userIdsDetail = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')        
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.cabinet_creator'))
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
    $rejectionUserDetails = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.ministry_reviewer'))
        ->where('u.ministry_id', $ministry_id)
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();


break;
case Config::get('roles.name.cabinet_creator'):

    $userIdsDetail = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id',Config::get('roles.id.cabinet_approver'))
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
    $rejectionUserDetails = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.ministry_secretary'))
        ->where('u.ministry_id', $ministry_id)
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();

break;
case Config::get('roles.name.cabinet_approver'):

    $userIdsDetail = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.chief_secretary'))
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
    $rejectionUserDetails = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.cabinet_creator'))
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();


break;
case Config::get('roles.name.chief_secretary'):
    $userIdsDetail = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.cabinet_creator'))
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
    $rejectionUserDetails = DB::table('users as u')
        ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
        ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
        ->where('mhr.role_id', Config::get('roles.id.cabinet_approver'))
        ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
        ->get();
break;

default:

break;
}


$rejectionUserIds = [];
foreach ($rejectionUserDetails as $rejection_user) {
    $rejectionUserIds[$rejection_user->id] = [
        'name' => $rejection_user->name,
        'post_name' => $rejection_user->post_name
    ];
}


$userIds = [];
foreach ($userIdsDetail as $approver_user) {
    $userIds[$approver_user->id] = [
        'name' => $approver_user->name,
        'post_name' => $approver_user->post_name
    ];
}



$all_approval_ids = AgendaButtonHideShowStatus::where('approval_history_id',$entry->agendaApprovalHistory->id)->pluck('user_id')->toArray();
    // Ministry 2nd level Permissions
    if($user_backpack->hasRole(Config::get('roles.name.ministry_reviewer'))){
        if($entry->agendaApprovalHistory->role_id >=  Config::get('roles.id.ministry_creator') && $entry->agendaApprovalHistory->status_id == 1){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        if($entry->agendaApprovalHistory->role_id >= Config::get('roles.id.ministry_secretary') && $entry->agendaApprovalHistory->status_id == 0){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        $m1_m2_c1_c2 = true;
        

    }
    // Ministry 3rd level Permissions
    if($user_backpack->hasRole(Config::get('roles.name.ministry_secretary'))){
        if($entry->agendaApprovalHistory->role_id >=  Config::get('roles.id.ministry_reviewer') && $entry->agendaApprovalHistory->status_id == 1){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        if($entry->agendaApprovalHistory->role_id <=  Config::get('roles.id.cabinet_creator') && $entry->agendaApprovalHistory->status_id == 0){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
    }
   // Cabinet 1st level Permissions
    if($user_backpack->hasRole(Config::get('roles.name.cabinet_creator'))){
        if($entry->agendaApprovalHistory->role_id == Config::get('roles.id.ministry_secretary') && $entry->agendaApprovalHistory->status_id == 1){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        if($entry->agendaApprovalHistory->role_id == Config::get('roles.id.cabinet_approver') && $entry->agendaApprovalHistory->status_id == 0){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        $m1_m2_c1_c2 = true;

        
    }
    // Cabinet 2nd level Permissions
    if($user_backpack->hasRole(Config::get('roles.name.cabinet_approver'))){
        if($entry->agendaApprovalHistory->role_id == Config::get('roles.id.cabinet_creator') && $entry->agendaApprovalHistory->status_id == 1){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        if($entry->agendaApprovalHistory->role_id == Config::get('roles.id.chief_secretary') && $entry->agendaApprovalHistory->status_id == 0){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
        $m1_m2_c1_c2 = true;

        
    }
    // Cabinet 3rd level Permissions
    if($user_backpack->hasRole(Config::get('roles.name.chief_secretary'))){

        if($entry->agendaApprovalHistory->role_id >= Config::get('roles.id.cabinet_approver') && $entry->agendaApprovalHistory->status_id == 1){
            if (in_array(backpack_user()->id , $all_approval_ids)) {
                $show_btn = true;
            }
        }
       
    }
@endphp

@if($show_btn)
    @if($entry->getStatusRejection())

        @if($m1_m2_c1_c2)
            <a onclick="ECABINET.confirmation('{{ $entry->id }}',this, '{{ json_encode($userIds) }}' )"
                id="approveAgenda-submit"
                class="btn btn-sm btn-info text-white"
                style="cursor: pointer;"
                title="प्रस्ताव पेश गर्नुहोस">{{ trans('common.resubmitAgenda') }}</a>
        @else
            <a onclick="ECABINET.confirmation('{{ $entry->id }}',this, '{{ json_encode($userIds) }}' )"
                id="approveAgenda"
                class="btn btn-sm btn-info text-white"
                style="cursor: pointer;"
                title="प्रस्ताव स्वीकृत गर्नुहोस">{{ trans('common.reapproveAgenda') }}</a>
        @endif
    @else
        @if($m1_m2_c1_c2)
            <a onclick="ECABINET.confirmation('{{ $entry->id }}',this, '{{ json_encode($userIds) }}' )"
                id="approveAgenda-submit"
                class="btn btn-sm btn-info text-white"
                style="cursor: pointer;"
                title="प्रस्ताव पेश गर्नुहोस">{{ trans('common.submitAgenda') }}</a>
        @else
            <a onclick="ECABINET.confirmation('{{ $entry->id }}',this, '{{ json_encode($userIds) }}' )"
                id="approveAgenda"
                class="btn btn-sm btn-info text-white"
                style="cursor: pointer;"
                title="प्रस्ताव स्वीकृत गर्नुहोस">{{ trans('common.approveAgenda') }}</a>
        @endif   
    @endif
    <a onclick="ECABINET.confirmationRejection('{{ $entry->id }}',this, '{{ json_encode($rejectionUserIds) }}' )"
        id="rejectAgenda"
        class="btn btn-sm btn-danger text-white"
        data-toggle="tooltip" style="cursor: pointer;" title="प्रस्ताव फिर्ता गर्नुहोस">
        {{ trans('common.rejectAgenda')}}</a>
    {{-- <a data-fancybox data-type="ajax" data-src="{{backpack_url('/agenda-reject-view/'.$entry->id)}}" class="btn btn-sm btn-danger text-white" data-toggle="tooltip" style="cursor: pointer;" title="Reject Agenda">{{ trans('common.rejectAgenda')}}</a> --}}
@endif