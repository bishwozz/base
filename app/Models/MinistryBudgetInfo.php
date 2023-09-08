<?php

namespace App\Models;

use App\Models\CoreMaster\MstMinistry;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MinistryBudgetInfo extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ministry_budget_info';
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

    public function  fiscalYearName()
    {
        return $this->fiscalYear->code;
    }
    public function  monthNmae()
    {
        return $this->month->name_lc;
    }
    public function  ministryName()
    {
        return $this->ministry->name_lc;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function fiscalYear(){
        return $this->belongsTo(MstFiscalYear::class,'fiscal_year_id','id');
    }
    public function month(){
        return $this->belongsTo(MstNepaliMonth::class,'month_id','id');
    }
    public function ministry(){
        return $this->belongsTo(MstMinistry::class,'ministry_id','id');
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
