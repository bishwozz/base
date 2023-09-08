<?php

namespace App\Http\Controllers\Admin;

use App\Base\BaseCrudController;
use App\Http\Requests\CommitteeMembersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class CommitteeMembersCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(\App\Models\CommitteeMembers::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/committee/'.$this->parent('committee_id').'/members');
        CRUD::setEntityNameStrings('समिति सदस्य', 'समिति सदस्यहरु');
        $this->setUpLinks();

    }

    public function tabLinks(){
        return  $this->setCommitteeTabs();
    }


    protected function setupListOperation()
    {

        $columns = [
			$this->addRowNumberColumn(),
            [
                'name'=>'mp_id',
                'type'=>'select',
                'label'=>trans('MinistryMember.mp_id'),
                'entity'=>'ec_mp',
                'model'=>'App\Models\EcMp',
                'attribute'=>'name_lc'
            ],

            [
                'name'=>'date_from_bs',
                'label'=>trans('MinistryMember.date_from')
            ],
            [
                'name'=>'date_to_bs',
                'label'=>trans('MinistryMember.date_to')
            ],
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),
            $this->addRemarksColumn(),

		];
        $this->crud->addColumns(array_filter($columns));
        if($this->parent('committee_id') == null){
            abort(404);
        }
        else{
            $this->crud->addClause('where', 'committee_id', $this->parent('committee_id'));
        }
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommitteeMembersRequest::class);

        $arr=[
            [
                'type'  => "hidden",
                'name'  => 'committee_id',
                'value' => $this->parent('committee_id'),
                'attributes'=>
                [
                  'required' => 'Required',
               ],
            ],
            [
                'name'=>'mp_id',
                'type'=>'select2',
                'label'=>trans('MinistryMember.mp_id'),
                'entity'=>'ec_mp',
                'model'=>'App\Models\EcMp',
                'attribute'=>'name_lc',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'id'=>'mp-id',
                    // 'disabled'=>true
                ],
            ],
            [
                'name' => 'date_from_bs',
                'type' => 'nepali_date',
                'label' => trans('common.date_from_bs'),
                'attributes' => [
                    'id' => 'date_bs',
                    'relatedId' => 'date-from-ad',
                    'maxlength' => '10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

            [
                'name'=>'date_from_ad',
                'type'=>'date',
                'label'=>trans('common.date_from_ad'),
                'attributes'=>[
                    'id'=>'date-from-ad',

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'date_to_bs',
                'type'=>'nepali_date',
                'label'=>trans('common.date_to_bs'),
                'attributes'=>[

                    'id'=>'date-to-bs',
                    'relatedId' => 'date-to-ad',

                    'placeholder'  => 'yyy-mm-dd',
                    'maxlength' => '10',

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'date_to_ad',
                'type'=>'date',
                'label'=>trans('common.date_to_ad'),
                'attributes'=>[
                      'id'=>'date-to-ad',
                      'placeholder'  => 'yyy-mm-dd',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
            $this->addRemarksField(),
        ];
        $this->crud->addFields(array_filter($arr));
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
