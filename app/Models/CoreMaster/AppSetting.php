<?php

namespace App\Models\CoreMaster;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class AppSetting extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'app_settings';
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function ministry()
    {
        return $this->belongsTo('App\Models\CoreMaster\MstMinistry', 'ministry_id', 'id');
    }
    public function fiscalYearEntity()
    {
        return $this->belongsTo('App\Models\CoreMaster\MstFiscalYear', 'fiscal_year_id', 'id');
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
