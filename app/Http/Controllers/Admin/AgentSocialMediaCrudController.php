<?php

namespace App\Http\Controllers\Admin;

use App\Models\MstSocialMedia;
use App\Base\Traits\ParentData;
use App\Http\Requests\AgentSocialMediaRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AgentSocialMediaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AgentSocialMediaCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    use ParentData;
    public function setup()
    {
        CRUD::setModel(\App\Models\AgentSocialMedia::class);
        \CRUD::setRoute('admin/agent-detail/' . $this->parent('agent_details_id') . '/agent-social-media');
        CRUD::setEntityNameStrings('Social Media', 'Social Medias');
        $this->setUpLinks();
    }

    public function tabLinks()
    {
        return $this->setAgentDetailsTabs();
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
                'label' => 'Social Media',
                'type' => 'select',
                'name' => 'social_media_id',
                'entity' => 'socialMedia',
                'attribute' => 'name',
                'model' => "App\Models\MstSocialMedia",
                
            ],
            [
                'label' => 'Url',
                'type' => 'text',
                'name' => 'url',
            ],
        ];
        $this->crud->addColumns($cols);
        if ($this->parent('agent_details_id') === null) {
            abort(404);
        } else {
            $this->crud->addClause('where', 'agent_details_id', $this->parent('agent_details_id'));
        }

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
        CRUD::setValidation(AgentSocialMediaRequest::class);

        $arr = [

            [
                'name' => 'agent_details_id',
                'type' => 'hidden',
                'value' => $this->parent('agent_details_id')
            ],
            [
                'name' => 'social_media_id',
                'type' => 'select2',
                'entity' => 'socialMedia',
                'attribute' => 'name',
                'model' => MstSocialMedia::class,
                'label' => 'Social Media',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'attributes' => [
                    'required' => 'Required',
                ],
            ],
           
            [
                'name' => 'url',
                'type' => 'text',
                'label' => 'URL',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'display_order',
                'type' => 'number',
                'label' => 'Display Order',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            $this->addIsActiveField(),
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
