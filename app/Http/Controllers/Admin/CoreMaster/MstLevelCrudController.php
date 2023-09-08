<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Http\Requests\MstLevelRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MstLevelCrudController extends BaseCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        CRUD::setModel(\App\Models\MstLevel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-level');
        CRUD::setEntityNameStrings('श्रेणी', 'श्रेणीहरू');
    }


    protected function setupListOperation()
    {
        $columns = [
            $this->addRowNumber(),
            [
                'name' => 'name_lc',
                'type' => 'text',
                'label' => 'नाम',
            ],
            [
                'name' => 'name_en',
                'type' => 'text',
                'label' => 'Name',
            ],
            [
                'name' => 'display_order',
                'label' => 'वर्णानुक्रमं',
                'type' => 'text',
            ],
            [
                'name' => 'is_active',
                'type' => 'check',
                'label' => 'सक्रिय हो ?',
            ],
		];
        $this->crud->addColumns(array_filter($columns));
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstLevelRequest::class);
        $this->addMstLevelFields();
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function addMstLevelFields(){
        $arr = [
            [
                'name' => 'name_lc',
                'type' => 'text',
                'label' => 'नाम',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                'required' => 'required',
                'maxlength'=>'100',
                ],
            ],
            [
                'name' => 'name_en',
                'type' => 'text',
                'label' => 'Name (अंग्रेजीमा)',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                'maxlength'=>'100',
                ],
            ],

            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'विवरण',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
                'attributes' => [
                    'maxlength' => '300',
                    'class' => 'form-control fixed-textarea',
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
                'name'   => 'is_active',
                'label'  => 'सक्रिय हो ?',
                'type'   => 'radio',
                'options' => [
                    0 => "होइन &nbsp;",
                    1 => "हो"
                ],
                'inline' => true, // show the radios all on the same line?
                'wrapper' => [
                    'class' => 'form-group4 form-group col-md-8 mb-4',
                ],
            ],


        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }
}
