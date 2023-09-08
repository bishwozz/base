<?php

namespace App\Http\Controllers\Admin;

use App\Models\MinistryActLaw;
use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstMinistry;
use App\Http\Requests\MinistryActLawRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryActLawCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryActLawCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryActLaw::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry-act-law');
        CRUD::setEntityNameStrings(trans('actLaw.act_law'), trans('actLaw.act_laws'));
        $this->addFilters();
        $this->crud->enableExportButtons();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    public function addFilters()
    {


        if(backpack_user()->hasAnyRole('admin|superadmin'))
        {
            $this->crud->addFilter([
                'name'=>'ministry_id',
                'label'=> 'मन्त्रालयको नाम',
                'type'=>'select2'
            ], function() {
                return MstMinistry::all()->pluck('name_lc', 'id')->toArray();
            }, function($value) { 
                $this->crud->addClause('where', 'ministry_id', $value);
            });
            $this->crud->addFilter([
                'name'=>'name',
                'label'=> 'नाम',
                'type'=>'text'
            ],false, function($value) { 
                $this->crud->addClause('where', 'name', 'ILIKE',"%$value%");
            });
            $this->crud->addFilter([
                'name'=>'status',
                'label'=> trans('actLaw.status'),
                'type'=>'select2'
            ],function() {
                return MinistryActLaw::$status;
            }, function($value) { 
                $this->crud->addClause('where', 'status', $value);
            });
            $this->crud->addFilter([
                'name'=>'type',
                'label'=> trans('actLaw.type'),
                'type'=>'select2'
            ],function() {
                return MinistryActLaw::$type;
            }, function($value) {
                $value=trim($value);
                $this->crud->addClause('where', 'type', $value);
            });
        }        // ministry filter
        
    }
    protected function setupListOperation()
    {
        $cols=[
            $this->addRowNumberColumn(),
            $this->addMinistryColumn(),

            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('actLaw.name'),
            ],
            [
                'label' => trans('actLaw.type'),
                'type' => 'select_from_array',
                'name' => 'type', // the db column for the foreign key
                'options'     => MinistryActLaw::$type,
            ],
            [
                'label' => trans('actLaw.status'),
                'type' => 'select_from_array',
                'name' => 'status', // the db column for the foreign key
                'options'     => MinistryActLaw::$status,
            ],
        ];
        $this->crud->addColumns(array_filter($cols));
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MinistryActLawRequest::class);

        $arr = [
            $this->addMinistryField(),

            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('actLaw.name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-8',
                ],
            ],
            [
                'name'        => 'type',
                'label'       => trans('actLaw.type'),
                'type'        => 'select_from_array',
                'options'     => MinistryActLaw::$type,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name'        => 'status',
                'label'       => trans('actLaw.status'),
                'type'        => 'select_from_array',
                'options'     => MinistryActLaw::$status,
                'inline' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'file_upload',
                'type' => 'upload',
                'upload' => true,
                'disk' => 'uploads',
                'label' => trans('actLaw.file_upload'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'description',
                'type' => 'summernote',
                'label' => trans('actLaw.description'),
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
