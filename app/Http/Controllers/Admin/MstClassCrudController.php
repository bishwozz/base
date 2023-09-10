<?php

namespace App\Http\Controllers\Admin;

use App\Models\MstClass;
use App\Http\Requests\MstClassRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstClassCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstClassCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\MstClass::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-class');
        CRUD::setEntityNameStrings('mst class', 'mst classes');
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
                'name'=>'display_order',
                'type'=>'number',
                'label' => trans('common.display_order'),
            ],
            [
                'label' => 'Name',
                'type' => 'text',
                'name' => 'name',
            ],
            [
                'label' => 'Section',
                'type' => 'text',
                'name' => 'section',
            ],
            [
                'label' => 'Capacity',
                'type' => 'text',
                'name' => 'capacity',
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
        $this->crud->setValidation(MstClassRequest::class);
        $arr = [
            [
                'label' => 'Name',
                'type' => 'text',
                'name' => 'name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [
                'label' => 'Section',
                'type' => 'text',
                'name' => 'section',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [
                'name' => 'capacity',
                'type' => 'text',
                'label' => 'capacity',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'description',
                'type' => 'ckeditor',
                'label' => 'Description',
            ],
            [
                'label' => trans('common.display_order').' (optional)',
                'type' => 'number',
                'name' => 'display_order',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
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
