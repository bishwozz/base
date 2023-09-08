<?php

namespace App\Models;

use App\Models\EcMp;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login',
        'mp_id',
        'ministry_id',
        'display_order',
        'phone_no',
        'is_ministry_member',
        'mp_id',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function mp(){
        return $this->belongsTo(Ministry::class,'ministry_id','id');
    }
    public function MinistryEmployee(){
        return $this->belongsTo(MinistryEmployee::class,'employee_id','id');
    }

    public function uis()
    {
        return $this->hasMany('App\Models\Ui','user_id','id');
    }

    public function ministry(){
        return $this->belongsTo('App\Models\Ministry','ministry_id','id');
    }

    //assign role to user

    public function assignRoleCustom($role_name, $model_id){
        $roleModel = Role::where('name', $role_name)->first();
        if(!$roleModel){
            return "role doesnot exists";
        }else{
            DB::table('model_has_roles')->insert([
                'role_id' => $roleModel->id,
                'model_type' => 'App\Models\User',
                'model_id' => $model_id,
            ]);
        }

    }

    public function commiteeEntity()
    {
       return $this->belongsTo('App\Models\MinistryMember', 'mp_id', 'mp_id');
    }
}
