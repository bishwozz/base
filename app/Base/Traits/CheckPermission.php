<?php

namespace  App\Base\Traits;

use ReflectionClass;
use App\Base\DataAccessPermission;
use Illuminate\Support\Facades\Schema;


/**
 *  CheckPermission
 */
trait CheckPermission
{
    private $dataPermission;
    private $user;

    protected $overRide = [];

    protected $permissions = [
        //methods => Access Permission
        'index' => 'list',
        'show' => 'list',
        'search' => 'list',
        'store' => 'create',
        'create' => 'create',
        'update' => 'update',
        'edit' => 'update',
        'destroy' => 'delete',
        'delete' => 'delete',
        'addMilestone' => 'create',
        'getMilestoneData' => 'list',
        'getproject' => 'list',
        'addprogressRecord' => 'create',
        'importExcel' => 'index',
        'editprogressRecord' => 'index',
        'timelineChart' => 'index',
        'printTimelineBar' => 'index',
        'getMinBudget' => 'index',
    ];

    public function checkPermission($include_extra_permissions=NULL)
    {

        //merge to default array if extra permission exists
        if($include_extra_permissions){
            $this->permissions= array_merge($this->permissions,$include_extra_permissions);
        }
        $new_permissions = [];
        $action = $this->crud->getActionMethod();


        //get model name and make it class name to append it into permission
        $class_name = strtolower((new ReflectionClass(get_class($this->crud->model)))->getShortName());
        foreach ($this->permissions as $key => $permission) {
            $new_permissions[$key] = $permission . ' ' . $class_name;
        }

        //get all permission of current user
        $user_permission = backpack_user()->getAllPermissions()->mapWithKeys( function ($permission, $key) {
            return [$key => $permission->name];
        })->toArray();

        ///check for excluded permission
        $excluded_permissions =  array_diff(array_values(array_unique($new_permissions)), $user_permission);
        // Remove Button for excluded permission
        // if(!backpack_user()->isSystemUser()){
            $this->crud->operation(['list','index','search'], function () use ($excluded_permissions, $class_name) {
                foreach ($excluded_permissions as $permission) {
                    $this->crud->removeButton(str_replace(' '.$class_name, '',$permission));
                }
            });


        //     /**  A function that does not need permission is ignored */

        //     if(!isset($new_permissions[$action]))
        //         goto jump;

            // Deny Access For any operation
            if (!backpack_user()->can($new_permissions[$action])) {
                $this->crud->denyAccess($this->permissions[$action]);
            }
        // }


        jump:
        $schema = Schema::getColumnListing($this->crud->model->getTable());
        // dd($schema);
        if(in_array('ministry_id',$schema) && !backpack_user()->isSystemUser()) {
            $this->crud->query->where('ministry_id',backpack_user()->ministry_id);
        }


    }



}
