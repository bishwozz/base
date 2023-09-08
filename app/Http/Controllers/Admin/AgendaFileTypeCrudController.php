<?php

namespace App\Http\Controllers\Admin;

use App\Models\AgendaFileType;
use App\Base\BaseCrudController;
use App\Http\Requests\AgendaFileTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AgendaFileTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AgendaFileTypeCrudController extends BaseCrudController
{
   

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AgendaFileType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/agenda-file-type');
        CRUD::setEntityNameStrings(trans('common.file_type'), trans('common.file_type'));
        $this->checkPermission();

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
            [
                'name' => 'code',
                'label' => trans('common.agenda_decision_code'),
                'type' => 'text',
            ],
            [
                'name' => 'name',
                'label' => 'फाईल प्रकार नाम',
                'type' => 'text',
            ],
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),

		];
        $this->crud->addColumns(array_filter($columns));
        

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
        CRUD::setValidation(AgendaFileTypeRequest::class);

        $fields = [
			
            [
                'name' => 'code',
                'label' => 'कोड',
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'name',
                'label' => 'फाईल प्रकार नाम',
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
		];
        $this->crud->addFields(array_filter($fields));

        

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
