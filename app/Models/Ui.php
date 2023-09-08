<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ui extends BaseModel
{
    use HasFactory;
    protected $table = 'uis';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
