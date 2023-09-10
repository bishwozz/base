<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HeaderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HeaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HeaderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Header::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/header');
        CRUD::setEntityNameStrings('Header', 'Header');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title');
        CRUD::column('subtitle');
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
        CRUD::setValidation(HeaderRequest::class);
        $arr = [
            [
                'label' => trans('Title'),
                'type' => 'text',
                'name' => 'title',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('SubTitle'),
                'type' => 'text',
                'name' => 'subtitle',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'name' => 'logo',
                'type' => 'image',
                'label' => 'Logo',
                'disk' => 'uploads', 
                'crop' => true, 
                'upload' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                
            ],
            // [
            //     'label' => trans('Full Address'),
            //     'type' => 'text',
            //     'name' => 'full_address',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-4',
            //     ]
            // ],
            // [
            //     'label' => trans('Phone'),
            //     'type' => 'text',
            //     'name' => 'phone',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-12',
            //     ]
            // ],
            // [
            //     'label' => trans('Email'),
            //     'type' => 'text',
            //     'name' => 'email',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-12',
            //     ]
            // ],
            // [
            //     'label' => trans('Fax'),
            //     'type' => 'text',
            //     'name' => 'fax',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-12',
            //     ]
            // ],
            // [
            //     'label' => trans('Description'),
            //     'type' => 'textarea',
            //     'name' => 'description',
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-12',
            //     ]
            // ],
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
}
