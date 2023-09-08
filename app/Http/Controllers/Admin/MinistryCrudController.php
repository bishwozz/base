<?php

namespace App\Http\Controllers\Admin;
use App\Models\Ministry;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Http\Requests\MinistryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryCrudController extends BaseCrudController
{
    use ParentData;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Ministry::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry');
        CRUD::setEntityNameStrings(trans('menu.ministries'), trans('menu.ministries'));
        $this->setUpLinks(['edit']);
        $this->checkPermission();
    }
    public function tabLinks(){
     return  $this->setMinistryTabs();
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
			$this->addCodeColumn(),
            $this->addNameLcColumn(),
            $this->addNameEnColumn(),
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),
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
        CRUD::setValidation(MinistryRequest::class);
       
        
        $fields = [
			
            $this->addCodeField(),
            $this->addNameLcField(),
            $this->addNameEnField(),
            [
                'name' => 'email',
                'type' => 'text',
                'label' => trans('mp.email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'required' => 'Required',
                 ],
            ],
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
