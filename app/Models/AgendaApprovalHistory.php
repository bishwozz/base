<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaApprovalHistory extends BaseModel
{
    use HasFactory;

    protected $table = 'agenda_approval_history';
    
    protected $status = [ 0 => 'Reject', 1 => 'Approved'];




    public function user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id','id');
    }

    public function designation(){
        $designation_name = null;
        $name = DB::table('users as u')->join('ec_ministry_employees as eme','eme.id','u.employee_id')
        ->join('mst_posts as mp','mp.id','eme.post_id')
        ->select('mp.name_lc')->where('u.id',$this->created_by)->first();
        if($name){
            $designation_name = $name->name_lc;
        }
        return $designation_name;
    }

}


