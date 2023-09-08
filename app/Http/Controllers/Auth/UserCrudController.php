<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\UserCreateRequest;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\AppSetting;
// 

class UserCrudController extends BaseCrudController
{

    


    public function setup()
    {
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('menu.user'), trans('menu.user'));
        $this->crud->setRoute('admin/user');
        // $this->checkPermission();
        /*
            To list the user lower than its heirarchy
        */
        if(!backpack_user()->hasRole('superadmin')){
            if(backpack_user()->hasRole('admin')){
                $this->crud->addClause('where', 'id','<>',1);
            }else{
                $this->crud->addClause('where', 'ministry_id',backpack_user()->ministry_id);
            }
        }
    }
    public function setupListOperation()
    {
        $cols = [
            $this->addRowNumberColumn(),
           [
            'name' => 'ministry_id',
            'type' => 'select',
            'entity' => 'ministry',
            'attribute' => 'name_lc',
            'label' => 'मन्त्रालय',
            'model' => MstMinistry::class,
           ],
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('backpack::permissionmanager.roles'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'roles', // the method that defines the relationship in your Model
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'field_name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.role'), // foreign key model
            ],
            // [ 
            //     'label'     => trans('Last Login'), // Table column heading
            //     'type'      => 'text',
            //     'name'      => 'last_login', // the method that defines the relationship in your Model
                
            // ],
        ];

        $cols = array_filter($cols);

        $this->crud->addColumns($cols);
    }

    public function addFields()
    {
        $ministry_id = [];
        if(backpack_user()->hasAnyRole('superadmin|admin')){
            $ministry_id = [
                'name' => 'ministry_id',
                'type' => 'select2',
                'entity' => 'ministry',
                'attribute' => 'name_lc',
                'label' => 'मन्त्रालय',
                'model' => MstMinistry::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ];
        }
      $arr = [

            [
                'type' => 'custom_html',
                'name'=>'custom_html_1',
                'value' => '<br/>',
            ],
            $ministry_id,
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                    'id'=>'full_name',
                ], 
            ],
            
           
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                    'id'=>'email',
                ], 
            ],
            [
                'name'  => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'type' => 'custom_html',
                'name'=>'custom_html_2',
                'value' => '<br/>',
            ],
            [
                // two interconnected entities
                'label'             => trans('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency_custom',
                'name'              => ['roles', 'permissions'],
                'subfields'         => [
                    'primary' => [
                        'label'            => trans('backpack::permissionmanager.roles'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'field_name', // foreign key attribute that is shown to user
                        'model'            => Role::class, // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 4, //can be 1,2,3,4,6
                        'option' => $this->getPrivateRoles(), // to get custom roles that it is allowed to see

                    ],
                    'secondary' => [
                        'label'          => ucfirst(trans('backpack::permissionmanager.permission_singular')),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => Permission::class, // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 4, //can be 1,2,3,4,6
                        'option' => $this->getPrivatePermissions(), //to get custom permissions that it is given

                    ],
                ],
            ],
        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }

    public function setupCreateOperation()
    {
        $this->crud->setValidation(UserCreateRequest::class);     
        $this->addFields();
    }
    
    public function setupUpdateOperation()
    {
        $this->crud->setValidation(UserUpdateRequest::class);
        $this->addFields();
    }

    // to fetch private roles
    public function getPrivateRoles()
    {
        if(backpack_user()->hasRole('superadmin')){
            return Role::all();
        }else{
            if(backpack_user()->hasRole('admin')){
                return Role::where('id','<>',1)->get();
            }else{
                return Role::where('id','<>',1)->where('id','<>',2)->get();
            }
        }
    }

    // To fetch private permissions
    public function getPrivatePermissions(){
       $user = User::find(backpack_user()->id);
       
       if(backpack_user()->hasRole('superadmin')){
            return Permission::all();
       }else{
            $permissions = $user->getAllPermissions();
            return $permissions;
       }
    }
    


    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $user = backpack_user();

        $request = $this->crud->validateRequest();
        if(!$user->hasAnyRole('superadmin|admin')){
            $request->request->set('ministry_id', $user->ministry_id);
        }
        $request->request->set('created_by', $user->id);
        $request->request->set('updated_by', $user->id);
    
        //save full_name, email and password for sending email
        $email_details = [
            'full_name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
        ];


        //encrypt password
        $request = $this->handlePasswordInput($request);

        DB::beginTransaction();
        try {
                $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));  
                if($item && env('SEND_MAIL_NOTIFICATION') == TRUE){
                    $this->send_mail($email_details);
                }
            // $this->client_user->notify(new TicketCreatedNotification($item));
            $current_fiscal_year_id = MstFiscalYear::where('code',get_current_fiscal_year())->first()->id;
            $ministry = MstMinistry::find($item->ministry_id);
            $app_setting = AppSetting::create([
                'ministry_id'=>$item->ministry_id,
                'fiscal_year_id'=>$current_fiscal_year_id,
                'office_name_lc'=>$ministry->name_lc,
                'office_name_en'=>$ministry->name_en,
        ]);
            \Alert::success(trans('backpack::crud.insert_success'))->flash();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }


    public function update()
    {
        $this->crud->hasAccessOrFail('update');
        $user = backpack_user();

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $request->request->set('updated_by', $user->id);
        $request->request->set('client_id', $user->client_id);


        //save full_name, email and password for sending email
        $email_details = [
            'full_name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
        ];
        //encrypt password
        $request = $this->handlePasswordInput($request);

        DB::beginTransaction();
        try {
                $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                        $request->except(['save_action', '_token', '_method', 'http_referrer']));

                // if($item && env('SEND_MAIL_NOTIFICATION') == TRUE){
                //     $this->send_mail($email_details);
                // }
            \Alert::success(trans('backpack::crud.update_success'))->flash();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }
   
}