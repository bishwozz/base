<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Http\Requests\CoreMaster\MstFedLocalLevelRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFedLocalLevelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFedLocalLevelCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {

        CRUD::setModel(\App\Models\CoreMaster\MstFedLocalLevel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fed-local-level');
        CRUD::setEntityNameStrings(trans('menu.localLevel'), trans('menu.localLevel'));
        // $this->crud->denyAccess(['create','update','delete']);
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
        $cols=[
            $this->addRowNumberColumn(),
            $this->addDistrictColumn(),
            $this->addLocalLevelColumn(),
            $this->addNameEnColumn(),
            $this->addNameLcColumn(),
            
            $this->addIsActiveColumn(),

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
        CRUD::setValidation(MstFedLocalLevelRequest::class);

        $arr = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addDistrictField(),
            $this->addLocalLevelTypeField(),
            $this->addNameLcField(),
            $this->addNameEnField(),
            $this->addIsActiveField()
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
