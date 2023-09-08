<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Http\Requests\CoreMaster\MstMinistryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class MstMinistryCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(MstMinistry::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-ministry');
        CRUD::setEntityNameStrings('मन्त्रालय', 'मन्त्रालय विवरण');
        $this->setUpLinks(['edit']);

        if(!backpack_user()->hasAnyRole(['superadmin','admin'])){
            $this->crud->addClause('where','id',backpack_user()->ministry_id);
        }

        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['edit'])){
            $mst_ministry = MstMinistry::find($this->parent('id'));
            $this->data['custom_title'] =$mst_ministry->title;
        }
    }

    public function tabLinks()
    {
        $links = [];
            $links[] = ['label' => 'मन्त्रालय विवरण', 'href' => backpack_url('mst-ministry/'.$this->parent('id').'/edit')];
            $links[] = ['label' => 'मन्त्रालयका सदस्यहरू', 'href' => backpack_url('mst-ministry/'.$this->parent('id').'/members')];
            $links[] = ['label' => 'मन्त्रालयको दरबन्दी', 'href' => backpack_url('mst-ministry/'.$this->parent('id').'/darbandi')];
        return $links;
    }

    protected function setupListOperation()
    {


        $col = [
            $this->addRowNumberColumn(),
            [
                'name' => 'name_en',
                'label' => 'Ministry Name',
                'type' => 'text',
            ],
            [
                'name' => 'name_lc',
                'label' => 'मन्त्रालयको नाम',
                'type' => 'text',
            ],
            [
                'name' => 'is_active',
                'label' => 'सक्रिय हो ?',
                'type'=>'check',
            ],

            [
                'name' => 'organogram',
                'label' => 'अर्गानोग्राम',
                'type' => 'custom_file',
                'upload' => true,
                'disk' => 'uploads',
            ],

            ];
            $this->crud->addColumns($col);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstMinistryRequest::class);
        $arr = [

            [
                'name' => 'name_lc',
                'type' => 'text',
                'label' => 'मन्लालयको नाम',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                'id'=>'secretary_name',
                ],
            ],
            [
                'name' => 'name_en',
                'type' => 'text',
                'label' => 'Ministry Name',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'id'=>'secretary_name_en',
                ],
            ],
            [
                'name' => 'is_active',
                'label' => 'सक्रिय हो ?',
                'type' => 'radio',
                'default' => 1,
                'inline' => true,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'options' =>
                [
                    1 => 'हो',
                    0 => 'होइन',
                ],
            ],
            [
                'name' => 'organogram',
                'type' => 'upload',
                'upload' => true,
                'disk' => 'uploads',
                'label' => 'अर्गानोग्राम अपलोड गर्नुहोस्',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'विवरण',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],


        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
