<?php
namespace App\Base;

use App\Models\User;
use App\Eloquent\SoftDeletes;
use App\Base\Traits\ComboField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\AppSetting;
use App\Models\CoreMaster\MstFedLocalLevel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\CoreMaster\MstFedLocalLevelType;

class BaseModel extends Model
{
    use CrudTrait;
    use ComboField;
    use SoftDeletes;

    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id','created_at','created_by'];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $columns = Schema::getColumnListing($model->getTable());

            if(in_array('code', $columns)){
                $code = self::generateCode($model);
                $model->code = $code;
            }

            if(in_array('created_by', $columns)){
                $model->created_by =  !is_null(backpack_user()) ? backpack_user()->id : 1;
            }

            if(in_array('sup_org_id', $columns) && in_array('created_by', $columns)){
                if(!backpack_user()->hasRole('superadmin'))
                {
                    $model->created_by =  !is_null(backpack_user()) ? backpack_user()->id : 1;
                }
            }
        });

        static::updating(function ($model){
            $columns = Schema::getColumnListing($model->getTable());
            if(in_array('updated_by', $columns)){
                $model->created_by =  !is_null(backpack_user()) ? backpack_user()->id : 1;
            }
            if(in_array('sup_org_id', $columns)){
                if(!backpack_user()->hasRole('superadmin'))
                {
                    $model->sup_org_id =  backpack_user()->sup_org_id;
                }
            }
        });
    }

    public static function generateCode($model)
    {
        $table = $model->getTable();
        $qu = DB::table($table)
                    ->selectRaw('COALESCE(max(code::NUMERIC),0)+1 as code')
                    ->whereRaw("(code ~ '^([0-9]+[.]?[0-9]*|[.][0-9]+)$') = true");
                    // ->where('deleted_uq_code',1);
                if(in_array('office_id',$model->getFillable())){
                    $qu->where('office_id', backpack_user()->office_id);
                }
                $rec = $qu->first();
                if(isset($rec)){
                    $code = $rec->code;
                }
                else{
                    $code = 1;
                }
                return $code;
    }

    //Relation

    public function provinceEntity(){
        return $this->belongsTo(MstFedProvince::class,'province_id','id');
    }

    public function districtEntity(){
        return $this->belongsTo(MstFedDistrict::class,'district_id','id');
    }

    public function levelTypeEntity(){
        return $this->belongsTo(MstFedLocalLevelType::class,'level_type_id','id');
    }

    public function localLevelEntity(){
        return $this->belongsTo(MstFedLocalLevel::class,'local_level_id','id');
    }
    public function localLevelTypeEntity()
    {
        return $this->belongsTo(MstFedLocalLevelType::class,'level_type_id','id');
    }


}
