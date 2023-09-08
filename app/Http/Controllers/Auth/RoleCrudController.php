<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Base\BaseCrudController;
use app\Base\Operations\CreateOperation;
use app\Base\Operations\UpdateOperation;
use Backpack\PermissionManager\app\Http\Requests\RoleStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\RoleUpdateCrudRequest as UpdateRequest;

class RoleCrudController extends BaseCrudController
{
    use CreateOperation { store as traitStore; }
    use UpdateOperation { update as traitUpdate; }
    
    public function setup()
    {
        $this->role_model = $role_model = config('backpack.permissionmanager.models.role');
        $this->permission_model = $permission_model = config('backpack.permissionmanager.models.permission');

        $this->crud->setModel(Role::class);
        $this->crud->setEntityNameStrings(trans('menu.role'), trans('menu.role'));
        $this->crud->setRoute(backpack_url('role'));

        // deny access according to configuration file
        if (config('backpack.permissionmanager.allow_role_create') == false) {
            $this->crud->denyAccess('create');
        }
        if (config('backpack.permissionmanager.allow_role_update') == false) {
            $this->crud->denyAccess('update');
        }
        if (config('backpack.permissionmanager.allow_role_delete') == false) {
            $this->crud->denyAccess('delete');
        }
        if(!backpack_user()->hasRole('superadmin')){
            if(backpack_user()->hasRole('admin')){
                $this->crud->addClause('where', 'id','<>',1);
            }
        }
    }

    public function setupListOperation()
    {
        $this->crud->addColumn($this->addRowNumberColumn());

        $this->crud->addColumn([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
        ]);
        
        /**
         * In case multiple guards are used, show a column for the guard.
         */
        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addColumn([
                'name'  => 'guard_name',
                'label' => trans('backpack::permissionmanager.guard_type'),
                'type'  => 'text',
            ]);
        }

        /**
         * Show the exact permissions that role has.
         */
        $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label'     => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
            'type'      => 'custom_permission_grid',
            'name'      => 'permissions', // the method that defines the relationship in your Model
            'entity'    => 'permissions', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => $this->permission_model, // foreign key model
            'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
        ]);
    }
    public function getPermissionsss(){
        $user = User::find(backpack_user()->id);
        $permissions = $user->getAllPermissions();

        if ($user->isSystemUser()) {
            return Permission::all();
        } else {
            // $permissions = $user->getAllPermissions();
            $role_id = backpack_user()->getRoles()[0]->id;
            $permissions = Role::where('roles.id', $role_id)
            ->leftjoin('role_has_permissions as rhp', 'rhp.role_id', '=', 'roles.id')
            ->leftjoin('permissions as p', 'p.id', '=', 'rhp.permission_id')
            ->select('p.*')
            ->get();
        }

        // $role_id = backpack_user()->getRoles()[0]->id;
        // $permissions = Role::where('roles.id', $role_id)
        // ->leftjoin('role_has_permissions as rhp', 'rhp.role_id', '=', 'roles.id')
        // ->leftjoin('permissions as p', 'p.id', '=', 'rhp.permission_id')
        // ->select('p.*')
        // ->get();
        // dd($permissions);
        return $permissions;
    }

    public function setupCreateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(StoreRequest::class);

        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');
    }

    public function setupUpdateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(UpdateRequest::class);

        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');
    }

    private function addFields()
    {
        $this->crud->addField([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
            'wrapper'=>[
                'class' => 'form-group col-md-6 required',
            ]
        ]);

        $this->crud->addField([
            'name'  => 'field_name',
            'label' =>'Field Name',
            'type'  => 'text',
            'wrapper'=>[
                'class' => 'form-group col-md-6 required',
            ]
        ]);

        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addField([
                'name'    => 'guard_name',
                'label'   => trans('backpack::permissionmanager.guard_type'),
                'type'    => 'select_from_array',
                'options' => $this->getGuardTypes(),
            ]);
        }

        $this->crud->addField([
            'label'     => ucfirst(trans('backpack::permissionmanager.permission_plural')),
            'type'      => 'custom_permission_checklist',
            'name'      => 'permissions',
            'entity'    => 'permissions',
            'attribute' => 'name',
            'model'     => $this->permission_model,
            'pivot'     => true,
        ]);
    }

    /*
     * Get an array list of all available guard types
     * that have been defined in app/config/auth.php
     *
     * @return array
     **/
    private function getGuardTypes()
    {
        $guards = config('auth.guards');

        $returnable = [];
        foreach ($guards as $key => $details) {
            $returnable[$key] = $key;
        }

        return $returnable;
    }
}
