<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\EcMp;
use App\Models\Role;
use App\Models\User;
use App\Models\Ministry;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Base\BaseCrudController;
use App\Models\MinistryEmployee;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Modules\HR\Entities\HrEmployee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Base\Operations\CreateOperation;
use App\Base\Operations\UpdateOperation;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;;

class UserCrudController extends BaseCrudController
{


    protected $client_user ;


    public function setup()
    {
        $this->client_user = backpack_user();
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('menu.user'), trans('menu.user'));
        $this->crud->setRoute('admin/user');
        $this->addFilters();

        $this->checkPermission(['fetchMpDetail'=>'fetchMpDetail']);
        if(!backpack_user()->hasRole('superadmin')){
            if(backpack_user()->hasRole('admin')){
                $this->crud->addClause('where', 'id','<>',1);
            }else{
                if(backpack_user()->hasRole('operator')){
                    $this->crud->addClause('where', 'id','<>',1);
                    $this->crud->addClause('where', 'id','<>',2);
                }else{
                    if(backpack_user()->hasRole('ministry')){
                        $this->crud->addClause('where', 'id','<>',1);
                        $this->crud->addClause('where', 'id','<>',2);
                        $this->crud->addClause('where', 'id','<>',3);
                    }else{
                        $this->crud->addClause('where', 'id','<>',1);
                        $this->crud->addClause('where', 'id','<>',2);
                        $this->crud->addClause('where', 'id','<>',3);
                        $this->crud->addClause('where', 'id','<>',4);
                    }
                }
            }
        }
    }

    public function addFilters()
    {
        $user = backpack_user();

        if($user->hasRole(Config::get('roles.name.cabinet_creator')) || $user->hasRole(Config::get('roles.name.cabinet_approver')) || $user->hasRole(Config::get('roles.name.chief_secretary')) || $user->hasRole('minister') || $user->hasRole('admin')){
            $this->crud->addFilter([
                'name'=>'ministry_id',
                'label'=> 'मन्त्रालयको नाम',
                'type'=>'select2'
                ], function() {
                    return Ministry::all()->pluck('name_lc', 'id')->toArray();
                }, function($value) {
                    $this->crud->addClause('where', 'ministry_id', $value);
                });

                $this->crud->addFilter(
                    [ // simple filter
                        'type' => 'select2',
                        'name' => 'is_ministry_member',
                        'label' => trans('प्रयोगकर्ता प्रकार'),
                    ],
                    [
                        1 => 'सदस्य',
                        0 => 'कर्मचारी',
                        2 => 'सबै',
                    ],
                    function ($value) { // if the filter is active
                        // dd($value);
                        if($value < 2){
                            $this->crud->addClause('where', 'is_ministry_member', $value);
                        }
                    }
                );
        }

    }
    public function setupListOperation()
    {
        $cols = [
            $this->addRowNumberColumn(),
            [
                'name'  => 'name',
                'label' => trans('common.name'),
                'type'  => 'text',
            ],
            // [
            //     'name' => 'mp_id',
            //     'type' => 'select',
            //     'entity'=>'mp',
            //     'attribute' => 'name_lc',
            //     'model'=>'App\Models\EcMp',
            //     'label' => trans('common.mp'),
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-6',
            //     ],
            // ],
            [
                'name' => 'is_ministry_member',
                'label' => trans('common.mp_or_member_selected'),
                'type' => 'radio',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'options'     => [
                    1 => trans('common.user_toggle_choice1'),
                    0 => trans('common.user_toggle_choice2'),
                ],
            ],

            [
                'name' => 'ministry_id',
                'type' => 'select',
                'entity'=>'ministry',
                'attribute' => 'name_lc',
                'model'=>'App\Models\Ministry',
                'label' => trans('common.ministry'),
            ],
            [
                'name'  => 'email',
                'label' => trans('common.email'),
                'type'  => 'email',
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('common.role'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'roles', // the method that defines the relationship in your Model
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'field_name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.role'), // foreign key model
            ],
            [
                'name' => 'display_order',
                'type' => 'number',
                'label' => trans('common.display_order'),
            ],
        ];

        $cols = array_filter($cols);

        $this->crud->addColumns($cols);
    }

    public function addFields()
    {

       $mode = $this->crud->getActionMethod();
       if($mode == 'edit'){

        $ministry_id = [
            'name' => 'ministry_id',
            'type' => 'select2',
            'entity'=>'ministry',
            'attribute' => 'name_lc',
            'model'=>'App\Models\Ministry',
            'label' => trans('common.ministry'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ],
        ];

        $mp_id = [
            'name'=>'mp_id',
            'label'=>trans('common.mp'),
            'type'=>'select2_from_ajax',
            'model'=>EcMp::class,
            'entity'=>'mp',
            'attribute'=>'name_lc',
            'method'=>'post',
            'data_source' => url("api/getMP/ministry_id"),
            'minimum_input_length' => 0,
            'dependencies'=> ['ministry_id'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'id' =>'mp_id',
                'placeholder' => 'Select a Minister',
                'onchange' =>'ECABINET.fetchMpById(this)',
                'disabled' => 'disabled'
            ],
        ];
        $ministry_employee_id = [
            'name'=>'employee_id',
            'label'=> trans('common.ministry_employee'),
            'type'=>'select2_from_ajax',
            'model'=>MinistryEmployee::class,
            'entity'=>'MinistryEmployee',
            'attribute'=>'full_name',
            'method'=>'post',
            'data_source' => url("api/get-ministry-employee/ministry_id"),
            'minimum_input_length' => 0,
            'dependencies'=> ['ministry_id'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'id' =>'employee_id',
                'placeholder' => 'Select a Minister',
                'onchange' =>'ECABINET.fetchMinistryEmployeeById(this)',
                'disabled' => 'disabled'
            ],
        ];
        $is_ministry_member =  [
            'name' => 'is_ministry_member',
            'label' => trans('common.mp_or_member'),
            'type' => 'switch_toggle',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'default' => false,
            'hide_when' => [
                1 => ['employee_id'],
                0 => ['mp_id'],
            ],
            'inline' => true,
            'options'     => [
                1 => trans('common.user_toggle_choice1'),
                0 => trans('common.user_toggle_choice2'),
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ],
        ];

       }else{

        $ministry_id = [
            'name' => 'ministry_id',
            'type' => 'select2',
            'entity'=>'ministry',
            'attribute' => 'name_lc',
            'model'=>'App\Models\Ministry',
            'label' => trans('common.ministry'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
        ];

        $mp_id = [
            'name'=>'mp_id',
            'label'=>trans('common.mp'),
            'type'=>'select2_from_ajax',
            'model'=>EcMp::class,
            'entity'=>'mp',
            'attribute'=>'name_lc',
            'method'=>'post',
            'data_source' => url("api/getMP/ministry_id"),
            'minimum_input_length' => 0,
            'dependencies'=> ['ministry_id'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'id' =>'mp_id',
                'placeholder' => 'Select a Minister',
                'onchange' =>'ECABINET.fetchMpById(this)'
            ],
        ];
        $ministry_employee_id = [
            'name'=>'employee_id',
            'label'=> trans('common.ministry_employee'),
            'type'=>'select2_from_ajax',
            'model'=>MinistryEmployee::class,
            'entity'=>'MinistryEmployee',
            'attribute'=>'full_name',
            'method'=>'post',
            'data_source' => url("api/get-ministry-employee/ministry_id"),
            'minimum_input_length' => 0,
            'dependencies'=> ['ministry_id'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'id' =>'employee_id',
                'placeholder' => 'Select a Minister',
                'onchange' =>'ECABINET.fetchMinistryEmployeeById(this)'
            ],
        ];
        $is_ministry_member =  [
            'name' => 'is_ministry_member',
            'label' => trans('common.mp_or_member'),
            'type' => 'switch_toggle',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'default' => false,
            'hide_when' => [
                1 => ['employee_id'],
                0 => ['mp_id'],
            ],
            'inline' => true,
            'options'     => [
                1 => trans('common.user_toggle_choice1'),
                0 => trans('common.user_toggle_choice2'),
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
            $is_ministry_member,
            $ministry_employee_id,
            $mp_id,

            [
                'name'  => 'name',
                'label' => trans('common.name'),
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
                'label' => trans('common.email'),
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
                'label' => trans('common.password'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('common.password_confirmation'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'display_order',
                'type' => 'number',
                'label' => trans('common.display_order'),
                'default' => 0,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'phone_no',
                'type' => 'number',
                'label' => trans('फोन नम्बर'),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'maxlength' => '10',
                ],
            ],
            [
                'type' => 'custom_html',
                'name'=>'custom_html_2',
                'value' => '<br/>',
            ],
            [
                // two interconnected entities
                'label'             => trans('common.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency_custom',
                'name'              => ['roles', 'permissions'],
                'subfields'         => [
                    'primary' => [
                        'label'            => trans('common.role'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'field_name', // foreign key attribute that is shown to user
                        'model'            => Role::class, // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 4, //can be 1,2,3,4,6
                        'option' => $this->getPrivateRoles(),
                    ],
                    'secondary' => [
                        'label'          => trans('common.permission'),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => Permission::class, // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 4, //can be 1,2,3,4,6
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

    public function getPrivateRoles()
    {
        if(backpack_user()->hasRole('superadmin')){
            return Role::all();
        }else{
            if(backpack_user()->hasRole('admin')){
                return Role::where('id','<>',1)->get();
            }else{
                if(backpack_user()->hasRole('operator')){
                    return Role::where('id','<>',1)->where('id','<>',2)->get();
                }else{
                    if(backpack_user()->hasRole('ministry')){
                        return Role::where('id','<>',1)->where('id','<>',2)->where('id','<>',3)->get();
                    }else{
                        return Role::where('id','<>',1)->where('id','<>',2)->where('id','<>',3)->where('id','<>',4)->get();
                    }
                }
            }
        }
    }


    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $user = backpack_user();

        $request = $this->crud->validateRequest();
        $request->request->set('created_by', $user->id);
        $request->request->set('updated_by', $user->id);




        //encrypt password
        $request = $this->handlePasswordInput($request);

        DB::beginTransaction();
        try {
                $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));

                $token = Str::random(60);

                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                $base_url = url('/');

                //save full_name, email and password for sending email
                $email_details = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'link' => $base_url.'/admin/password-reset' .'?token='. $token . '&email=' . urlencode($request->email)
                ];

                if($item && env('SEND_MAIL_NOTIFICATION') == TRUE){
                    Mail::send('sendEmail.user_create',$email_details, function($message) use ($email_details){
                        $message->to($email_details['email'])
                        ->subject('खाता प्रमाणहरू');
                    });
                }

            // $this->client_user->notify(new TicketCreatedNotification($item));

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

                $token = Str::random(60);

                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                $base_url = url('/');
                $email_details = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'link' => $base_url.'/admin/password-reset' .'?token='. $token . '&email=' . urlencode($request->email)
                ];

                if($item && env('SEND_MAIL_NOTIFICATION') == TRUE){
                    Mail::send('sendEmail.user_create',$email_details, function($message) use ($email_details){
                        $message->to($email_details['email'])
                        ->subject('खाता प्रमाणहरू');
                    });
                }

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

    public function fetchMpDetail(Request $request){
         $mpId  = $request->mpId;
         $mp = EcMp::findOrFail($mpId);
         return response()->json([
             'message' =>'success',
             'user' =>$mp,
         ]);

    }

    public function fetchMinistryEmployeeDetail(Request $request){
         $ministry_employee_id  = $request->ministry_employee_id;
         $me = MinistryEmployee::findOrFail($ministry_employee_id);
         return response()->json([
             'message' =>'success',
             'user' =>$me,
         ]);

    }

}
