<?php

namespace App\Http\Controllers\Admin;

use App\Base\Traits\ParentData;
use App\Http\Requests\CollegeDetailsRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CollegeDetailsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CollegeDetailsCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    use ParentData;
    public function setup()
    {
        CRUD::setModel(\App\Models\CollegeDetails::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/college-details');
        CRUD::setEntityNameStrings('College Detail', 'College Detail');
        $this->setUpLinks(['edit']);
    }

    public function tabLinks()
    {
        return $this->setCollegeDetailsTabs();
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
        CRUD::column('full_address');
        CRUD::column('phone');
        CRUD::column('fax');
        CRUD::column('email');

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
        CRUD::setValidation(CollegeDetailsRequest::class);
        $arr = [
            [
                'label' => 'College Name',
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
            [
                'label' => "Google map embed link",
                'type' => 'text',
                'name' => 'gps',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('Full Address'),
                'type' => 'text',
                'name' => 'full_address',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('Phone'),
                'type' => 'text',
                'name' => 'phone',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('Email'),
                'type' => 'text',
                'name' => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('Fax'),
                'type' => 'text',
                'name' => 'fax',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('Description'),
                'type' => 'textarea',
                'name' => 'description',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12',
                ]
            ],
            [   // Upload
                'name'      => 'enrolment_form',
                'label'     => 'Student Enrolment Form',
                'type'      => 'upload',
                'upload'    => true,
                'disk'      => 'uploads', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
                // optional:
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                // 'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
            ]
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
}
