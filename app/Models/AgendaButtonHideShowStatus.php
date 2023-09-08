<?php

namespace App\Models;

use App\Models\Role;
use App\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaButtonHideShowStatus extends BaseModel
{
    use HasFactory;

    protected $table = 'agenda_button_hide_show_status';
    


    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function ApprovalHistory(){
        return $this->belongsTo('App\Models\AgendaApprovalHistory','created_by','id');
    }
    public function roleName(){
        $role_name = null;
        $name = DB::table('model_has_roles as mhr')->join('roles as r','r.id','mhr.role_id')->select('r.field_name')->where('mhr.model_id',$this->user_id)->first();
        if($name){
            $role_name = $name->field_name;
        }
        // $role_name = Role::where('id',$this->user_id)->first()->name;
        return $role_name;
    }
    public function designation(){
        $designation_name = null;
        $name = DB::table('users as u')->join('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->join('mst_posts as mp','mp.id','eme.post_id')
        ->select('mp.name_lc')->where('u.id',$this->user_id)->first();
        // dd($name);
        if($name){
            $designation_name = $name->name_lc;
        }
        return $designation_name;
    }

   

}


