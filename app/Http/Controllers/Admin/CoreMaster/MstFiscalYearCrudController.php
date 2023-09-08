<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Http\Requests\CoreMaster\MstFiscalYearRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFiscalYearCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFiscalYearCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\MstFiscalYear::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fiscal-year');
        CRUD::setEntityNameStrings(trans('menu.fiscalYear'), trans('menu.fiscalYear'));
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumberColumn(),
            [
                'name' => 'code',
                'type' => 'text',
                'label' => trans('common.code'),
            ],
            [
                'name'=>'from_date_bs',
                'label'=> trans('common.date_from_bs'),
            ],

            [
                'name'=>'to_date_bs',
                'label'=> trans('common.date_to_bs'),
            ],

            ];
            $this->crud->addColumns($col);

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
        CRUD::setValidation(MstFiscalYearRequest::class);

        $arr = [
            [
                'name' => 'code',
                'label' => trans('common.code'),
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'placeholder' => '20_ _/_ _'
                ]
            ],
           [
            'type' => 'plain_html',
            'name'=>'plain_html_1',
            'value' => '<div class="form-group col-md-6"></div>',
        ],
        [
            'name' => 'from_date_bs',
            'type' => 'nepali_date',
            'label' => trans('common.date_from_bs'),
             'attributes'=>
              [
                'id'=>'from_date_bs',
                'relatedId' =>'from_date_ad',
                'maxlength' =>'10',
             ],
             'wrapperAttributes' => [
                 'class' => 'form-group col-md-3',
             ],
        ],

        [
            'name' => 'from_date_ad',
            'type' => 'date',
            'label' => trans('common.date_from_ad'),
            'attributes'=>
            [
            'id'=>'from_date_ad',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ],
        ],
        [
            'name' => 'to_date_bs',
            'type' => 'nepali_date',
            'label' => trans('common.date_to_bs'),
            'attributes'=>
            [
                'id'=>'to_date_bs',
                'relatedId' => 'to_date_ad',
                'maxlength' =>'10',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ],
        ],
        [
            'name' => 'to_date_ad',
            'type' => 'date',
            'label' => trans('common.date_to_ad'),
            'attributes'=>[
                'id'=>'to_date_ad'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ],
        ],

    ];

    $this->crud->addFields($arr);
       
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
