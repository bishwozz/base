<?php

namespace App\Models;

use App\Models\Agenda;
use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Ministry extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ec_ministry';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['code','name_en','name_lc','display_order','remarks','is_active','agenda_count','email'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function agendas(){
        return $this->hasMany('App\Models\Agenda','ministry_id','id');
    }
    public function agendasForMeeting($ec_meeting_request_id = null)
    {
        return $this->agendas()
            ->where('ec_meeting_request_id',$ec_meeting_request_id)
            ->where('is_approved',true)
            ->where('is_hold',false)
            ->where('is_rejected',false)
            ->get();
    }
    public function transfered_agendas(){
        return $this->hasMany('App\Models\TransferedAgenda','ministry_id','id');
    }

    public function ministry_employee(){
        return $this->hasMany('App\Models\MinistryEmployee','ministry_id','id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
