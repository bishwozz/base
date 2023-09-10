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
                'label' => 'Address',
                'name' => 'address',
                'type' => 'text',

            ],
            [
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',

            ],
            [
                'label' => 'Phone Number',
                'name' => 'phone',
                'type' => 'text',

            ],
            [
                'label' => 'Fax',
                'name' => 'fax',
                'type' => 'text',

            ],
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',

            ],
            [
                'label' => 'Opening Hour',
                'name' => 'opening_hours',
                'type' => 'time',

            ],
            [
                'label' => 'Total Clients',
                'name' => 'total_clients',
                'type' => 'number',

            ],
            [
                'label' => 'Total Products',
                'name' => 'total_products',
                'type' => 'number',

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
                'label' => 'Address',
                'name' => 'address',
                'type' => 'text',
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
                'label' => 'Fax',
                'name' => 'fax',
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
                'label' => 'Opening Hour',
                'name' => 'opening_hours',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label' => 'Total Clients',
                'name' => 'total_clients',
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label' => 'Total Products',
                'name' => 'total_products',
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label' => 'Description',
                'name' => 'description',
                'type' => 'ckeditor',

            ],
            [
                'label' => 'display_order',
                'name' => 'display_order',
                'type' => 'number',

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