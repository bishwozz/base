<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\MstLevel;
use App\Models\MstPosts;
use App\Models\MstGroups;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MinistryDarbandi extends BaseModel
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ministry_darbandi';
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
        return '<a class="btn btn-success" href="'.asset('excelSample/darbandiExcel.xlsx').'" data-toggle="tooltip" title="Import from excel"><i class="fa fa-file-excel-o"></i> Sample</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function level(){
        return $this->belongsTo(MstLevel::class,'level_id','id');
    }
    public function post(){
        return $this->belongsTo(MstPosts::class,'post_id','id');
    }
    public function group(){
        return $this->belongsTo(MstGroups::class,'group_id','id');
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
