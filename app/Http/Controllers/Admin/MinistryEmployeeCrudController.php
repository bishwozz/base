<?php

namespace App\Http\Controllers\Admin;
use App\Models\EcMp;
use App\Models\Role;
use App\Models\User;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Models\MinistryEmployee;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MinistryEmployeeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryEmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryEmployeeCrudController extends BaseCrudController
{
   use ParentData;
      /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryEmployee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry/'.$this->parent('ministry_id').'/ministryemployee');
        CRUD::setEntityNameStrings(trans('MinistryMember.ministry_employee_list'), trans('MinistryMember.ministry_employee_list'));
        $this->setUpLinks();
        $this->checkPermission();
        $this->crud->denyAccess('delete');

    }
    public function tabLinks(){
        return  $this->setMinistryTabs();
    }
    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $columns = [
			$this->addRowNumberColumn(),
            [
                'name'=>'full_name',
                'label'=>trans('MinistryMember.full_name')
            ],
            [
                'name'=>'date_from_bs',
                'label'=>trans('MinistryMember.date_from')
            ],
            [
                'name'=>'date_to_bs',
                'label'=>trans('MinistryMember.date_to')
            ],
            [
                'name' => 'email',
                'label' => trans('common.email'),
                'type' => 'email',
            ],
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),

		];
        $this->crud->addColumns(array_filter($columns));
        if($this->parent('ministry_id') == null){
            abort(404);
        }
        else{
            $this->crud->addClause('where', 'ministry_id', $this->parent('ministry_id'));
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MinistryEmployeeRequest::class);

        $arr=[
            [
                'type'  => "hidden",
                'name'  => 'ministry_id',
                'value' => $this->parent('ministry_id'),
                'attributes'=>
                [
                  'required' => 'Required',
               ],
            ],
            [
                'name'  => 'full_name',
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
                'name' => 'post_id',
                'type' => 'select2',
                'entity'=>'post',
                'attribute' => 'name_lc',
                'model'=>'App\Models\MstPost',
                'label' => trans('menu.post'),
                'options'   => (function ($query) {
                    return $query->whereNotIn('id', [1,2,3])
                            ->get();
                        }),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            // [
            //     'name'=>'member_type_id',
            //     'type'=>'select',
            //     'label'=>trans('MinistryMember.member_type_id'),
            //     'entity'=>'mst_ministry_member_type',
            //     'model'=>'App\Models\MinistryMemberType',
            //     'attribute'=>'name_lc',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-4',
            //     ],
            //     'attributes'=>[
            //         'required' => 'Required',
            //         'id'=>'member-type-id',
            //         'onChange'=>'selectMemberType(this)'
            //    ],
            // ],
            [
                'name' => 'date_from_bs',
                'type' => 'nepali_date',
                'label' => trans('common.date_from_bs'),
                'attributes' => [
                    'id' => 'date_bs',
                    'relatedId' => 'date-from-ad',
                    'maxlength' => '10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

            [
                'name'=>'date_from_ad',
                'type'=>'date',
                'label'=>trans('common.date_from_ad'),
                'attributes'=>[
                    'id'=>'date-from-ad',

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'date_to_bs',
                'type'=>'nepali_date',
                'label'=>trans('common.date_to_bs'),
                'attributes'=>[

                    'id'=>'date-to-bs',
                    'relatedId' => 'date-to-ad',

                    'placeholder'  => 'yyy-mm-dd',
                    'maxlength' => '10',

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'date_to_ad',
                'type'=>'date',
                'label'=>trans('common.date_to_ad'),
                'attributes'=>[
                      'id'=>'date-to-ad',
                      'placeholder'  => 'yyy-mm-dd',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'email',
                'label' => trans('common.email'),
                'type' => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'phone_number',
                'label' => trans('common.phone'),
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'legend4',
                'type' => 'custom_html',
                'value' => '<br>',
            ],
            [ //Toggle
                'name' => 'allow_user_login',
                'label' => trans('<b>कर्मचारी लाइ सिस्टममा लग इन दिने हो?</b>'),
                'type' => 'toggle',
                'options'     => [
                    0 => 'होइन &nbsp;&nbsp;&nbsp;',
                    1 => 'हो'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'hide_when' => [
                    0 => ['role_id'],
                    1 => [],
                ],
                'default' => 0,
                'inline' => true,

            ],
            [
                'name'=>'role_id',
                'type'=>'select2',
                'label'=>trans('भूमिका'),
                'entity'=>'roleEntity',
                'model'=>Role::class,
                'attribute'=>'field_name',
                'options'   => (function ($query) {
                    return $query->whereNotIn('id',[1,3])->get();
                }),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'legend5',
                'type' => 'custom_html',
                'value' => '<br>',
            ],
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
        ];
        $this->crud->addFields(array_filter($arr));
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        DB::beginTransaction();
        try {
            // insert item in the db
            $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
            $this->data['entry'] = $this->crud->entry = $item;

            $already_exists_user = User::where('employee_id',$item->id)->first();


            if($request->allow_user_login == "1" && $already_exists_user == null){

                $request->validate([
                    'phone_number' => 'required|unique:users,phone_no',
                    'email' => 'required|email|unique:users,email',
                ]);

                $user = User::create(
                    [
                        'employee_id' => $item->id,
                        'is_ministry_member' => false,
                        'phone_no' => $request->phone_number,
                        'ministry_id' => $this->parent('ministry_id'),
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'password' => Hash::make('1'),
                    ]);

                // Get the ID of the new role from the request
                $newRoleID = $request->role_id;

                // Add the new role
                DB::table('model_has_roles')->insert([
                    'role_id' => $newRoleID,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id,
                ]);
            }else if($already_exists_user != null && $request->allow_user_login == "0"){
                $user = User::where('employee_id', $item->id)->update(['is_active' => false]);

                DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('model_id', $user->id)
                ->delete();
            }
            // else if($already_exists_user != null && $request->allow_user_login == "1"){
            //     $user = User::where('email',$request->email)->update(
            //         [
            //             'is_ministry_member' => false,
            //             'ministry_id' => $this->parent('ministry_id'),
            //             'name' => $request->full_name,
            //             'phone_no' => $request->phone_number,
            //             'email' => $request->email,
            //         ]);
            // }
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }



        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();


        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();



        // update the row in the db
        DB::beginTransaction();
        try {
             // update the row in the db
            $item = $this->crud->update(
                $request->get($this->crud->model->getKeyName()),
                $this->crud->getStrippedSaveRequest($request)
            );
            $this->data['entry'] = $this->crud->entry = $item;
            // if user has already exists 
            $already_exists_user = User::where('employee_id',$item->id)->first();




            if($request->allow_user_login == "1" && $already_exists_user == null){

                $request->validate([
                    'phone_number' => 'required|unique:users,phone_no',
                    'email' => 'required|email|unique:users,email',
                ]);

                $user = User::create(
                    [
                        'employee_id' => $item->id,
                        'is_ministry_member' => false,
                        'phone_no' => $request->phone_number,
                        'ministry_id' => $this->parent('ministry_id'),
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'password' => Hash::make('1'),
                    ]);

                // Get the ID of the new role from the request
                $newRoleID = $request->role_id;

                // Add the new role
                DB::table('model_has_roles')->insert([
                    'role_id' => $newRoleID,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id,
                ]);
            }else if($already_exists_user != null && $request->allow_user_login == "0"){
                $user = User::where('employee_id', $item->id)->update(['is_active' => false]);

                DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('model_id', $user->id)
                ->delete();
            }else if($already_exists_user != null && $request->allow_user_login == "1"){
                $already_exists_user = User::where('employee_id',$item->id)->first();


                $id_check = $already_exists_user->id ? ",".$already_exists_user->id : ",NULL";
                $email = $request->email;
                $phone_no = $request->phone_number;
                $email_check = $id_check.",id,email,".$email.",deleted_uq_code,1";
                $phone_no_check = $id_check.",id,phone_no,".$phone_no.",deleted_uq_code,1";

                $request->validate([
                    'phone_number' => 'required|max:10|min:10|unique:users,phone_no'.$phone_no_check,
                    'email' => 'required|email|unique:users,email'.$email_check,
                ]);

                $user = User::where('employee_id',$item->id)->update(
                    [
                        'is_ministry_member' => false,
                        'ministry_id' => $this->parent('ministry_id'),
                        'name' => $request->full_name,
                        'phone_no' => $request->phone_number,
                        'email' => $request->email,
                    ]);
                DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('model_id', $already_exists_user->id)
                ->delete();

                DB::table('model_has_roles')->insert([
                    'role_id' => $request->role_id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $already_exists_user->id,
                ]);
            }
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }



        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
