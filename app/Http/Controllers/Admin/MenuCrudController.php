<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Http\Requests\MenuRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Menu::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/menu');
        CRUD::setEntityNameStrings('menu', 'menus');
        $this->data['script_js']= $this->getScriptJs();


    }
    public function getScriptJs(){
        return "
        $(document).ready(function(){
            if($('#type_id').val() == '0' || $('#type_id').val() == ''){
                $('.parent_id').val(null);
                $('.parent_id').hide();
            }
            $('.type_id').change(function() {
                $('.parent_id').val(null);
                $('.parent_id').hide();
                if($('#type_id').val() == '0'){
                }else{
                    $('.parent_id').show();
                }
            });
        });
        ";
    }
    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $cols = [
            $this->addRowNumber(),
            [
                'name'=>'display_order',
                'type'=>'number',
                'label' => trans('common.display_order'),
            ],
            [
                'label' => trans('common.title'),
                'type' => 'text',
                'name' => 'title',
            ],
            [
                'label' => trans('common.link'),
                'type' => 'text',
                'name' => 'link',
            ],
        ];
        $this->crud->addColumns($cols);  
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MenuRequest::class);
        
        $arr = [
            [
                'label' => trans('common.display_order').' (optional)',
                'type' => 'number',
                'name' => 'display_order',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ]
            ],
            [
                'label' => trans('common.type'),
                'type' => 'select_from_array',
                'name' => 'type_id',
                'options' => Menu::$menu_type,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 type_id',
                ],
                'attributes'=>[
                    'id'=>'type_id'
                ]

            ],
            [
                'name' => 'parent_id',
                'type' => "select2_from_ajax",
                'label' =>trans('common.parent_menu'),//just a label
                'model' =>  Menu::class,
                'entity' => 'parentMenu',//relatioship which is inside the model
                'attribute' => "title",//the field which is needed
                'data_source' => url("api/parent_menu/type_id"),//api/modelsmallname/tableid from which state is taken
                'placeholder' => 'First select menu type',
                'minimum_input_length' => 0,
                'dependencies' => ["type_id"],//id from which state is pulled
                'include_all_form_fields' => true,
                'wrapperAttributes' => [
                'class' => 'form-group col-md-3 parent_id',
                ],
                'attributes'=>[
                    'id'=>'parent_id'
                ],
            ],
            [
                'label' => trans('common.title'),
                'type' => 'text',
                'name' => 'title',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]

            ],
            [
                'label' => trans('common.link'),
                'type' => 'text',
                'name' => 'link',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                    'readonly' => true
                ]
            ],
        ];
        $this->crud->addFields(array_filter($arr));
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
        $user_id = backpack_user()->id;
        if(!isset($request->display_order)){
            if(isset($request->parent_id)){
                $max_order=$this->crud->model->where('parent_id',$request->parent_id)->max('display_order');
            }else{
                $max_order=$this->crud->model->where('parent_id',null)->max('display_order');
            }
            $request->request->set('display_order', $max_order+1);
        }
        $request->request->set('link','pages'.'/'.strtolower($request->title));

        $request->request->set('created_by', $user_id);
        $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
