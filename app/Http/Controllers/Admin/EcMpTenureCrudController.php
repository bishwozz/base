<?php

namespace App\Http\Controllers\Admin;

use App\Models\EcMpTenure;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Http\Requests\EcMpTenureRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EcMpTenureCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EcMpTenureCrudController extends BaseCrudController
{
    use ParentData;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\EcMpTenure::class);
        $this->crud->setRoute('admin/ec-mp/'.$this->parent('mp_id').'/tenure');
        CRUD::setEntityNameStrings(trans('menu.ecMptenure'), trans('menu.ecMptenure'));
        $this->setUpLinks();
        $this->checkPermission();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    private function tabLinks(){
        return $this->setEcMpTabs();
    }
    protected function setupListOperation()
    {
        $arr=[
            $this->addRowNumberColumn(),
            [
                'name'=>'date_from_bs',
                'label'=>trans('mp.date_from_bs')
            ],
            [
                'name'=>'date_to_bs',
                'label'=>trans('mp.date_to_bs')
            ],
            [
                'name' => 'display_order',
                'type' => 'number',
                'label'=>trans('common.display_order'),
            ],
            [
                'name'=>'is_active',
                 'label'=>trans('common.is_active'),
                 'type'=>'radio',
                 'options'     => [
                     1 => "हो",
                     0 => "होइन"
                 ]
            ],
        ];
        $this->crud->addColumns($arr);
        $this->crud->OrderBy('display_order');

        if($this->parent('mp_id') == null){
            abort(404);
        } 
        else{
            $this->crud->addClause('where', 'mp_id', $this->parent('mp_id'));
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(EcMpTenureRequest::class);

        $arr=[
           
            [
                'type'  => "hidden",
                'name'  => 'mp_id',
                'value' => $this->parent('mp_id'),
                'attributes'=>[
                    'required' => 'Required',
                ],
            ],

            [
                'name'=>'date_from_bs',
                'type'=>'nepali_date',
                'label'=>trans('mp.date_from_bs'),
                'attributes'=>
                [
                    'id'=>'dfb',
                    'placeholder'  => 'yyyy-mm-dd',
                    'maxlength' => '10',
                    'relatedId' => 'dfa',
                    'required' => 'Required',

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',

                ],
            ],
            [
                'name'=>'date_from_ad',
                'type'=>'date',
                'label'=>trans('mp.date_from_ad'),
                'attributes'=>
                [
                    'id'=>'dfa',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'date_to_bs',
                'type'=>'nepali_date',
                'label'=>trans('mp.date_to_bs'),
                'attributes'=>[
                    'id'=>'dtb',
                    'placeholder' => 'yyyy-mm-dd',
                    'relatedId' => 'dta',
                    'maxlength' => '10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'date_to_ad',
                'type'=>'date',
                'label'=>trans('mp.date_to_ad'),
                'attributes'=>[
                    'id'=>'dta',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            
            [
                'name'=>'display_order',
                'type'=>'number',
                'label'=>trans('common.display_order'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'is_active',
                'type'=>'radio',
                'label'=>trans('common.is_active'),
                'inline'=>true,
                'options'=>[
                    0 => trans('common.no'),
                    1 => trans('common.yes')
                ],
                'default'=>1
            ],
            [
                'name' => 'remarks',
                'type' => 'ckeditor',
                'label' => trans('common.remarks'),
            ],
            $this->addRemarksField(),
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
