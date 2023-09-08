<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\EcMp;
use App\Models\MstStep;
use Illuminate\Http\Request;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Models\EcMeetingRequest;
use Illuminate\Support\Facades\DB;
use App\Models\MeetingAttendanceDetail;
use App\Http\Requests\MeetingAttendanceDetailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class MeetingAttendanceDetailCrudController extends BaseCrudController
{
    use ParentData;

    private $user;
    private $now;
    private $mp_user;

    public function setup()
    {
        $this->now = Carbon::now();
        $this->user=backpack_user();
        $this->mp_user = isset($this->user->mp_id);
        CRUD::setModel(\App\Models\MeetingAttendanceDetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ec-meeting-request/'.$this->parent('meeting_request_id').'/meeting-attendance-detail');
        CRUD::setEntityNameStrings(trans('menu.meetingAttendanceDetails'), trans('menu.meetingAttendanceDetails'));
        $this->setUpLinks();
        $this->checkPermission(['mpAttendance' => 'mpAttendance',
        'applyForMeetingAttendanceAttend' => 'applyForMeetingAttendanceAttend',
        'pullMinistry'=>'pullMinistry'
        ]);
    }

    public function tabLinks(){
        return  $this->setEcMeetingRequestTabs();
}

    protected function setupListOperation()
    {
       
        $cols = [
            $this->addRowNumberColumn(),            
           
            [
                'name' => 'mp_id',
                'type' => 'select',
                'entity' => 'mp',
                'attribute' => 'name_lc',
                'model' => EcMp::class,
                'label' => trans('common.mp'),
            ],
            [
                'name' => 'requested_date_bs',
                'label' => trans('common.requested_date_bs').'<br>'.trans('common.requested_date_ad'),
                'function_name' => 'requested_date',
                'type' => 'model_function'
            ],
          
            [
                'name' => 'is_present',
                'label' => trans('common.is_present'),
                'type' => 'custom_toggle',
            ],
            [
                'name' => 'present_time',
                'label' => trans('common.present_time'),
                'wrapperAttributes' => [
                    'id'=>'check_time'
                ],
            ],
           
        ];
        // dd(isset($this->user->mp_id));
        $this->crud->addColumns(array_filter($cols));
        // $roles=$this->user->getRoleNames();
        // foreach($roles as $role)
        // {
        //    if($role == 'mp'){

        //    }
        // }
        if($this->mp_user){
            $this->crud->addClause('where','mp_id',$this->user->mp_id);
        }

        /* 
            to view attendance detail according to meeting. 
         */
        
        $this->crud->addClause('where', 'meeting_request_id', $this->parent('meeting_request_id'));
  
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MeetingAttendanceDetailRequest::class);
        $current_nepali_date =convert_bs_from_ad($this->now ->toDateString());

         $present_time=$requested_date_bs= $requested_date_ad= $apply_for_meeting_attendance= $ministry =NULL;
         $is_present = false;

        if($this->mp_user){
            $mp=[
                'name' =>'mp_id',
                'label' => trans('common.mp'),
                'type' =>'hidden',
                'value' => $this->user->mp_id
            ];
            if(isset($this->user->commiteeEntity)){

                $ministry=[
                    'name' => 'ministry_id',
                    'type' =>'hidden',
                    'value' => $this->user->commiteeEntity->ec_com->id
                ];
            }

           $requested_date_bs= [
                'name' => 'requested_date_bs',
                'type' => 'nepali_date',
                'label' => trans('common.requested_date_bs'),
                'attributes' => [
                    'id' => 'requested_date_bs',
                    'relatedId' => 'requested_date_ad',
                    'maxlength' => '10',
                    'readonly' =>'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'default'=> $current_nepali_date,
            ];
            $requested_date_ad=[
                'name' => 'requested_date_ad',
                'type' => 'date',
                'label' => trans('common.requested_date_ad'),
                'value' => $this->now,

                'attributes' => [
                    'id' => 'requested_date_ad',
                    'relatedId' =>'requested_date_bs',
                    'readonly' => 'readonly'
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ];
            $apply_for_meeting_attendance= [
                'name' => 'apply_for_meeting_attendance',
                'label' => trans('Meeting Attendance'),
                'type' => 'radio',
                'options'     => [
                    false => 'होइन',
                    true => 'हो'
                ],
                'inline' => true,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'default' => true,
            ];
        }
        else{
            $mp=[
                'name' => 'mp_id',
                'type' => 'select2',
                'entity' => 'mp',
                'attribute' => 'name_lc',
                'model' => EcMp::class,
                'label' => trans('common.mp'),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ];
           $is_present= [
                'name' => 'is_present',
                'label' => trans('common.is_present'),
                'type' => 'radio',
                'options'     => [
                    false => 'होइन',
                    true => 'हो'
                ],
                'inline' => true,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'default' => false,
            ];
           $present_time= [
                'name' => 'present_time',
                'label' => trans('common.present_time'),
                'type' => 'time',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                
            ];
            
        }

        $arr = [
            [
                'name' => 'meeting_request_id',
                'value' => $this->parent('meeting_request_id'),
                'type' => 'hidden',
            ],
            $mp,
            $ministry,
            $is_present,
            $present_time,
            $apply_for_meeting_attendance,
            $requested_date_bs,
            $requested_date_ad,
            // [
            //     'name' => 'agenda',
            //     'type' => 'repeatable',
            //     'label' => trans('common.agenda'),
            //     'show_individually' => true,
            //     'new_item_label'  => 'थप',
            //     'fields' => [
            //         [
            //             'name' => 'agenda',
            //             'type' => 'text',
            //             'label' => trans('common.agenda'),
            //             'wrapper' => [
            //                 'class' => 'form-group col-md-8',
            //             ],
            //         ],
            //         [
            //             'name' => 'step_id',
            //             'type' => 'select2',
            //             'entity' => 'step',
            //             'attribute' => 'name_lc',
            //             'model' => MstStep::class,
            //             'label' => trans('common.step'),
            //             'wrapper' => [
            //                 'class' => 'form-group col-md-4',
            //             ],
            //         ],
            //     ],
            // ],
            $this->addRemarksField(),
        ];
        $this->crud->addFields(array_filter($arr));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function mpAttendance(Request $request,$id){
        try{
            if($request->tog_val == 1){
                $present = true;
                $present_time = Carbon::now();
            }else{
                $present = false;
                $present_time = null;
            }

            MeetingAttendanceDetail::whereId($id)->update([
                'is_present' => $present,
                'present_time' => $present_time,
              ]);
              return 1;
        }catch(Exception $e){
            DB::rollback();
            dd($e);
        }

    }

    public function applyForMeetingAttendanceAttend(Request $request){
        dd('here');
    }
}
