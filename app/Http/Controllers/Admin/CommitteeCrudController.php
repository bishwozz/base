<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommitteeRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Base\BaseCrudController;



class CommitteeCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\Committee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/committee');
        CRUD::setEntityNameStrings(trans('menu.committee'), trans('menu.committee'));
        $this->setUpLinks(['edit']);

    }

    public function tabLinks(){
        return  $this->setCommitteeTabs();
    }

    protected function setupListOperation()
    {
        $columns = [
			$this->addRowNumberColumn(),
            $this->addNameLcColumn(),
            $this->addNameEnColumn(),
            $this->addDisplayOrderColumn(),
            $this->addIsActiveColumn(),
		];
        $this->crud->addColumns(array_filter($columns));
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommitteeRequest::class);
        $fields = [

            $this->addNameLcField(),
            $this->addNameEnField(),
            $this->addDisplayOrderField(),
            $this->addIsActiveField(),
            $this->addRemarksField(),
		];
        $this->crud->addFields(array_filter($fields));
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
