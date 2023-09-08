<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Models\MinistryMembers;
use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstMinistry;
use App\Http\Requests\MinistryMembersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MinistryMembersCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryMembers::class);
        CRUD::setRoute('/admin/mst-ministry/'.$this->parent('ministry_id').'/members');

        CRUD::setEntityNameStrings('मन्त्रालय सदस्य', 'मन्त्रालयका सदस्यहरू');
        $this->setUpLinks(['index']);
        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['index','edit'])){
            $mst_ministry = MstMinistry::find($this->parent('ministry_id'));
            $this->data['custom_title'] =$mst_ministry->name_lc;
        }
    }

    public function tabLinks()
    {
        $links = [];
        $links[] = ['label' => 'मन्त्रालय', 'href' => backpack_url('mst-ministry/'.$this->parent('ministry_id').'/edit')];
        $links[] = ['label' => 'मन्त्रालयका सदस्यहरू', 'href' => $this->crud->route];
        $links[] = ['label' => 'मन्त्रालयको दरबन्दी', 'href' => backpack_url('mst-ministry/'.$this->parent('ministry_id').'/darbandi')];

        return $links;
    }


    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            [
                'name' => 'post_id',
                'type' => 'select_from_array',
                'label' => 'पद',
                'options' => MinistryMembers::$post,
            ],
            [
                'name' => 'name_lc',
                'type' => 'text',
                'label' => 'नाम',
            ],
            [
                'name' => 'name_en',
                'type' => 'text',
                'label' => 'Name',
            ],
            [
                'name' => 'mobile_number',
                'type' => 'text',
                'label' => 'मोबाइल नम्बर',
            ],
            [
                'name' => 'from_date_bs',
                'type' => 'text',
                'label' => 'मिति देखि',
            ],
            [
                'name' => 'to_date_bs',
                'type' => 'text',
                'label' => 'मिति सम्म',
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
        CRUD::setValidation(MinistryMembersRequest::class);
        $this->addMinistryMemberFields();
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    public function addMinistryMemberFields(){
        $arr = [
            [
                'type' => 'hidden',
                'name' => 'ministry_id',
                'value' => $this->parent('ministry_id'),
            ],
            [
                'name' => 'post_id',
                'type' => 'select_from_array',
                'label' => 'पद',
                'options' => MinistryMembers::$post,
                'validationRules' => 'required',
                'validationMessages' => [
                    'required' => ' The result field type is required.',
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                'id' => 'post_id'
                ],
            ],
            [
                'name' => 'name_lc',
                'type' => 'text',
                'label' => 'नाम',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                'maxlength'=>'100',
                ],
            ],
            [
                'name' => 'name_en',
                'type' => 'text',
                'label' => 'Name',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'maxlength'=>'100',
                ],
            ],
            [
                'name' => 'mobile_number',
                'type' => 'number',
                'label' => 'मोबाइल नम्बर',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'maxlength'=>'10',
                ],
            ],

            [
                'name' => 'from_date_bs',
                'type' => 'nepali_date',
                'label' => trans(' मिति देखि (बि.स.)'),
                 'attributes'=>
                  [
                    'id'=>'from_date_bs',
                    'relatedId' =>'from_date_ad',
                    'maxlength' =>'10',
                 ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-4',
                 ],
            ],

            [
                'name' => 'from_date_ad',
                'type' => 'date',
                'label' => trans(' मिति देखि (इ.स.)'),
                'attributes'=>
                [
                'id'=>'from_date_ad',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'to_date_bs',
                'type' => 'nepali_date',
                'label' => trans(' मिति सम्म (बि.स.)'),
                 'attributes'=>
                  [
                    'id'=>'to_date_bs',
                    'relatedId' =>'to_date_ad',
                    'maxlength' =>'10',
                 ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-4',
                 ],
            ],

            [
                'name' => 'to_date_ad',
                'type' => 'date',
                'label' => trans('देखि सम्म (इ.स.)'),
                'attributes'=>[
                    'id'=>'to_date_ad',
                    ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'legend2',
                'type' => 'custom_html',
                'value' => '<br>',
            ],

            [
                'name' => 'is_active',
                'label' => 'सक्रिय हो ?',
                'type' => 'radio',
                'options'     => [
                    0 => 'होइन',
                    1 => 'हो',
                ],
                'inline' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],

            ],

        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }

}
