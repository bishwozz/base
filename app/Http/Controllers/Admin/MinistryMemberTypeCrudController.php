<?php

namespace App\Http\Controllers\Admin;
use App\Models\Ministry;
use App\Base\BaseCrudController;

use App\Models\MinistryMemberType;
use App\Models\CoreMaster\MstFiscalYear;
use App\Http\Requests\MinistryMemberTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryMemberTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryMemberTypeCrudController extends BaseCrudController
{


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryMemberType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry-member-type');
        CRUD::setEntityNameStrings(trans('menu.ministryMemberType'), trans('menu.ministryMemberType'));
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
        $this->addFilters();
        $columns = [
			$this->addRowNumberColumn(),
            $this->addNameLcColumn(),
            $this->addNameEnColumn(),
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),
            $this->addRemarksColumn(),

		];
        $this->crud->addColumns(array_filter($columns));
    }

   

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MinistryMemberTypeRequest::class);

        $fields = [
			
            $this->addNameLcField(),
            $this->addNameEnField(),
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
            $this->addRemarksField(),
		];
        $this->crud->addFields(array_filter($fields));
        
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
