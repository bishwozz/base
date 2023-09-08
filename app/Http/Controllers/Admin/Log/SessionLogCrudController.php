<?php

namespace App\Http\Controllers\Admin\Log;

use App\Base\BaseCrudController;
use App\Base\Traits\ActivityLogTraits;
use App\Http\Requests\Log\SessionLogRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SessionLogCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\Log\SessionLog::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/session-log');
        CRUD::setEntityNameStrings(trans('menu.session_log'), trans('menu.session_log'));

        $this->crud->addButtonFromModelFunction('line', 'activityLog', 'activityLog', 'beginning');

        $this->crud->denyAccess('update');
        $this->crud->denyAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');
    }

    protected function setupListOperation()
    {
        $cols = [
            // [
            //     'name' => 'row_number',
            //     'type' => 'row_number',
            //     'label' => trans('S.N.'),
            //     'orderable' => true,
                
            // ],
            $this->addRowNumberColumn(),
            [
                'label' => trans('User Name'),
                'type' => 'text',
                'name' => 'username', // the db column for the foreign key
                
            ],
            [
                'label' => trans('User Email'),
                'name' => 'user_email', // the db column for the foreign key
                
            ],
            [
                'label' => trans('Login Date'),
                'type' => 'text',
                'name' => 'login_date', // the db column for the foreign key
                
            ],
            [
                'label' => trans('Login time'),
                'type' => 'text',
                'name' => 'login_time', // the db column for the foreign key
                
            ],
            [
                'label' => trans('Currently <br> logged In?'),
                'type' => 'check',
                'name' => 'is_currently_logged_in',
                'options' => [
                    false => 'Yes',
                    true => 'No',
                ]
                
            ],
            [
                'label' => trans('Logout time'),
                'type' => 'text',
                'name' => 'logout_time', // the db column for the foreign key
                
            ],               
            [
                'label' => trans('IP'),
                'type' => 'text',
                'name' => 'user_ip', // the db column for the foreign key
                
            ],               
            [
                'label' => trans('Device'),
                'type' => 'text',
                'name' => 'device', // tdesktophe db column for the foreign key
                
            ],               
            [
                'label' => trans('Platform'),
                'type' => 'text',
                'name' => 'platform', // tdesktophe db column for the foreign key
                
            ],               
            [
                'label' => trans('Browser'),
                'type' => 'text',
                'name' => 'browser', // tdesktophe db column for the foreign key
                
            ],               
        ];
        $this->crud->addColumns(array_filter($cols));
        $this->crud->addClause('where', 'user_id', '!=', 1);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SessionLogRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
