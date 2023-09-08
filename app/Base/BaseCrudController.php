<?php

namespace App\Base;

use App\Base\Traits\ParentData;
use App\Base\Traits\CheckPermission;
use App\Base\Traits\UserLevelFilter;
use App\Base\Operations\ListOperation;
use App\Base\Operations\ShowOperation;
use App\Base\Traits\ActivityLogTraits;
use App\Base\Operations\CreateOperation;
use App\Base\Operations\DeleteOperation;
use App\Base\Operations\UpdateOperation;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Models\CoreMaster\MstFedLocalLevelType;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class BaseCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;
    use ParentData;
    use ActivityLogTraits;
    use CheckPermission;

    protected $activity = ['index','create','edit','update','store','show','destroy'];

    public function __construct()
    {

        if ($this->crud) {
            return;
        }
        $this->middleware(function ($request, $next) {
            $this->crud = app()->make('crud');
            // ensure crud has the latest request
            $this->crud->setRequest($request);
            $this->enableDialog(false);
            $this->request = $request;
            $this->setupDefaults();
            $this->setup();
            $this->setLogs();
            // $this->isAllowed(['show' => 'list']);
            $this->crud->denyAccess('show');
            $this->setupConfigurationForCurrentOperation();
            return $next($request);
        });
        // parent::__construct();
    }

    protected function addCodeField()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addReadOnlyCodeField()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'attributes' => [
                'id' => 'code',
                'readonly' => true,
            ],
        ];
    }

    protected function addPlainHtml()
    {
        return   [
            'type' => 'custom_html',
            'name' => 'plain_html_1',
            'value' => '<br>',
        ];
    }

    protected function addNameEnField()
    {
        return [
            'name' => 'name_en',
            'label' => trans('common.name_en'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-en',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addNameLcField()
    {
        return [
            'name' => 'name_lc',
            'label' => trans('common.name_lc'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-lc',
                'required' => 'required',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addSettingNameEnField()
    {
        return [
            'name' => 'office_name_en',
            'label' => trans('common.name_en'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-en',
                'required' => 'required',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addSettingNameLcField()
    {
        return [
            'name' => 'office_name_lc',
            'label' => trans('common.name_lc'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-lc',
                'required' => 'required',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
 
    protected function addProvinceField()
    {
        return [
            'name' => 'province_id',
            'type' => 'select2',
            'entity' => 'provinceEntity',
            'attribute' => 'name_en',
            'model' => MstFedProvince::class,
            'label' => trans('common.province'),
            'options'   => (function ($query) {
                return (new MstFedProvince())->getFieldComboOptions($query);
            }),
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }
    protected function addDistrictField()
    {
        return  [
            'name' => 'district_id',
            'type' => 'select2',
            'entity' => 'districtEntity',
            'attribute' => 'name_en',
            'model' => MstFedDistrict::class,
            'label' => trans('जिल्ला'),
            'options'   => (function ($query) {
                return (new MstFedDistrict())->getFieldComboOptions($query);
            }),
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addLocalLevelTypeField()
    {
        return  [
            'name' => 'level_type_id',
            'type' => 'select2',
            'entity' => 'levelTypeEntity',
            'attribute' => 'name_en',
            'model' => MstFedLocalLevelType::class,
            'label' => trans('स्थानीय तह प्रकार'),
            'options'   => (function ($query) {
                return (new MstFedLocalLevelType())->getFieldComboOptions($query);
            }),
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addDateBsField()
    {
        return  [
            'name' => 'date_bs',
            'type' => 'nepali_date',
            'label' => trans('common.date_bs'),
            'attributes' => [
                'id' => 'date_bs',
                'relatedId' => 'date_ad',
                'maxlength' => '10',
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
    protected function addDateAdField()
    {
        return [
            'name' => 'date_ad',
            'type' => 'date',
            'label' => trans('common.date_ad'),
            'attributes' => [
                'id' => 'date_ad',
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addRemarksField()
    {
        return [
            'name' => 'remarks',
            'label' => trans('common.remarks'),
            'type' => 'textarea',
            'wrapper' => [
                'class' => 'form-group col-md-12',
            ],
        ];
    }
    protected function addDescriptionField()
    {
        return [
            'name' => 'description',
            'label' => trans('common.description'),
            'type' => 'textarea',
            'wrapper' => [
                'class' => 'form-group col-md-12',
            ],
        ];
    }

    protected function addDescriptionColumn()
    {
        return [
            'name' => 'description',
            'label' => trans('common.description'),
            'type' => 'textarea',
        ];
    }



    public function addIsActiveField()
    {
        return [
            'name' => 'is_active',
            'label' => trans('common.is_active'),
            'type' => 'radio',
            'default' => 1,
            'inline' => true,
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'options' =>
            [
                1 => 'Yes',
                0 => 'No',
            ],
        ];
    }

    public function addDisplayOrderField()
    {
        return [
            'name' => 'display_order',
            'type' => 'number',
            'label' => trans('common.display_order'),
            'default' => 0,
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }


    // common columns

    protected function addRowNumberColumn()
    {
        return [
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => trans('common.row_number'),
        ];
    }

    protected function addCodeColumn()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
        ];
    }


    protected function addNameEnColumn()
    {
        return [
            'name' => 'name_en',
            'label' => trans('common.name_en'),
            'type' => 'text',
        ];
    }

    protected function addNameLcColumn()
    {
        return [
            'name' => 'name_lc',
            'label' => trans('common.name_lc'),
            'type' => 'text',
        ];
    }
    protected function addSettingNameEnColumn()
    {
        return [
            'name' => 'office_name_en',
            'label' => trans('common.office_name_en'),
            'type' => 'text',
        ];
    }

    protected function addSettingNameLcColumn()
    {
        return [
            'name' => 'office_name_'.lang(),
            'label' => trans('common.office_name_'.lang()),
            'type' => 'text',
        ];
    }


    protected function addProvinceColumn()
    {
        return [
            'name' => 'province_id',
            'type' => 'select',
            'entity' => 'provinceEntity',
            'attribute' => 'name_en',
            'model' => MstFedProvince::class,
            'label' => trans('common.province'),
        ];
    }
    

    
    protected function addDistrictColumn()
    {
        return [
            'name' => 'district_id',
            'type' => 'select',
            'entity' => 'districtEntity',
            'attribute' => 'name_en',
            'model' => MstFedDistrict::class,
            'label' => trans('common.district'),
        ];
    }

    protected function addLocalLevelColumn()
    {
        return [
            'name' => 'level_type_id',
            'type' => 'select',
            'entity' => 'levelTypeEntity',
            'attribute' => 'name_en',
            'model' => MstFedLocalLevelType::class,
            'label' => trans('common.localLevelType'),
        ];
    }

    
   
    protected function addDateBsColumn()
    {
        return  [
            'name' => 'date_bs',
            'type' => 'nepali_date',
            'label' => trans('common.date_bs'),
        ];
    }
    protected function addDateAdColumn()
    {
        return [
            'name' => 'date_ad',
            'type' => 'date',
            'label' => trans('common.date_ad'),
        ];
    }



    public function addIsActiveColumn()
    {
        return [
            'name' => 'is_active',
            'label' => trans('common.is_active'),
            'type' => 'radio',
            'options' =>
            [
                1 => 'Yes',
                0 => 'No',
            ],
        ];
    }

    public function addDisplayOrderColumn()
    {
        return [
            'name' => 'display_order',
            'type' => 'number',
            'label' => trans('common.display_order'),
        ];
    }
    public function addRemarksColumn()
    {
        return [
            'name' => 'remarks',
            'type' => 'text',
            'label' => trans('common.remarks'),
        ];
    }

    protected function addFiscalYearField()
    {
        return [
            'name' => 'fiscal_year_id',
            'type' => 'select2',
            'entity' => 'fiscalYearEntity',
            'attribute' => 'code',
            'model' => MstFiscalYear::class,
            'label' => trans('common.fiscal_year'),
            // 'options'   => (function ($query) {
            //     return (new MstFiscalYear())->getFieldComboFiscalOptions($query);
            // }),
            'options'   => (function ($query) {
                // dd($query->get()->keyBy('id')->pluck('code', 'id')->toArray());
                return $query->select('id','code')->orderBy('code', 'DESC')->get();
            }), 
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }

    protected function addAddressEnField()
    {
        return [
            'name' => 'address_name_en',
            'label' => trans('common.address_name_en'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addAddressLcField()
    {
        return [
            'name' => 'address_name_lc',
            'label' => trans('common.address_name_lc'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
    protected function addPhoneField()
    {
        return [
            'name' => 'Phone',
            'label' => trans('common.phone_no'),
            'type' => 'number',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
    protected function addFaxField()
    {
        return [
            'name' => 'fax',
            'label' => trans('common.fax'),
            'type' => 'number',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
    protected function addEmailField()
    {
        return [
            'name' => 'email',
            'label' => trans('common.email'),
            'type' => 'email',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }



    //common filters

    public function addNameEnFilter()
    {
        return $this->crud->addFilter(

            [
                'label' => trans('common.name_en'),
                'type' => 'text',
                'name' => 'name_en',
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name_en', 'iLIKE', '%' . $value . '%');
            }
        );
    }

    public function addNameLcFilter()
    {
        return $this->crud->addFilter(

            [
                'label' => trans('common.name_lc'),
                'type' => 'text',
                'name' => 'name_lc',
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name_lc', 'iLIKE', '%' . $value . '%');
            }
        );
    }

    protected function readonlyOrDisableFields($fields, $data, $attribute)
    {
        $res = [];
        foreach ($data as $key => $arr) {
            if (isset($arr['name']) && in_array($arr['name'], $fields)) {
                $arr['attributes'][$attribute] = $attribute;
            }
            $res[] = $arr;
        }
        return $res;
    }
}
