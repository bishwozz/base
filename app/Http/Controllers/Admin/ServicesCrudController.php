<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServicesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ServicesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ServicesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Services::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/services');
        CRUD::setEntityNameStrings('services', 'services');
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
                'label' => trans('common.title'),
                'type' => 'text',
                'name' => 'title',
            ],
            [
                'name' => 'service_img',
                'type' => 'image',
                'label' => "Image",
                'disk'=>'uploads',
            ]
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
        // $this->crud->setValidation(SliderRequest::class);
        $arr = [
            [
                'label' => trans('common.title'),
                'type' => 'text',
                'name' => 'title',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]

            ],
            [
                'name' => 'service_img',
                'type' => 'image',
                'label' => 'Image',
                'disk' => 'uploads', 
                'crop' => true, 
                'upload' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                
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
