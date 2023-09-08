<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Models\MstLevel;
use App\Models\MstPosts;
use App\Models\MstGroups;
use App\Base\BaseCrudController;
use App\Models\MinistryDarbandi;
use Prologue\Alerts\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CoreMaster\MstMinistry;
use App\Imports\MinistryDarbandiImport;
use App\Http\Requests\MinistryDarbandiRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MinistryDarbandiCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryDarbandi::class);
        CRUD::setRoute('/admin/mst-ministry/'.$this->parent('ministry_id').'/darbandi');
        CRUD::setEntityNameStrings('मन्त्रालय दरबन्दी', 'मन्त्रालय दरबन्दी');
        $this->setUpLinks(['index']);
        $this->crud->addButtonFromView('top', 'ministryDarbandiImport', 'ministry_darbandi_import', 'end');
        $this->crud->addButtonFromModelFunction('top', 'excelSample', 'excelSample', 'end');
        $this->crud->enableExportButtons();
        $this->data['script_js'] = $this->getScripts();
        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['index','edit'])){
            $mst_ministry = MstMinistry::find($this->parent('ministry_id'));
            $this->data['custom_title'] =$mst_ministry->name_lc;
        }
    }

    public function tabLinks()
    {
        $links = [];
        $links[] = ['label' => 'मन्त्रालय विवरण', 'href' => backpack_url('mst-ministry/'.$this->parent('ministry_id').'/edit')];
        $links[] = ['label' => 'मन्त्रालयका सदस्यहरू', 'href' => backpack_url('mst-ministry/'.$this->parent('ministry_id').'/members')];
        $links[] = ['label' => 'मन्त्रालयको दरबन्दी', 'href' => $this->crud->route];

        return $links;
    }

    public function getScripts()
    {
        return "
        $(document).ready(function (){
            $('#temp-darbandi').on('keyup', function(){
                var val1 = parseInt($('#total-darbandi').val());
                var val2 = parseInt($('#perm-drbandi').val());
                var val3 = parseInt($('#temp-darbandi').val());
                $('#vacant-darbandi').val(val1 - (val2 + val3));
            });
        });
        ";
    }



    protected function setupListOperation()
    {

        $col = [
            $this->addRowNumberColumn(),
            [
                'name' => 'level_id',
                'type' => 'select',
                'label' => 'श्रेणी',
                'entity'=>'level',
                'attribute' => 'name_lc',
                'model'=> MstLevel::class,
            ],
            [
                'name' => 'total_darbandi',
                'label' => 'कुल दरबन्दी',
            ],
            [
                'name' => 'perm_darbandi',
                'label' => 'स्थायी दरबन्दी',
            ],
            [
                'name' => 'temp_darbandi',
                'label' => 'अस्थायी दरबन्दी',
            ],
            [
                'name' => 'vacant_darbandi',
                'label' => 'रिक्त दरबन्दी',
            ],
            [
                'name' => 'is_active',
                'label' => 'सक्रिय हो ?',
                'type'=>'check',
            ],

            ];

            $this->crud->addColumns(array_filter($col));

            if ($this->parent('ministry_id')== null) {
                abort(404);
            } else {
                $this->crud->addClause('where', 'ministry_id', $this->parent('ministry_id'));
            }
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(MinistryDarbandiRequest::class);
        $this->addMinistryDarbandiFields();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function addMinistryDarbandiFields(){
        $arr = [
            [
                'type' => 'hidden',
                'name' => 'ministry_id',
                'value' => $this->parent('ministry_id'),
            ],
            [
                'name' => 'level_id',
                'type' => 'select2',
                'label' => 'श्रेणी',
                'entity'=>'level',
                'attribute' => 'name_lc',
                'model'=> MstLevel::class,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                ],
            ],
            [
                'name' => 'post_id',
                'type' => 'select2',
                'label' => 'पद',
                'entity'=>'post',
                'attribute' => 'name_lc',
                'model'=> MstPosts::class,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                ],
            ],
            [
                'name' => 'group_id',
                'type' => 'select2',
                'label' => 'समूह',
                'entity'=>'group',
                'attribute' => 'name_lc',
                'model'=> MstGroups::class,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                ],
            ],
            [
                'name' => 'legend1',
                'type' => 'custom_html',
                'value' => '<br>',
            ],
            [
                'name' => 'total_darbandi',
                'type' => 'number',
                'label' => 'कुल दरबन्दी',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                'id' => 'total-darbandi',
                'required' => 'required',
                'maxlength'=>'100',
                ],
            ],
            [
                'name' => 'perm_darbandi',
                'type' => 'number',
                'label' => 'स्थायी दरबन्दी',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'id' => 'perm-drbandi',
                'required' => 'required',
                'maxlength'=>'100',
                ],
            ],
            [
                'name' => 'temp_darbandi',
                'type' => 'number',
                'label' => 'अस्थायी दरबन्दी',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'id' => 'temp-darbandi',
                'required' => 'required',
                'maxlength'=>'20',
                ],
            ],
            [
                'name' => 'vacant_darbandi',
                'type' => 'number',
                'label' => 'रिक्त दरबन्दी',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'id' => 'vacant-darbandi',
                'required' => 'required',
                'maxlength'=>'20',
                ],
            ],

            [
                'name' => 'comment',
                'type' => 'textarea',
                'label' => 'टिप्पणी',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
                'attributes' => [
                    'maxlength' => '100',
                    'class' => 'form-control fixed-textarea',
                ],
            ],
            [
                'name'   => 'is_active',
                'label'  => 'सक्रिय हो ?',
                'type'   => 'radio',
                'options' => [
                    0 => "होइन &nbsp;",
                    1 => "हो"
                ],
                'inline' => true, // show the radios all on the same line?
                'wrapper' => [
                    'class' => 'form-group4 form-group col-md-8 mb-4',
                ],
            ],


        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }
    public function importExcel(){
        $import=Excel::import(new MinistryDarbandiImport,request()->file('file'));
        if(!$import){
            \Alert::error('The darbandi is already exist')->flash();
        }else{
            \Alert::success('The file has been imported successfully.')->flash();
        }
        $route = 'admin/mst-ministry/'.request('ministry_id').'/darbandi';
        return redirect($route)->with('success','data imported successfully');
    }
}
