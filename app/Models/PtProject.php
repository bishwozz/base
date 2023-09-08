<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\PtProjectMilestone;
use App\Models\CoreMaster\MstMinistry;
use App\Models\MinistryProgramProgress;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoreMaster\MstFiscalYear;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PtProject extends BaseModel
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'pt_project';
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
    public function excelSample(){
        return '<a class="btn btn-success btn-sm float-right" href="'.asset('excelSample/projectExcel.xlsx').'" data-toggle="tooltip" title="Import from excel"><i class="fa fa-file-excel-o"></i> Download Sample</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function fiscalYear(){
        return $this->belongsTo(MstFiscalYear::class,'fiscal_year_id','id');
    }

    public function ministry(){
        return $this->belongsTo(MstMinistry::class,'ministry_id','id');
    }
    public function milestones()
    {
        return '<a href="/admin/pt-project/'.$this->id.'/milestone" style="font-size:11px;" class="btn btn-sm btn-primary p-1 px-2 mr-3" data-toggle="tooltip" title="Milestones">माइलस्टोन विवरण </a>';
    }
    public function milestoneCount()
    {
        $milestoneCount = PtProjectMilestone::where('project_id',$this->id)->get()->count();
        return '<span style="color:green;">'.$milestoneCount.'</span>';
    }

    public function ministryProgramProgress(){
        return $this->hasMany(MinistryProgramProgress::class,'project_id','id');
    }
    public function projectMilestones(){
        return $this->hasMany(PtProjectMilestone::class,'project_id','id');
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

    public function viewPage()
    {
        // return '<a href="/patient-billing/'.$this->id.'/generate_sales_bill" class="btn btn-sm btn-primary print-btn mr-2 mt-1" title="Print Bill"><i class="la la-print" style="color: white;"></i></a>';
        return '<a class="btn btn-sm btn-primary print-btn mr-2 mt-1" href="'.route("viewProject", $this->id).'"><i class="la la-eye" style="color: white;"></i></a>';

    }
}
