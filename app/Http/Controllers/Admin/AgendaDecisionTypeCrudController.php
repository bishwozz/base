<?php

namespace App\Http\Controllers\Admin;

use App\Base\BaseCrudController;
use App\Models\AgendaDecisionType;
use App\Http\Requests\AgendaDecisionTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AgendaDecisionTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AgendaDecisionTypeCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AgendaDecisionType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/agenda-decision-type');
        CRUD::setEntityNameStrings(trans('common.agenda_decision_type'), trans('common.agenda_decision_type'));
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
                'name' => 'agenda_decision_code',
                'label' => trans('common.agenda_decision_code'),
                'type' => 'text',
            ],
            [
                'name' => 'agenda_decision_content',
                'label' => trans('common.agenda_decision_content'),
                'type' => 'text',
            ],
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
        CRUD::setValidation(AgendaDecisionTypeRequest::class);

        $fields = [
			
            [
                'name' => 'agenda_decision_code',
                'label' => trans('common.agenda_decision_code'),
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'agenda_decision_content',
                'label' => trans('common.agenda_decision_content'),
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
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
