<?php

namespace App\Http\Controllers\Admin\CoreMaster;


use App\Base\BaseCrudController;
use Prologue\Alerts\Facades\Alert;
use App\Models\CoreMaster\AppSetting;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Http\Requests\CoreMaster\AppSettingRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AppSettingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AppSettingCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\AppSetting::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/app-setting');
        CRUD::setEntityNameStrings(trans('menu.appSetting'), trans('menu.appSetting'));
        if(!backpack_user()->hasRole('superadmin|admin')){   
            $this->crud->addClause('where','ministry_id',backpack_user()->ministry_id);
        }
        if(backpack_user()->hasRole('admin')){
            $this->crud->addClause('where','id','<>',1);
        }
        $this->data['script_js'] = $this->getScriptJs();
    }


    private function getScriptJs(){
        return "
        function appSetting_letter_head(){
            var title_1 = $('form input[name=letter_head_title_1]').val(),
            title_2 = $('form input[name=letter_head_title_2]').val(),
            title_3 = $('form input[name=letter_head_title_3]').val(),
            title_4 = $('form input[name=letter_head_title_4]').val();
            
            $('#letter_head_title_1_label').html(title_1);
            $('#letter_head_title_2_label').html(title_2);
            $('#letter_head_title_3_label').html(title_3);
            $('#letter_head_title_4_label').html(title_4);
        }
        $(document).ready(function(){
            appSetting_letter_head();
            $('form input[name=letter_head_title_1]').keyup(appSetting_letter_head);
            $('form input[name=letter_head_title_2]').keyup(appSetting_letter_head);
            $('form input[name=letter_head_title_3]').keyup(appSetting_letter_head);
            $('form input[name=letter_head_title_4]').keyup(appSetting_letter_head);
        });
        ";
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
            [
                'name' => 'office_name_lc',
                'label' => trans('कार्यालयको नाम'),
                'type' => 'text',
            ],
            [
                'name' => 'address_name_lc',
                'label' => trans('ठेगाना'),
                'type' => 'text',
            ],
            [
                'name' => 'phone',
                'label' => trans('सम्पर्क न.'),
                'type' => 'text',
            ],
            [
                'name' => 'fax',
                'label' => trans('फ्याक्स'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => trans('इमेल'),
                'type' => 'email',
            ],

        ];
        $this->crud->addColumns($cols);
        $this->crud->removeButtons(['create','delete']);

    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AppSettingRequest::class);

        $arr = [
            $this->addSettingNameLcField(),
            $this->addSettingNameEnField(),
            $this->addAddressLcField(),
            $this->addAddressEnField(),
            $this->addFiscalYearField(),
            $this->addPhoneField(),
            $this->addFaxField(),
            $this->addEmailField(),
            $this->addRemarksField(),
            [
                'name' => 'legend12',
                'type' => 'custom_html',
                'value' => '<b><legend>&nbsp;&nbsp;पत्रको शिर्षक:</legend></b>',
            ],
            [
                'name' => 'div_1-0',
                'type' => 'plain_html',
                'value' => '<div class="row shakti col-md-12">',
            ],
            [
                'name' => 'div_1-2',
                'type' => 'plain_html',
                'value' => '<div class="col-md-6">',
            ],
            [
                'name' => 'letter_head_title_1',
                'label' =>  trans('शीर्षक एक'),
                'type' => 'text',
            ],
            [
                'name' => 'letter_head_title_2',
                'label' =>  trans('शीर्षक दुई'),
                'type' => 'text',
            ],
            [
                'name' => 'letter_head_title_3',
                'label' =>  trans('शीर्षक तीन'),
                'type' => 'text',
            ],
            [
                'name' => 'letter_head_title_4',
                'label' => trans('शीर्षक चार'),
                'type' => 'text',
            ],
            [ 
                'name' => 'div_1-2_close',
                'type' => 'plain_html',
                'value'=> '</div>',
            ],
            [
                'name' => 'div_1-2a',
                'type' => 'plain_html',
                'value' => '<div class="col-md-6">',
            ],
            [
                'name' => 'div_1-2ac',
                'type' => 'plain_html',
                'value' => '<div class="col-md-12">
                <style>
                    .head-address{
                        text-align: center;
                    }
                </style>
                <h3 class="head-address" style="color:red; margin-left:30px;text-decoration: underline">
                <span id="">पत्र शिर्षकको नमुना </span>
                </h3>
                <br/>
                <h2 class="head-address" id="letter_head_title_label">
                    <span id="letter_head_title_1_label">-</span><br/> 
                    <span style="font-size: 18px;" id="letter_head_title_2_label">-</span><br/> 
                    <span style="font-size: 18px;" id="letter_head_title_3_label">-</span><br/> 
                    <span style="font-size: 16px;" id="letter_head_title_4_label">-</span>
                </h2>
                </div>',
            ],
            [ 
                'name' => 'div_1-2a_close',
                'type' => 'plain_html',
                'value'=> '</div>',
            ],

            [ 
                'name' => 'div_1-0_close',
                'type' => 'plain_html',
                'value'=> '</div>',
            ],

        ];

        $arr = array_filter($arr);
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

    public function store()
    {
        $request = request();
        $appSetting = AppSetting::create([
          'code' => $request->code,
          'office_name_lc' => $request->office_name_lc,
          'office_name_en' => $request->office_name_en,
          'address_name_lc' => $request->address_name_lc,
          'address_name_en' => $request->address_name_en,
          'letter_head_title_1' => $request->letter_head_title_1,
          'letter_head_title_2' => $request->letter_head_title_2,
          'letter_head_title_3' => $request->letter_head_title_3,
          'letter_head_title_4' => $request->letter_head_title_4,
          'fiscal_year_id' => $request->fiscal_year_id,
          'phone' => $request->phone,
          'fax' => $request->fax,
          'email' => $request->email,
          'remarks' => $request->remarks,
        ]);

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect(backpack_url('app-setting'));
    }

    public function update()
    {
        $request = request();
        $app_id = $request->id;
        // dd($request->id);

        $appSetting = AppSetting::find($app_id);
        $appSetting->code = $request->code;
        $appSetting->office_name_lc = $request->office_name_lc;
        $appSetting->office_name_en = $request->office_name_en;
        $appSetting->address_name_lc =$request->address_name_lc;
        $appSetting->address_name_en = $request->address_name_en;
        $appSetting->letter_head_title_1 = $request->letter_head_title_1;
        $appSetting->letter_head_title_2 = $request->letter_head_title_2;
        $appSetting->letter_head_title_3 = $request->letter_head_title_3;
        $appSetting->letter_head_title_4 = $request->letter_head_title_4;
        $appSetting->fiscal_year_id = $request->fiscal_year_id;
        $appSetting->phone = $request->phone;
        $appSetting->fax = $request->fax;
        $appSetting->email = $request->email;
        $appSetting->remarks =$request->remarks;
        $appSetting->save();

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect(backpack_url('app-setting'));
    }


}
