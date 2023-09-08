<?php

namespace App\Http\Controllers\Admin;

use App\Models\EcMp;
use Illuminate\Http\Request;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Http\Requests\EcMpRequest;
use App\Models\Ministry;

// use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EcMpCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EcMpCrudController extends BaseCrudController
{
    use ParentData;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(EcMp::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/ec-mp');
        $this->crud->setEntityNameStrings(trans('menu.ecMps'), trans('menu.ecMps'));
        // $this->setUpLinks(['edit']);
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
                'label' => trans('mp.photo'),
                'name' => 'photo_path',
                'type' => 'image',
                // 'prefix' => 'storage/uploads/',
            ],

            [
                'name' => 'name_'.lang(),
                'type' => 'text',
                'label' => trans('common.name_'.lang()),

            ],
            [
                'name' => 'gender_id',
                'type' => 'select',
                'entity'=>'gender',
                'attribute' => 'name_'.lang(),
                'model'=>'App\Models\CoreMaster\MstGender',
                'label' => trans('common.gender'),
            ],
            [
                'name' => 'district_id',
                'type' => 'select',
                'entity'=>'district',
                'attribute' => 'name_'.lang(),
                'model'=>'App\Models\CoreMaster\MstFedDistrict',
                'label' => trans('common.district'),
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
                     1 => trans('common.yes'),
                     0 => trans('common.no')
                 ]
             ],
        ];
        $this->crud->addColumns($arr);
        $this->crud->orderBy('display_order');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(EcMpRequest::class);

        $arr=[
            [
                'name'=>'name_lc',
                'type'=>'text',
                'label'=>trans('common.name_lc'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                    'maxlength' => '200',
                    'required' => 'Required',
                 ],
            ],
            
            [
                'name'=>'name_en',
                'type'=>'text',
                'label'=>trans('common.name_en'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                    'maxlength' => '200',
                 ],
            ],
            [
                'name' => 'post_id',
                'type' => 'select2',
                'entity'=>'post',
                'attribute' => 'name_lc',
                'model'=>'App\Models\MstPost',
                'options'   => (function ($query) {
                    return $query->whereIn('id', [1,2,3])
                            ->get();
                        }),
                'label' => trans('menu.post'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'gender_id',
                'type' => 'select2',
                'entity'=>'gender',
                'attribute' => 'name_lc',
                'model'=>'App\Models\CoreMaster\MstGender',
                'label' => trans('common.gender'),
                'options'   => (function ($query) {
                    return $query->selectRaw("code|| ' - ' || name_lc as name_lc, id")
                            ->get();
                        }),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => trans('mp.email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'required' => 'Required',
                 ],
            ],
            [
                'name' => 'district_id',
                'type' => 'select2',
                'entity'=>'district',
                'attribute' => 'name_lc',
                'model'=>'App\Models\CoreMaster\MstFedDistrict',
                'label' => trans('menu.district'),
                'options'   => (function ($query) {
                    return $query->selectRaw("code|| ' - ' || name_lc as name_lc, id")
                            ->get();
                        }),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'mobile_number',
                'type'=>'number',
                'label'=>trans('mp.mobile'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'maxlength' => '10',
                 ],
            ],
            [
                'name'=>'display_order',
                'type'=>'number',
                'label'=>trans('common.display_order'),
                'default'=> 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [   // Upload
                'name' => 'photo_path',
                'label' => trans('mp.photo'),
                'type' => 'image',
                'upload' => true,
                'disk' => 'uploads', 
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                // 'crop'  => true,
                // 'aspect_ratio' => 1
            ],
            [   // Upload
                'name' => 'signature_path',
                'label' => trans('mp.signature'),
                'type' => 'image',
                'upload' => true,
                'disk' => 'uploads', 
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [ 
                'name' => 'fieldset',
                'type' => 'custom_html',
                'value' => '<div></div>',
            ],
            
            [
                'name'=>'is_active',
                'type'=>'radio',
                'label'=>trans('common.is_active'),
                'inline'=>true,
                'options'=>[
                    1 => trans('common.yes'),
                    0 => trans('common.no')
                ],
                'default'=>'1',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'remarks',
                'type' => 'ckeditor',
                'label' => 'Remarks',
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
