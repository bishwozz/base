<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\PtProject;
use App\Models\PtProjectMilestone;
use App\Models\MstMilestonesStatus;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PtProjectMilestone as ModelsPtProjectMilestone;

class PtProjectMilestoneDetails extends BaseModel
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'progress_milestones_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function project(){
        return $this->belongsTo(PtProject::class,'project_id','id');
    }

    public function milestone(){
        return $this->belongsTo(PtProjectMilestone::class,'milestone_id','id');
    }
    public function milestoneStatus(){
        return $this->belongsTo(MstMilestonesStatus::class,'milestone_status_id','id');
    }

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
