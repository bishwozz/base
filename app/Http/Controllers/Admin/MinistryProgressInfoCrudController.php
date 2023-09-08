<?php

namespace App\Http\Controllers\Admin;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Http\Requests\MinistryProgressInfoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryProgressInfoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryProgressInfoCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryProgressInfo::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry-progress-info');
        CRUD::setEntityNameStrings('मन्त्रालय प्रगति विवरण', 'मन्त्रालय प्रगति विवरण');
        $this->data['script_js'] = $this->getScripts();
        $this->addFilters();
        // Show export to PDF, CSV, XLS and Print buttons on the table view. Please note it will only export the current _page_ of results. So in order to export all entries the user needs to make the current page show "All" entries from the top-left picker.
        $this->crud->enableExportButtons();
        $this->crud->enableResponsiveTable();
    }


    public function getScripts()
    {
        return "
        $(document).ready(function (){
            $('#financial_progress_current_amount, #financial_progress_capital_amount, #financial_progress_current, #financial_progress_capital').on('keyup', function(){
                var val1_amount = parseFloat($('#financial_progress_current_amount').val());
                var val2_amount = parseFloat($('#financial_progress_capital_amount').val());

                var mbcur = parseFloat($('#ministry_budget_current').val());
                var mbcap = parseFloat($('#ministry_budget_capital').val());
                var mbtot = parseFloat($('#total_ministry_budget').val());

                $('#financial_progress_current').val((val1_amount/mbcur)*100)
                $('#financial_progress_capital').val((val2_amount/mbcap)*100)
                $('#financial_progress_total_amount').val(val1_amount + val2_amount);

                var val1 = parseFloat($('#financial_progress_current').val());
                var val2 = parseFloat($('#financial_progress_capital').val());
        
                
        
                var financial_cur = (mbcur * val1) / 100;
                var financial_cap = (mbcap * val2) / 100;
                var total_financial = financial_cur + financial_cap;
        
                $('#financial_progress_total').val(((total_financial / mbtot) * 100).toFixed(2));
            });
        
            $('#physical_progress_current, #physical_progress_capital').on('keyup', function(){
                var val1 = parseFloat($('#physical_progress_current').val());
                var val2 = parseFloat($('#physical_progress_capital').val());
        
                $('#physical_progress_total').val((val1 + val2) / 2);
            });
        
            $('#ministry_id').on('change', function() {
                get_ministry_budget();
            });
            get_ministry_budget();
        });
        

        


        function get_ministry_budget(){
            var min_id = $('#ministry_id').val();
            var fy_id = $('#fiscal_year_id').val();
            
            $.ajax({
                type:'POST',
                url:'/admin/minbudgetinfo',
                data:{
                    'ministry_id':min_id,
                    'fiscal_year_id':fy_id
                },
                success: function(data){
                    $('#ministry_budget_current').val(data.current_budget);
                    $('#ministry_budget_capital').val(data.capital_budget);
                    $('#total_ministry_budget').val(data.total_budget);
                }

            });
        }

        
        
        ";
        
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

        // month filter
        $this->crud->addFilter([
            'name'=>'month_id',
            'label'=> 'महिना',
            'type'=>'select2'
          ], function() {
              return MstNepaliMonth::all()->pluck('name_lc', 'id')->toArray();
          }, function($value) { 
              $this->crud->addClause('where', 'month_id', $value);
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
            [
                'name'=>'month_id',
                'type'=>'model_function',
                'label' => 'महिना',
                'function_name'=>'monthNmae'
            ],
            $this->addMinistryColumn(),

            [
                'name' => 'current_progress_financial',
                'type' => 'text',
                'label' => 'वित्तीय प्रगति - चालु',
            ],[
                'name' => 'capital_progress_financial',
                'type' => 'text',
                'label' => 'वित्तीय प्रगति - पूँजीगत',
            ],[
                'name' => 'total_progress_financial',
                'type' => 'text',
                'label' => 'वित्तीय प्रगति - जम्मा',
            ],[
                'name' => 'current_progress_physical',
                'type' => 'text',
                'label' => 'भौतिक प्रगति - चालु',
            ],[
                'name' => 'capital_progress_physical',
                'type' => 'text',
                'label' => 'भौतिक प्रगति - पूँजीगत',
            ],[
                'name' => 'total_progress_physical',
                'type' => 'text',
                'label' => 'भौतिक प्रगति - जम्मा',
            ],
            [
                'name' => 'beruju_farchyat_percent',
                'type' => 'text',
                'label' => 'बेरुजु फछर्यौटको प्रगति प्रतिशत',
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
        CRUD::setValidation(MinistryProgressInfoRequest::class);

        $arr = [
            [
                'name' => 'fiscal_year_id',
                'type' => 'select2',
                'entity'=>'fiscalYear',
                'attribute' => 'code',
                'model'=> MstFiscalYear::class,
                'label' => 'आर्थिक वर्ष',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                'id'=>'fiscal_year_id',
                ],
                'options'   => (function ($query) {
                    
                    return $query->orderBy('id', 'DESC')->get();
                }),
                'default' => backpack_user()->ministry?backpack_user()->ministry->appSetting->fiscal_year_id:null
            ],


            [
                'name' => 'month_id',
                'type' => 'select2',
                'entity'=>'month',
                'attribute' => 'name_lc',
                'model'=> MstNepaliMonth::class,
                'label' => 'महिना',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                ],
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
            //         'class' => 'form-group col-md-4',
            //     ],
            //     'attributes' => [
            //         'required' => 'required',
            //         'id'=>'ministry_id',
            //         'placeholder'=>'select ministry',
            //     ],
            // ],
            [
                'name' => 'legendm',
                'type' => 'custom_html',
                'value' => '<legend>&nbsp;&nbsp;मन्त्रालय बजेट विवरण</legend>',
            ],

            [
                'name' => 'ministry_budget_current',
                'type' => 'number',
                'label' => 'चालु',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'ministry_budget_current',
                    'readonly' => 'readonly',
                    'required' => 'required',

                ],
            ],[
                'name' => 'ministry_budget_capital',
                'type' => 'number',
                'label' => 'पूँजीगत',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'ministry_budget_capital',
                    'readonly' => 'readonly',
                    'required' => 'required',
                ],
            ],[
                'name' => 'total_ministry_budget',
                'type' => 'number',
                'label' => 'जम्मा',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'total_ministry_budget',
                    'readonly'=>'readonly',
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'legend0',
                'type' => 'custom_html',
                'value' => '<legend>&nbsp;&nbsp;यो महिना को वित्तीय खर्च</legend>',
            ],

            [
                'name' => 'current_progress_financial_amount',
                'type' => 'number',
                'label' => 'चालु (रु)',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'financial_progress_current_amount',
                    'min' => 0,
                    'step' => 'any',
                    'required' => 'required',

                ],
            ],
            [
                'name' => 'capital_progress_financial_amount',
                'type' => 'number',
                'label' => 'पूँजीगत (रु)',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'financial_progress_capital_amount',
                    'min' => 0,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'total_progress_financial_amount',
                'type' => 'number',
                'label' => 'जम्मा (रु)',
                'prefix' => 'रु.',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'financial_progress_total_amount',
                    'readonly'=>'readonly',
                    'min' => 0,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],

            [
                'name' => 'current_progress_financial',
                'type' => 'number',
                'label' => 'चालु (%)',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'financial_progress_current',
                    'readonly'=>'readonly',
                    'max' => 100,
                    'step' => 'any',
                    'required' => 'required',

                ],
            ],[
                'name' => 'capital_progress_financial',
                'type' => 'number',
                'label' => 'पूँजीगत (%)',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'financial_progress_capital',
                    'readonly'=>'readonly',
                    'max' => 100,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],[
                'name' => 'total_progress_financial',
                'type' => 'number',
                'label' => 'जम्मा (%)',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'financial_progress_total',
                    'readonly'=>'readonly',
                    'max' => 100,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'legend1',
                'type' => 'custom_html',
                'value' => '<legend>&nbsp;&nbsp;यो महिना को भौतिक प्रगति</legend>',
            ],
            [
                'name' => 'current_progress_physical',
                'type' => 'number',
                'label' => 'चालु',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'physical_progress_current',
                    'max' => 100,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],[
                'name' => 'capital_progress_physical',
                'type' => 'number',
                'label' => 'पूँजीगत',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'physical_progress_capital',
                    'max' => 100,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],[
                'name' => 'total_progress_physical',
                'type' => 'number',
                'label' => 'जम्मा',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'id'=>'physical_progress_total',
                    'readonly'=>'readonly',
                    'max' => 100,
                    'step' => 'any',
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'beruju_farchyat_percent',
                'type' => 'number',
                'label' => 'बेरुजु फछर्यौटको प्रगति प्रतिशत',
                'suffix' => '%',
                'wrapper' => [
                    'class' => 'form-group1 form-group col-md-4',
                ],
                'attributes' => [
                    
                    'max' => 100,
                    'step' => 'any',
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
