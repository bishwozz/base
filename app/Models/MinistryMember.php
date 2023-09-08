<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MinistryMember extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ec_ministry_members';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function ec_mp(){
        return $this->belongsTo('App\Models\EcMp','mp_id','id');
    }

    // public function mst_ministry_member_type()
    // {
    //     return $this->belongsTo('App\Models\MinistryMemberType','member_type_id','id');
    // }

    public function roleEntity(){
        return $this->belongsTo(Role::class,'role_id','id');
    }


    public function ec_com(){
        return $this->belongsTo('App\Models\Ministry','ministry_id','id');
    }
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
