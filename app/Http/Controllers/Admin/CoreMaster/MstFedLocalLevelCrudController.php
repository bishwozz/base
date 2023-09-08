<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstFedLocalLevelType;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Http\Requests\CoreMaster\MstFedLocalLevelRequest;
use App\Models\CoreMaster\MstFedDistrict;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFedLocalLevelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFedLocalLevelCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {

        CRUD::setModel(\App\Models\CoreMaster\MstFedLocalLevel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fed-local-level');
        CRUD::setEntityNameStrings(trans('menu.localLevel'), trans('menu.localLevel'));
        $this->setFilters();
    }

    public function setFilters()
    {
        return  $this->crud->addFilter(
            [ 
                'name'        => 'province_id',
                'type'        => 'select2',
                'label'       => trans('Province'),
                'placeholder' => '-select province--',
            ],
            function () {
                return (new MstFedProvince())->getFilterComboOptions();
            },
            function ($value) { // if the filter is active
                $district_ids = MstFedDistrict::whereProvinceId($value)->pluck('id')->toArray();
                $this->crud->addClause('whereIn', 'district_id',$district_ids);
            }
        );
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $cols=[
            $this->addRowNumberColumn(),
            $this->addDistrictColumn(),
            $this->addLocalLevelColumn(),
            $this->addNameEnColumn(),
            $this->addNameLcColumn(),
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
        CRUD::setValidation(MstFedLocalLevelRequest::class);

        $arr = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            [
                'name' => 'district_id',
                'type' => 'select2',
                'entity' => 'districtEntity',
                'attribute' => 'name_en',
                'model' => MstFedDistrict::class,
                'label' => 'District',
                'options'   => (function ($query) {
                    return (new MstFedDistrict())->getFieldComboOptions($query);
                }),
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'level_type_id',
                'type' => 'select2',
                'entity' => 'levelTypeEntity',
                'attribute' => 'name_en',
                'model' => MstFedLocalLevelType::class,
                'label' => 'Local Level',
                'options'   => (function ($query) {
                    return (new MstFedLocalLevelType())->getFieldComboOptions($query);
                }),
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            
            [
                'name' => 'name_lc',
                'label' => trans('common.name_lc'),
                'type' => 'text',
                'attributes' => [
                    'id' => 'name-lc',
                    'required' => 'required',
                    'max-length' => 200,
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            
            [
                'name' => 'name_en',
                'label' => trans('common.name_en'),
                'type' => 'text',
                'attributes' => [
                    'id' => 'name-en',
                    'required' => 'required',
                    'max-length' => 200,
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            
            [
                'label' => 'Mayor Name',
                'name' => 'mayor_name',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'label' => 'Deputy Mayor Name',
                'name' => 'deputy_mayor_name',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            
            [
                'label' => 'Ward Count',
                'name' => 'ward_count',
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ],
            [
                'label' => 'Total Area',
                'name' => 'total_area',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'suffix'     => 'KM<sup>2</sup>',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ],
            [
                'label' => 'Total Population',
                'name' => 'total_population',
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ],
            
            [
                'label' => 'Area Code',
                'name' => 'area_code',
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'label' => 'Postal Code',
                'name' => 'postal_code',
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            
            
            //Map Path
            [  
                'label'     => 'Map Path',
                'name'      => 'map_path',
                'type'      => 'image',
                'crop' => true, 
                'disk' => 'uploads',
                'aspect_ratio' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label' => 'Website',
                'name' => 'web_link',
                'type' => 'url',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
            ],
            
        ];
        $this->crud->addFields($arr);
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
