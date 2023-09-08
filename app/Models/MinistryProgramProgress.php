<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\PtProject;
use App\Models\CoreMaster\MstMinistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Models\PtProjectMilestoneDetails;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MinistryProgramProgress extends BaseModel
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ministry_program_progress';
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
    public static $status =[
        0 => 'काम सुरु नभएको',
        1 => 'काम भइरहेको',
        1 => 'सम्पन्न',
    ];


    public function  fiscalYearName()
    {
        return $this->fiscalYear->code;
    }
    public function  reportingFiscalYearName()
    {
        return isset($this->reportingFiscalYear)?$this->reportingFiscalYear->code:'';
    }
    public function  monthNmae()
    {
        return $this->month->name_lc;
    }
    public function  ministryName()
    {
        return $this->ministry->name_lc;
    }
    public function  projectName()
    {
        return $this->project ? $this->project->project_name : '';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function fiscalYear(){
        return $this->belongsTo(MstFiscalYear::class,'fiscal_year_id','id');
    }
    public function reportingFiscalYear(){
        return $this->belongsTo(MstFiscalYear::class,'reporting_fiscal_year_id','id');
    }
    public function month(){
        return $this->belongsTo(MstNepaliMonth::class,'month_id','id');
    }

    public function ministry(){
        return $this->belongsTo(MstMinistry::class,'ministry_id','id');
    }

    public function project(){
        return $this->belongsTo(PtProject::class,'project_id','id');
    }
    public function ProgressMilestoneDetails(){
        return $this->hasMany(PtProjectMilestoneDetails::class,'progress_id','id');
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

    public function setFileUploadAttribute($value){
        $attribute_name = "file_upload";
        $disk = "uploads";

        $ministry_detail= request()->ministry_id;
        $user_id = (isset(request()->id) ? request()->id : 0);

        $path  = 'MinistryProgramProgress/###MINISTRY_ID###/###USER_ID###/Files/';
        $destination_path = str_replace("###MINISTRY_ID###", $ministry_detail, $path);
        $destination_path = str_replace("###USER_ID###", $user_id, $destination_path);
        
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($obj) {
            \Storage::disk('uploads')->delete($obj->photo_path);
        });
    }

    public function getSlugWithLink() {
        return '<a href="/storage/uploads/'.$this->file_upload.'" target="_blank"><i class="las la-file-pdf" style="font-size:25px;color:red;"></i></a>';
    }
}
