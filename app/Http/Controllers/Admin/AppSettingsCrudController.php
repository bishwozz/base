<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompanyDetailRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class AppSettingsCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(\App\Models\AppSettings::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/app-setting');
        CRUD::setEntityNameStrings('company detail', 'company details');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $arr = [
            $this->addRowNumber(),
            [
                'label' => 'Name',
                'name' => 'name',
                'type' => 'text',

            ],
            [
                'name' => 'logo',
                'type' => 'image',
                'label' => 'Logo',
                'disk' => 'uploads', 
                'upload' => true,
            ],
            
            
            [
                'label' => 'Phone Number',
                'name' => 'phone',
                'type' => 'text',

            ],

            
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',

            ],
            
        ];
        $this->crud->addColumns($arr);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        // CRUD::setValidation(CompanyDetailRequest::class);



        $arr = [
            [
                'label' => 'Name',
                'name' => 'name',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'logo',
                'type' => 'image',
                'label' => 'Logo',
                'disk' => 'uploads', 
                'upload' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                
            ],
           
            [
                'label' => 'Phone Number',
                'name' => 'phone',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
           
            
            [
                'label' => 'background_color',
                'name' => 'background_color',
                'type' => 'color',

            ],
            
        ];
        $this->crud->addFields($arr);

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
}