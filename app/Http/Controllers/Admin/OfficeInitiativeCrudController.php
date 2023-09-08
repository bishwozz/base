<?php

namespace App\Http\Controllers\Admin;

use App\Base\BaseCrudController;
use App\Models\OfficeInitiative;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Http\Requests\OfficeInitiativeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OfficeInitiativeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfficeInitiativeCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\OfficeInitiative::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office-initiative');
        CRUD::setEntityNameStrings(trans('actLaw.office_initiative'), trans('actLaw.office_initiatives'));
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

        // fiscal year filter
        $this->crud->addFilter([
            'name'=>'fiscal_year_id',
            'label'=> 'आर्थिक वर्ष',
            'type'=>'select2'
        ], function() {
            return MstFiscalYear::orderBy('id', 'DESC')->pluck('code', 'id')->toArray();
        }, function($value) { 
            $this->crud->addClause('where', 'fiscal_year_id', $value);
        });

        

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
        }        // ministry filter

        
    }
    protected function setupListOperation()
    {
        $cols=[
            $this->addRowNumberColumn(),
            $this->addMinistryColumn(),

            [
                'label' => trans('actLaw.fiscal_year'),
                'type' => 'select',
                'name' => 'fiscal_year_id',
                'entity' => 'fiscal_year',
                'attribute' => 'code',
                'model' => "App\Models\CoreMaster\MstFiscalYear",
            ],
             [
                'name' => 'innovatives',
                'type' => 'text',
                'label' => trans('actLaw.innovatives'),
            ],
            [
                'name' => 'achievements',
                'type' => 'text',
                'label' => trans('actLaw.achievements'),
            ],
            [
                'name' => 'challenges',
                'type' => 'text',
                'label' => trans('actLaw.challenges'),
            ],
            [
                'name' => 'initiatives',
                'type' => 'text',
                'label' => trans('actLaw.initiatives'),
            ],
            [
                'name' => 'expectations',
                'type' => 'text',
                'label' => trans('actLaw.expectations'),
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
        CRUD::setValidation(OfficeInitiativeRequest::class);

        $arr = [
            $this->addMinistryField(),

            [
                'label' => trans('actLaw.fiscal_year'),
                'type' => 'select',
                'name' => 'fiscal_year_id',
                'entity' => 'fiscal_year',
                'attribute' => 'code',
                'model' => "App\Models\CoreMaster\MstFiscalYear",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'options'   => (function ($query) {
                    return $query->orderBy('id', 'DESC')->get();
                }),
                'default' => backpack_user()->ministry?backpack_user()->ministry->appSetting->fiscal_year_id:null
            ],
            [
                'name' => 'innovatives',
                'type' => 'summernote',
                'label' => trans('actLaw.innovatives'),
            ],
            [
                'name' => 'achievements',
                'type' => 'summernote',
                'label' => trans('actLaw.achievements'),
            ],
            [
                'name' => 'challenges',
                'type' => 'summernote',
                'label' => trans('actLaw.challenges'),
            ],
            [
                'name' => 'initiatives',
                'type' => 'summernote',
                'label' => trans('actLaw.initiatives'),
            ],
            [
                'name' => 'expectations',
                'type' => 'summernote',
                'label' => trans('actLaw.expectations'),
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
