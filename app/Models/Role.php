<?php

namespace App\Models;

use App\Models\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Models\Role as OriginalRole;

class Role extends OriginalRole
{
    use CrudTrait;
    protected $guard_name = 'backpack';
    
    protected $fillable = ['name', 'guard_name', 'field_name','updated_at', 'created_at'];

    // public function users()
    // {
    //     // Assuming a belongsToMany relationship
    //     return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    // }
}
