<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use App\Models\MinistryBudgetInfo;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Http\Requests\MinistryBudgetInfoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryBudgetInfoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryBudgetInfoCrudController extends BaseCrudController
{
    

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryBudgetInfo::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry-budget-info');
        CRUD::setEntityNameStrings('मन्त्रालय बजेट विवरण', 'मन्त्रालय बजेट विवरण');

        $this->data['script_js'] = $this->getScripts();
        $this->addFilters();
        $this->crud->enableExportButtons();
    }



    public function getScripts()
    {
        return "
        $(document).ready(function (){
            $('#budget_allocation_current, #budget_allocation_capital').on('keyup', function(){
                var val1 = parseInt($('#budget_allocation_current').val());
                var val2 = parseInt($('#budget_allocation_capital').val());
                $('#budget_allocation_total').val(val1 + val2);
        
            });
        });
        ";
    }


    public function getMinBudget(Request $request){
        $fiscal_year_id = $request->fiscal_year_id;
        $ministry_id = $request->ministry_id;
        $budget = MinistryBudgetInfo::where('fiscal_year_id', $fiscal_year_id)->where('ministry_id', $ministry_id)->first();
        if($budget){
            return $budget;
        }
        return null;
    }


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
            'label'=> 'मन्त्रालय नाम',
            'type'=>'select2'
          ], function() {
              return MstMinistry::all()->pluck('name_lc', 'id')->toArray();
          }, function($value) { 
              $this->crud->addClause('where', 'ministry_id', $value);
          });
            }        // ministry filter

        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $columns = [
            $this->addRowNumber(),
            [
                'name'=>'fiscal_year_id',
                'type'=>'model_function',
                'label'=>'आर्थिक वर्ष',
                'function_name'=>'fiscalYearName'
            ],
            $this->addMinistryColumn(),
            [
                'name' => 'current_budget',
                'type' => 'text',
                'label' => 'बजेट विनियोजित - चालु'
            ],
            [
                'name' => 'capital_budget',
                'type' => 'text',
                'label' => 'बजेट विनियोजित - पूँजीगत'
            ],
            [
                'name' => 'total_budget',
                'type' => 'text',
                'label' => 'बजेट विनियोजित - जम्मा'
            ]
        ];
        $this->crud->addColumns(array_filter($columns));

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MinistryBudgetInfoRequest::class);

        $arr = [
            [
                'name' => 'fiscal_year_id',
                'type' => 'select2',
                'entity'=>'fiscalYear',
                'attribute' => 'code',
                'model'=> MstFiscalYear::class,
                'label' => 'आर्थिक वर्ष',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                'required' => 'required',
                ],
                'options'   => (function ($query) {
                    
                    return $query->orderBy('id', 'DESC')->get();
                }),
                'default' => backpack_user()->ministry?backpack_user()->ministry->appSetting->fiscal_year_id:null
            ],
            $this->addMinistryField(),
            // [
            //     'name' => 'ministry_id',
            //     'type' => 'select2',
            //     'entity'=>'ministry',
            //     'attribute' => 'name_lc',
            //     'model'=> MstMinistry::class,
            //     'label' => 'मन्त्रालयको नाम',
            //     'options'   => (function ($query) {
            //         if(backpack_user()->hasAnyRole(['superadmin','admin'])){
            //             return $query->get();
            //         }else{
            //             return $query->where('id',backpack_user()->ministry_id)->get();
            //         }
            //     }), 
            //     'wrapper' => [
            //         'class' => 'form-group col-md-6',
            //     ],
            //     'attributes' => [
            //         'required' => 'required',
            //         'id'=>'ministry_id',
            //         'placeholder'=>'select ministry',
            //     ],
            // ],

            [
                'name' => 'legend1',
                'type' => 'custom_html',
                'value' => '<legend>&nbsp;&nbsp;बजेट विनियोजित</legend>',
            ],

            [
                'name' => 'current_budget',
                'type' => 'number',
                'label' => 'चालु',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'budget_allocation_current',
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'capital_budget',
                'type' => 'number',
                'label' => 'पूँजीगत',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                
                'id'=>'budget_allocation_capital',
                'required' => 'required',
                ],
            ],
            [
                'name' => 'total_budget',
                'type' => 'number',
                'label' => 'जम्मा',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'budget_allocation_total',
                    'readonly'=>'readonly',
                    'required' => 'required',
                ],
            ]
        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
