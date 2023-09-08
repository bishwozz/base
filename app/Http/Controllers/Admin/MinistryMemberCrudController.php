<?php

namespace App\Http\Controllers\Admin;
use Hash;
use App\Models\EcMp;
use App\Models\Role;
use App\Models\User;
use App\Models\MinistryMember;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Http\Requests\MinistryMemberRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryMemberCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryMemberCrudController extends BaseCrudController
{
   use ParentData;
      /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryMember::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry/'.$this->parent('ministry_id').'/ministrymember');
        CRUD::setEntityNameStrings(trans('MinistryMember.ministry_member_list'), trans('MinistryMember.ministry_member_list'));
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
                'name'=>'mp_id',
                'type'=>'select',
                'label'=>trans('MinistryMember.mp_id'),
                'entity'=>'ec_mp',
                'model'=>'App\Models\EcMp',
                'attribute'=>'name_lc'
            ],
            // [
            //     'name'=>'member_type_id',
            //     'type'=>'select',
            //     'label'=>trans('MinistryMember.member_type_id'),
            //     'entity'=>'mst_ministry_member_type',
            //     'model'=>'App\Models\MinistryMemberType',
            //     'attribute'=>'name_lc'
            // ],

            [
                'name'=>'date_from_bs',
                'label'=>trans('MinistryMember.date_from')
            ],
            [
                'name'=>'date_to_bs',
                'label'=>trans('MinistryMember.date_to')
            ],
            // [
            //     'name' => 'email',
            //     'label' => trans('common.email'),
            //     'type' => 'email',
            // ],
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),
            $this->addRemarksColumn(),

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
        CRUD::setValidation(MinistryMemberRequest::class);
        if($this->crud->getActionMethod() == 'edit'){
            $mp_id  = [
                'name'=>'mp_id',
                'type'=>'select2',
                'label'=>trans('MinistryMember.mp_id'),
                'entity'=>'ec_mp',
                'model'=>'App\Models\EcMp',
                'attribute'=>'name_lc',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'id'=>'mp-id',
                    'disabled' =>'disabled'
                    // 'disabled'=>true
                ],
            ];



        }else{

            $mp_id = [
                'name'=>'mp_id',
                'type'=>'select2',
                'label'=>trans('MinistryMember.mp_id'),
                'entity'=>'ec_mp',
                'model'=>'App\Models\EcMp',
                'attribute'=>'name_lc',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'options' => (function($query){
                    $userMpIds = User::whereNotNull('mp_id')->pluck('mp_id')->toArray();
                    $mp_ids = EcMp::pluck('id')->toArray();
                    $filteredMpIds = array_diff($mp_ids, $userMpIds);
                    return $query->whereIn('id',$filteredMpIds)->get();
                }),
                'attributes'=>[
                    'id'=>'mp-id',
                    // 'disabled'=>true
                ],
            ];

        }
        

        

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
            $mp_id,
            
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
            // [
            //     'name' => 'email',
            //     'label' => trans('common.email'),
            //     'type' => 'email',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-4',
            //     ],
            // ],

            [
                'name' => 'legend4',
                'type' => 'custom_html',
                'value' => '<br>',
            ],
            [ //Toggle
                'name' => 'allow_user_login',
                'label' => trans('<b>सभासद लाइ सिस्टममा लग इन दिने हो</b>'),
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
                'default' => 3,
                'options'   => (function ($query) {
                    return $query->where('id','=', 3)->get();
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
            $this->addRemarksField(),
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


            $already_exists_user = User::where('mp_id',$item->id)->first();
            $ec_mp = EcMp::where('id',$request->mp_id)->first();

            if($request->allow_user_login == "0" && $already_exists_user != null){
                $user = User::where('mp_id', $item->id)->update(['is_active' => false]);

                DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('model_id', $user->id)
                ->delete();
            }elseif($request->allow_user_login == "1" && $ec_mp != null && $already_exists_user == null){



                $user = User::create(
                    [
                        'mp_id' => $request->mp_id,
                        'ministry_id' => $this->parent('ministry_id'),
                        'is_ministry_member' => true,
                        'name' => $ec_mp->name_lc,
                        'email' => $ec_mp->email,
                        'password' => Hash::make('1'),
                    ]);

                // Get the ID of the new role from the request
                $newRoleID = $request->role_id;
                if($newRoleID == null){
                    dd('भूमिका छान्नुहोस्');
                }

                // Add the new role
                DB::table('model_has_roles')->insert([
                    'role_id' => $newRoleID,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id,
                ]);
            }
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
            // $item = $this->crud->update($request['id'],$request);

            $item = $this->crud->update(
                $request->get($this->crud->model->getKeyName()),
                $this->crud->getStrippedSaveRequest($request)
            );
            $this->data['entry'] = $this->crud->entry = $item;

            $already_exists_user = User::where('mp_id',$item->id)->first();


            $ec_mp = EcMp::where('id',$request->mp_id)->first();

            if($request->allow_user_login == "0" && $already_exists_user != null){
                $user = User::where('mp_id', $item->id)->update(['is_active' => false]);

                DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('model_id', $user->id)
                ->delete();
            }elseif($request->allow_user_login == "1" && $ec_mp != null && $already_exists_user == null){

                $user = User::create(
                    [
                        'mp_id' => $request->mp_id,
                        'ministry_id' => $this->parent('ministry_id'),
                        'name' => $ec_mp->name_lc,
                        'is_ministry_member' => true,
                        'email' => $ec_mp->email,
                        'password' => Hash::make('1'),
                    ]);

                // Get the ID of the new role from the request
                $newRoleID = $request->role_id;
                
                if($newRoleID == null){
                    dd('भूमिका छान्नुहोस्');
                }

                // Add the new role
                DB::table('model_has_roles')->insert([
                    'role_id' => $newRoleID,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id,
                ]);
            }elseif($already_exists_user != null){

                dd($already_exists_user);

                $request->validate([
                    'mp_id' => 'required|unique:users,mp_id',
                ]);
                $user = User::where('mp_id',$item->id)->update(
                    [
                        'is_ministry_member' => true,
                        'ministry_id' => $this->parent('ministry_id'),
                        'name' => $ec_mp->name_lc,
                        'email' => $ec_mp->email,
                        'phone_no' => $ec_mp->mobile_number,

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
