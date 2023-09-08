<?php

namespace App\Http\Controllers\Admin;

use App\Models\OfficeDetail;
use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Http\Requests\OfficeDetailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OfficeDetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfficeDetailCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\OfficeDetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office-detail');
        CRUD::setEntityNameStrings(trans('actLaw.office_detail'), trans('actLaw.office_details'));
        $this->addFilters();
        $this->crud->enableExportButtons();
        $this->crud->enableResponsiveTable();

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
                'name' => 'grievance_farchyat',
                'type' => 'text',
                'label' => trans('actLaw.grievance_farchyat'),
            ],
            [
                'name' => 'pending_response_count',
                'type' => 'text',
                'label' => trans('actLaw.pending_response_count'),
            ],
            [
                'name' => 'internal_control_system',
                'type' => 'select_from_array',
                'label' => trans('actLaw.internal_control_system'),
                'options' => OfficeDetail::$internal_control_system
            ],
            [
                'name' => 'public_procurement',
                'type' => 'select_from_array',
                'label' => trans('actLaw.public_procurement'),
                'options' => OfficeDetail::$public_procurement
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
        CRUD::setValidation(OfficeDetailRequest::class);
        
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
                'name' => 'grievance_farchyat',
                'type' => 'text',
                'label' => trans('actLaw.grievance_farchyat'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
           
            [
                'name'        => 'internal_control_system',
                'label'       => trans('actLaw.internal_control_system'),
                'type'        => 'select_from_array',
                'default' => 1,
                'options'     => OfficeDetail::$internal_control_system,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'        => 'ladies_friendly_toilet',
                'label'       => trans('actLaw.ladies_friendly_toilet'),
                'type'        => 'select_from_array',
                'default' => 1,
                'options'     => OfficeDetail::$toilet_status,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'        => 'disable_friendly_toilet',
                'label'       => trans('actLaw.disable_friendly_toilet'),
                'type'        => 'select_from_array',
                'default' => 1,
                'options'     => OfficeDetail::$toilet_status,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'pending_response_count',
                'type' => 'number',
                'label' => trans('actLaw.pending_response_count'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name'        => 'is_information_updated',
                'label'       => trans('actLaw.is_information_updated'),
                'type'        => 'radio',
                'inline' => true,
                'default' => 1,
                'options'     => OfficeDetail::$is_information_updated,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'legend0',
                'type' => 'custom_html',
                'value' => '<legend>&emsp;सवारी साधन संख्या: </legend>',
            ],
            [
                'name' => 'current_two_wheeler',
                'type' => 'number',
                'label' => trans('actLaw.current_two_wheeler'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'current_four_wheeler',
                'type' => 'number',
                'label' => trans('actLaw.current_four_wheeler'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'required_two_wheeler',
                'type' => 'number',
                'label' => trans('actLaw.required_two_wheeler'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'required_four_wheeler',
                'type' => 'number',
                'label' => trans('actLaw.required_four_wheeler'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'legend',
                'type' => 'custom_html',
                'value' => '<legend>&emsp;ठेक्का सम्बन्धी (चालु आ.व. मा) </legend>',
            ],
            [
                'name'        => 'public_procurement',
                'label'       => trans('actLaw.public_procurement'),
                'type'        => 'select_from_array',
                'default' => 1,
                'options'     => OfficeDetail::$public_procurement,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'online_procurement_contract',
                'type' => 'number',
                'label' => trans('actLaw.online_procurement_contract'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'total_operating_contract',
                'type' => 'number',
                'label' => trans('actLaw.total_operating_contract'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'        => 'inspection_monitoring_period',
                'label'       => trans('actLaw.inspection_monitoring_period'),
                'type'        => 'select_from_array',
                'default' => 1,
                'options'     => OfficeDetail::$inspection_monitoring_period,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'inspection_count',
                'type' => 'number',
                'label' => trans('actLaw.inspection_count'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
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
