<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutUs;
use App\Http\Requests\AboutUsRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AboutUsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AboutUsCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\AboutUs::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/about-us');
        CRUD::setEntityNameStrings('About Us', 'About Us');
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
                'name' => 'title',
                'type' => 'text',
                'label' => "Title",
            ],
            [
                'name' => 'file_upload',
                'type' => 'image',
                'label' => "Image",
                'disk'=>'uploads',
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
        $this->crud->setValidation(AboutUsRequest::class);
        $arr = [
            [
                'name' => 'title',
                'type' => 'text',
                'label' => 'Title',
            ],
            [
                'name' => 'details',
                'type' => 'ckeditor',
                'label' => 'Details',
            ],
            [
                'name' => 'file_upload',
                'type' => 'image',
                'label' => 'Image',
                'disk' => 'uploads', 
                'upload' => true,                
                'crop' => true, 
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                
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
