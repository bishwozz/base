<?php

namespace App\Models;

use App\Models\Committee;
use App\Models\AgendaDecisionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaHistory extends Model
{
    use HasFactory;

    protected $table = 'agenda_histories';
    
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id','created_at'];
    protected $fillable = ['ec_meeting_request_id','agenda_id','transfered_to','step_id','remarks','ministry_id',
    'decision_of_cabinet','decision_of_committee','transfered_agenda_id','file_upload','agenda_decision_type_id'];
    // protected $hidden = [];
    // protected $dates = [];

    public function agenda(){
        return $this->belongsTo('App\Models\Agenda','agenda_id','id');
    }
    public function ministry_entity(){
        return $this->belongsTo('App\Models\Ministry','ministry_id','id');
    }

    public function employee_entity(){
        $chief_secretary = DB::table('ec_ministry_employees as eme')->select('full_name')->where('post_id', 4)->first();
        if($chief_secretary){
            return $chief_secretary->full_name;
        }
        return;
    }
    public function meeting(){
        return $this->belongsTo('App\Models\EcMeetingRequest','ec_meeting_request_id','id');
    }
    public function committee(){
        return $this->belongsTo(Committee::class, 'transfered_to_committee','id');
    }
    public function agenda_decision_type(){
        return $this->belongsTo(AgendaDecisionType::class, 'agenda_decision_type_id','id');
    }
}
