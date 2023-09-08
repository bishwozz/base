@php
    use App\Models\Role;
    use App\Models\User;

    $agenda = App\Models\Agenda::findOrFail($entry->id);
    $user_role = backpack_user()->getRoleNames()[0];
    $ministry_id = backpack_user()->ministry_id;
   
        if(Config::get('roles.name.ministry_creator')){
            $role_id = Config::get('roles.id.ministry_reviewer');
            $userIdsDetail = DB::table('users as u')
                ->leftJoin('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
                ->leftJoin('ec_ministry_employees as eme','eme.id','u.employee_id')
                ->leftJoin('mst_posts as mp','mp.id','eme.post_id')
                ->where('mhr.role_id', Config::get('roles.id.ministry_reviewer'))
                ->where('u.ministry_id', $ministry_id)
                ->select('u.id','u.name','mp.name_lc as post_name') // select the 'id' column
                ->get();
        }
 

    $userIds = [];
    foreach ($userIdsDetail as $approver_user) {
        $userIds[$approver_user->id] = [
            'name' => $approver_user->name,
            'post_name' => $approver_user->post_name
        ];
    }

@endphp
@if ($entry->is_submitted == true)
@else
    @if ($entry->getStatusRejection())
        <a onclick="ECABINET.confirmation('{{ $entry->id }}',this, '{{ json_encode($userIds) }}')" id="submit-agenda" class="btn btn-sm btn-info" style="cursor: pointer;"
            title="प्रस्ताव पेश गर्नुहोस">{{ trans('common.resubmitAgenda') }} </a>
    @else
        @if($entry->created_by == backpack_user()->id)
        <a onclick="ECABINET.confirmation('{{ $entry->id }}',this, '{{ json_encode($userIds) }}' )" id="submit-agenda" class="btn btn-sm btn-info" style="cursor: pointer;"
            title="प्रस्ताव पेश गर्नुहोस">{{ trans('common.submitAgenda') }}</a>
        @endif
    @endif
@endif
