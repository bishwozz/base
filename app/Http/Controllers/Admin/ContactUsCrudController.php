<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use App\Http\Requests\ContactUsRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContactUsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactUsCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\ContactUs::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact-us');
        CRUD::setEntityNameStrings('contact us', 'contact uses');
        $this->crud->denyAccess(['create','edit','delete']);
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
                'label' => "Full Name",
                'type' => 'text',
                'name' => 'full_name',
            ],
            [
                'label' => "Email",
                'type' => 'text',
                'name' => 'email',
            ],
            [
                'label' => "Phone",
                'type' => 'text',
                'name' => 'phone',
            ],
            [
                'label' => "Message",
                'type' => 'text',
                'name' => 'message',
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
        CRUD::setValidation(ContactUsRequest::class);

        

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
