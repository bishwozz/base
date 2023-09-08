<?php

namespace App\Http\Controllers\Admin\Log;

use App\Models\Log\SessionLog;
use App\Base\BaseCrudController;
use App\Http\Requests\Log\ActivityLogRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ActivityLogCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(\App\Models\Log\ActivityLog::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/activity-log');
        CRUD::setEntityNameStrings('Activity Log', 'Activity Log');
        $this->enableDialog(true);
        $this->crud->denySave = true;


        // $this->crud->denyAccess('update');
        $this->crud->denyAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');

        if (request()->has('session_id')) {
            $this->crud->addClause('where','session_id', request()->session_id);
        }

        $this->crud->back_url ='session-log';
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('update');

        $cols = [
            $this->addRowNumberColumn(),
            [
                'name'=>'session',
                'type'=>'session_activity_dialog',
                'label'=>'Session ID'
            ],
            // [
            //     'name' => 'session',
            //     'type' => 'select',
            //     'entity' => 'session',
            //     'attribute' => 'id',
            //     'model' => SessionLog::class,
            //     'label' => 'Session ID',
            // ],
            [
                'name' => 'activity_name',
                'label' => 'Controller Name',
                'function_name'=>'controller_name',
                'type'=>'model_function',
            ],
            [
                'name' => 'activity_type',
                'label' => 'Activity Type',
            ],
            [
                'name' => 'activity_time',
                'label' => 'Activity Time',
            ],
            [
                'name' => 'activity_date_bs',
                'label' => 'Activity Date B.S.',
            ],
            [
                'name' => 'description',
                'label' => 'Description',
            ],
            [
                'name' => 'url',
                'label' => 'URL',
            ],
            [
                'name' => 'request_method',
                'label' => 'Request Method',
            ],
            [
                'name' => 'url_query_string',
                'label' => 'URL Query String',
            ],
            [
                'name' => 'url_response',
                'label' => 'URL Response',
            ],
            [
                'name' => 'status',
                'label' => 'Status',
            ],
        ];
        $cols = array_filter($cols);
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
        CRUD::setValidation(ActivityLogRequest::class);

        

        $arr=[
             [
                'name' => 'session',
                'type' => 'text',
                'label' => 'Session ID',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-4',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'activity_name',
                'label' => 'Controller Name',
                'type'=>'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-8',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'activity_type',
                'label' => 'Activity Type',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-4',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'activity_date_bs',
                'label' => 'Activity Date B.S.',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-4',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'activity_time',
                'label' => 'Activity Time',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-4',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
         
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'description_area',
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'url',
                'label' => 'URL',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-8',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'request_method',
                'label' => 'Request Method',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-4',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'url_query_string',
                'label' => 'URL Query String',
                'type' => 'text',
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'url_response',
                'label' => 'URL Response',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-8',
                ],
                'attributes' => [
                    'readonly' => true,
                ]
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'text',
                'wrapperAttributes'=>[
                    "class"=>'form-group col-md-4',
                ],
                'attributes' => [
                    'readonly' => true,
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
