<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstGroups;
use App\Http\Requests\CoreMaster\MstGroupsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MstGroupsCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(MstGroups::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-groups');
        CRUD::setEntityNameStrings('समूह', 'समूहहरू');
    }

    protected function setupListOperation()
    {
        $cols =  [
            $this->addRowNumberColumn(),
            $this->addNameLcColumn(),
            $this->addNameEnColumn(),
         ];

        $this->crud->addColumns($cols);
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstGroupsRequest::class);
        $this->addMstGroupsFields();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function addMstGroupsFields(){
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
