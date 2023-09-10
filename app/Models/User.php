<?php

namespace App\Models;

use App\Models\Role;
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
    use HasApiTokens, HasFactory, Notifiable, CrudTrait, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ministry_id',
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

    public function isSystemUser(){
        if(isset($this->ministry_id))
            return false;
        else {
            return true;
        }
    }

    public function ministry()
    {
        return $this->belongsTo('App\Models\CoreMaster\MstMinistry', 'ministry_id', 'id');
    }
}
