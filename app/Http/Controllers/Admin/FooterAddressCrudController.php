<?php

namespace App\Http\Controllers\Admin;

use App\Base\Traits\ParentData;
use App\Http\Requests\FooterAddressRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FooterAddressCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FooterAddressCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    use ParentData;
    public function setup()
    {
        CRUD::setModel(\App\Models\FooterAddress::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/footer-address');
        CRUD::setEntityNameStrings('Footer', 'Footer');
        $this->setUpLinks(['edit']);
    }

    public function tabLinks()
    {
        return $this->setFooterTabs();
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
        CRUD::setValidation(FooterAddressRequest::class);
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
