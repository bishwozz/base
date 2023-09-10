<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Prologue\Alerts\Facades\Alert;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
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
                'label' => "Category of",
                'type' => 'select',
                'name' => 'menu_id', // the db column for the foreign key
                'entity' => 'menu', // the method that defines the relationship in your Model
                'attribute' => 'title', // foreign key attribute that is shown to user
                'model' => 'App\Models\Menu',
                'default' => 1,
            ],
            [
                'label' => "Category",
                'type' => 'text',
                'name' => 'name',
            ]
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
        $this->crud->setValidation(CategoryRequest::class);
        
        $arr = [
            [
                'label' => "Add Category To",
                'type' => 'select_from_array',
                'name' => 'menu_id',
                'options' => Category::$menus,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 type_id',
                ],
                'attributes'=>[
                    'id'=>'type_id'
                ]

            ],
            [
                'label' => trans('common.title'),
                'type' => 'text',
                'name' => 'name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]

            ]
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
        $user_id = backpack_user()->id;
        $request->request->set('name', strtolower($request->request->get('name')));
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
