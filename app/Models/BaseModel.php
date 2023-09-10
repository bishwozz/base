<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
// use App\Base\Traits\ComboField;
use Illuminate\Database\Eloquent\Model;
use \Venturecraft\Revisionable\RevisionableTrait;
use App\Eloquent\SoftDeletes;


class BaseModel extends Model
{
    use RevisionableTrait;
    use SoftDeletes;

    /**
     * The "booting" method of the model.
     *
     * @return void
     * 
     * 
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            // dd($query->toSql());
            // $query->code = self::max('code')+1;
            // $query->code= '0'.$query->code;
        });
    }
    public function identifiableName()
    {
        return $this->name_lc;
    }
    // If you are using another bootable trait
    // be sure to override the boot method in your model
    // public static function boot()
    // {
    //     parent::boot();
    // }
    protected $dontKeepRevisionOf = ['updated_by','deleted_by','created_by','image'];
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';
    protected $revisionFormattedFields = [
        'level_id'      => 'string:%s',
        'marital_status' => 'boolean: अविवाहित | विवाहित',
        'is_active'  => 'boolean: Yes | No',
    ];
    protected $revisionFormattedFieldNames = [
        'name_en' => 'Name',
        'title' => 'Title',
    ];
}