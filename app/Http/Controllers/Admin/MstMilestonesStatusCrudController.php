<?php

namespace App\Http\Controllers\Admin;

use App\Base\BaseCrudController;
use App\Http\Requests\MstMilestonesStatusRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MstMilestonesStatusCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(\App\Models\MstMilestonesStatus::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-milestones-status');
        CRUD::setEntityNameStrings('माइलस्टोन स्थिति', 'माइलस्टोन स्थितिहरू');
    }


    protected function setupListOperation()
    {
        $col = [
                $this->addRowNumberColumn(),
                [
                    'name' => 'name',
                    'label' => 'नाम',
                    'type' => 'text',
                ],
                [
                    'name' => 'display_order',
                    'label' => 'वर्णानुक्रमं',
                    'type' => 'text',
                ],
                [
                    'name' => 'status_colour',
                    'label' => 'स्थिति रंग',
                    'type' => 'color',
                ],
                [
                    'name' => 'progress_percent',
                    'label' => 'प्रगति प्रतिशत',
                    'type'=>'text',
                ],

            ];
        $this->crud->addColumns($col);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstMilestonesStatusRequest::class);
        $arr = [

            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'नाम',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'progress_percent',
                'type' => 'number',
                'label' => 'प्रगति प्रतिशत (%)',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                    'min' => '0', // Minimum allowed value
                    'max' => '100', // Maximum allowed value
                ],
            ],
            [
                'name' => 'display_order',
                'type' => 'number',
                'label' => 'वर्णानुक्रमं',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],

            [
                'name'    => 'status_colour',
                'label'   => 'स्थिति रंग',
                'type'    => 'color',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
