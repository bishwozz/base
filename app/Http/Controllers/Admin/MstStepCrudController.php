<?php

namespace App\Http\Controllers\Admin;

use App\Models\MstStep;
use App\Base\BaseCrudController;
use App\Http\Requests\MstStepRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstStepCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstStepCrudController extends BaseCrudController
{
    

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MstStep::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-step');
        CRUD::setEntityNameStrings(trans('menu.steps'), trans('menu.steps'));
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
			$this->addRowNumberColumn(),
            $this->addNameLcColumn(),
            $this->addNameEnColumn(),
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
        CRUD::setValidation(MstStepRequest::class);
        $fields = [
			
            $this->addNameLcField(),
            $this->addNameEnField(),
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
            $this->addRemarksField(),

			
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
