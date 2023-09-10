<?php

namespace App\Http\Controllers\Admin;

use App\Models\FeeType;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Base\Operations\ListOperation;
use App\Base\Operations\CreateOperation;
use App\Base\Operations\UpdateOperation;
use App\Base\Operations\DeleteOperation;
use App\Base\Operations\ShowOperation;
class BaseCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;
    

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->crud = app()->make('crud');
            $this->crud->setRequest($request);
            $this->request = $request;
            $this->setupDefaults();
            $this->setup();
            $this->setupConfigurationForCurrentOperation();
            return $next($request);
        });
    }


    protected function addCodeField()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2',
            ],
            // 'attributes' => [
            //     'readonly' => true,
            // ],
        ];
    }
    protected function addBreak()
    {
        return  [
            'name' => 'legend1',
            'type' => 'custom_html',
            'value' => '<hr>',
        ];
    }

    protected function addReadOnlyCodeField()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'readonly' => true,
            ],
        ];
    }

    // ROW NUMBERS
    protected function addRowNumber()
    {
        return [
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => trans('common.sn'),
        ];
    }

    protected function addCodeColumn()
    {
        return [
            'name' => 'code', // The db column name
            'label' => trans('common.code'), // Table column heading
            'wrapperAttributes' => [
                'class' => 'form-group col-md-5',
            ],
        ];
    }

    protected function addRemarksField()
    {
        return [
            'name' => 'remarks',
            'label' => trans('common.remarks'),
            'type' => 'textarea',
        ];
    }

    public function addIsActiveField(){
        return [
            'name'=>'is_active',
            'label'=>'Is Active',
            'type'=>'radio',
            'default'=>1,
            'inline' => true,
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'options'=>
            [
                1=>'Yes',
                0=>'No',
            ],
        ];
    }
}
