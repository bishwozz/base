<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Page;
use App\Http\Requests\PageRequest;
use Prologue\Alerts\Facades\Alert;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PageCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Page::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/page');
        CRUD::setEntityNameStrings('Page', 'Page');
        $this->data['script_js']= $this->getScriptJs();
    }

    public function getScriptJs(){
        return "
        $(document).ready(function(){
            if($('#type_id').val() == '0' || $('#type_id').val() == ''){
                $('.title').val(null);
                $('.title').hide();
                $('.description').val(null);
                $('.description').hide();
                $('.file_upload').val(null);
                $('.file_upload').hide();
            }
            $('.type_id').change(function() {
                if($('#type_id').val() == '0'){
                    $('#title').val(null);
                    $('.title').hide();
                    $('#description').val(null);
                    $('.description').hide();
                    $('#file_upload').val(null);
                    $('.file_upload').hide();
                    $('.external_link').show();
                }else{
                    $('.title').show();
                    $('.description').show();
                    $('.file_upload').show();
                    $('#external_link').val(null);
                    $('.external_link').hide();
                }
            });
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
        $cols = [
            $this->addRowNumber(),
            [
                'name'=>'title',
                'type'=>'numtextber',
                'label' => 'Title',
            ],
            [
                'label' => 'Redirect Url',
                'type' => 'text',
                'name' => 'external_redirect_url',
            ],
            [
                'name' => 'file_upload',
                'type' => 'image',
                'label' => "Image",
                'disk'=>'uploads',
            ]
        ];
        $this->crud->addColumns($cols);  
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PageRequest::class);
        $arr = [
            [
                'label' => trans('common.type'),
                'type' => 'select_from_array',
                'name' => 'type',
                'options' => Page::$type,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 type_id',
                ],
                'attributes'=>[
                    'id'=>'type_id'
                ]

            ],
            [
                'label' => 'Menu',
                'type' => 'select2',
                'name' => 'sub_menu_id',
                'entity' => 'subMenuEntity',
                'attribute' => 'title',
                'model' => 'App\Models\Menu',
                'options'   => (function ($query) {
                    return $query->selectRaw("title, id")
                        ->where('type_id', '>', 0)
                        ->orderBy('id', 'DESC')
                        ->get();
                }),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => trans('External Link'),
                'type' => 'text',
                'name' => 'external_redirect_url',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 external_link',
                ],
                'attributes'=>[
                    'id'=>'external_link'
                ]
            ],
            [
                'label' => trans('Title'),
                'type' => 'text',
                'name' => 'title',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 title',
                ],
                'attributes'=>[
                    'id'=>'title'
                ]
            ],
            [
                'label' => trans('Description'),
                'type' => 'ckeditor',
                'name' => 'description',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12 description',
                ],
                'attributes'=>[
                    'id'=>'description'
                ]
            ],
            [
                'name' => 'file_upload',
                'type' => 'image',
                'label' => 'Image',
                'disk' => 'uploads', 
                'crop' => true, 
                'upload' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 file_upload',
                ],
                'attributes'=>[
                    'id'=>'file_upload'
                ]
            ],
            // [
            //     'name' => 'file_upload',
            //     'type' => 'upload_multiple',
            //     'label' => 'File Upload',
            //     'disk' => 'uploads', 
            //     'upload' => true,
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-4',
            //     ],
                
            // ],
         
        ];
        $this->crud->addFields(array_filter($arr));
        

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

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $user_id = backpack_user()->id;
        $request->request->set('created_by', $user_id);
        $title_name = Menu::where('id',$request->sub_menu_id)->pluck('title')->first();
        $request->request->set('slug', strtolower($title_name));
        $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
