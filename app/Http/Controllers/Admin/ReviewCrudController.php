<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use App\Http\Requests\SliderRequest;
use App\Http\Controllers\Admin\BaseCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReviewCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReviewCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(Review::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/review');
        CRUD::setEntityNameStrings('review', 'reviews');
        // $this->data['script_js'] = $this->getScripts();
    }



    public function getScripts(){
        return "
        $(document).ready(function() {
            const ratingElement = document.getElementById('rating');
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('span');
                star.innerHTML = '&#9733;'; // Unicode star character
                star.className = 'star';
                star.dataset.value = i;
                star.addEventListener('click', handleStarClick);
                ratingElement.appendChild(star);
            }

            function handleStarClick(event) {
                // 
            }
          });

        ";
    }




    



    protected function setupListOperation()
    {
        $cols = [
            $this->addRowNumber(),
            [
                'name' => 'comment',
                'type' => 'textarea',
                'label' => 'Comment',
            ],
            [
                'name'=>'display_order',
                'type'=>'number',
                'label' => trans('common.display_order'),
            ],
            
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
        // $this->crud->setValidation(SliderRequest::class);
        $arr = [
  
            [
                'name' => 'rating',
                'type' => 'html',
                'label' => 'Rating',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'comment',
                'type' => 'ckeditor',
                'label' => 'comment',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12',
                    ]
            ],
            [
                'label' => trans('common.display_order').' (optional)',
                'type' => 'number',
                'name' => 'display_order',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                    ]
            ],
        ];
        $this->crud->addFields(array_filter($arr));
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

    // public function store(){
    //     $request = request()->all();
    //     dd($request, request());
    //     DB::beginTransaction();
    //     try {
    //     $process = Review::create([
    //         'comment' => $request->comment,
    //         'display_order' => $request->display_order,

    //         'process_type_id' => $processType->id,
    //         'industry_id' => $industry->id,

    //     ]);

    //     DB::commit();
    //     //  dd($industry->id);
    //     return redirect(backpack_url('review'));
    // } catch (\Throwable $th) {
    //     DB::rollBack();
    //     // return back();
    //     dd($th);
    // }
    // }
}
