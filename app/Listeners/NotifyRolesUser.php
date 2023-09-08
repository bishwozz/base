<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Models\AgendaApprovalHistory;
use App\Events\AgendaApprovedRejected;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AgendaButtonHideShowStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyRolesUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AgendaApprovedRejected  $event
     * @return void
     */
    public function handle(AgendaApprovedRejected $event)
    {

        $role_id = 9;
        $user_ids = [];
        // dd($event->data['roles_id']);
        
        if(isset($event->data['status_id']) && $event->data['status_id'] == 1 && Config::get('roles.id.ministry_creator') == $event->data['roles_id']){
            // $user_ids = DB::table('model_has_roles')
            // ->select('model_id')
            // ->where('role_id', Config::get('roles.id.ministry_reviewer'))
            // ->get()
            // ->pluck('model_id')
            // ->toArray();
            // $user_ids = DB::table('users')->select('id')->whereIn('id',$user_ids)->where('ministry_id', $event->data['ministry_id'])->get()->pluck('id')->toArray();
            $role_id = Config::get('roles.id.ministry_reviewer');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 1 && Config::get('roles.id.ministry_reviewer') == $event->data['roles_id']){
            // $user_ids = DB::table('model_has_roles')
            // ->select('model_id')
            // ->where('role_id', Config::get('roles.id.ministry_secretary'))
            // ->get()
            // ->pluck('model_id')
            // ->toArray();
            // $user_ids = DB::table('users')->select('id')->whereIn('id',$user_ids)->where('ministry_id', $event->data['ministry_id'])->get()->pluck('id')->toArray();
            $role_id = Config::get('roles.id.ministry_secretary');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 0 && Config::get('roles.id.ministry_reviewer') == $event->data['roles_id']){
            // $user_ids = DB::table('model_has_roles')
            // ->select('model_id')
            // ->where('role_id', Config::get('roles.id.ministry_creator'))
            // ->get()
            // ->pluck('model_id')
            // ->toArray();
            $user_ids = DB::table('users')->select('id')->whereIn('id',$user_ids)->where('ministry_id', $event->data['ministry_id'])->get()->pluck('id')->toArray();
            $role_id = Config::get('roles.id.ministry_creator');


        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 1 && Config::get('roles.id.ministry_secretary') == $event->data['roles_id']){
            // $user_ids = DB::table('model_has_roles')
            // ->select('model_id')
            // ->where('role_id', Config::get('roles.id.cabinet_creator'))
            // ->get()
            // ->pluck('model_id')
            // ->toArray();
            $role_id = Config::get('roles.id.cabinet_creator');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 0 && Config::get('roles.id.ministry_secretary') == $event->data['roles_id']){
            // $user_ids = DB::table('model_has_roles')
            // ->select('model_id')
            // ->where('role_id', Config::get('roles.id.ministry_reviewer'))
            // ->get()
            // ->pluck('model_id')
            // ->toArray();
            // $user_ids = DB::table('users')->select('id')->whereIn('id',$user_ids)->where('ministry_id', $event->data['ministry_id'])->get()->pluck('id')->toArray();
            $role_id = Config::get('roles.id.ministry_reviewer');


        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 1 && Config::get('roles.id.cabinet_creator') == $event->data['roles_id']){
            $user_ids = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', Config::get('roles.id.cabinet_approver'))
            ->get()
            ->pluck('model_id')
            ->toArray();
            $role_id = Config::get('roles.id.cabinet_approver');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 0 && Config::get('roles.id.cabinet_creator') == $event->data['roles_id']){
            $user_ids = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', Config::get('roles.id.ministry_secretary'))
            ->get()
            ->pluck('model_id')
            ->toArray();
            $role_id = Config::get('roles.id.ministry_secretary');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 1 && Config::get('roles.id.cabinet_approver') == $event->data['roles_id']){
            $user_ids = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', Config::get('roles.id.chief_secretary'))
            ->get()
            ->pluck('model_id')
            ->toArray();
            $role_id = Config::get('roles.id.chief_secretary');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 0 && Config::get('roles.id.cabinet_approver') == $event->data['roles_id']){
            $user_ids = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', Config::get('roles.id.cabinet_creator'))
            ->get()
            ->pluck('model_id')
            ->toArray();
            $role_id = Config::get('roles.id.cabinet_creator');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 1 && Config::get('roles.id.chief_secretary') == $event->data['roles_id']){
            $user_ids = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', Config::get('roles.id.cabinet_creator'))
            ->get()
            ->pluck('model_id')
            ->toArray();
            $user_ids = DB::table('users')->select('id')->whereIn('id',$user_ids)->get()->pluck('id')->toArray();
            $role_id = Config::get('roles.id.cabinet_creator');

        }elseif(isset($event->data['status_id']) && $event->data['status_id'] == 0 && Config::get('roles.id.chief_secretary') == $event->data['roles_id']){
            $user_ids = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', Config::get('roles.id.cabinet_approver'))
            ->get()
            ->pluck('model_id')
            ->toArray();
            $role_id = Config::get('roles.id.cabinet_approver');
        }

        DB::beginTransaction();
        try{
         
          

            if($event->data['type'] == 'Agenda'){
                $user_ids = $event->data['userIds'];


                if($event->data['remarks'] !== null && $user_ids == null){

                    $history_id = AgendaApprovalHistory::select('id')->where('agenda_id',$event->data['agenda_id'])->latest()->first()->id;
                    $user_ids = AgendaButtonHideShowStatus::select('created_by')->where('approval_history_id', $history_id)->distinct('created_by')->pluck('created_by')->toArray();
                }

                $agendaApprovalHistory = AgendaApprovalHistory::create([
                    
                    'status_id' => $event->data['status_id'],
                    'role_id' => getRoleId(),
                    'remarks' => $event->data['remarks'],
                    'agenda_id' => $event->data['agenda_id'],
                    'date_ad'=>dateToday(),
                    'date_bs'=>convert_bs_from_ad(),
                ]);
    
                foreach($user_ids as $user_id){
                    $agendaButtonHideShowStatus = AgendaButtonHideShowStatus::create([
                        'approval_history_id' => $agendaApprovalHistory->id,
                        'user_id' => $user_id,
                    ]);
                }

            }
            // elseif($event->data['type'] == 'Agenda'){

            // }elseif($event->data['type'] == ''){

            // }


            foreach($user_ids as $user_id){

                // Insert the data into the users table
                DB::table('notifications')->insert([
                    'status_id' => $event->data['status_id'],
                    'roles_id' => $role_id,
                    'agenda_id' => $event->data['agenda_id'],
                    'meeting_request_id' => $event->data['meeting_request_id'],
                    'meeting_minute_id' => $event->data['meeting_minute_id'],
                    'user_id' => $user_id,
                    'from_role_name' => getRoleNameFirst(),
                    'from_user_name' => getUserName(),
                    'ministry_id' => $event->data['ministry_id'],
                    'type' => $event->data['type'],
                    'data' => $event->data['data'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


            } 

            


            // broadcast(new AgendaApprovedRejected($user_ids, $event->data))->toOthers();
            DB::commit();
            // broadcast(new AgendaApprovedRejected());
            // broadcast(new AgendaApprovedRejected($user_ids, $event->data))->toOthers()->onPrivateChannel('agenda-updates');


            return 1;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }



        
    }

}
