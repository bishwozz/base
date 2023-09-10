<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Menu;
use App\Models\Course;
use App\Models\Saying;
use App\Models\Slider;
use App\Models\AboutUs;
use App\Models\AgentDetail;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use App\Models\HumanResource;
use App\Models\MstDepartmentType;
use Illuminate\Routing\Controller;
use App\Base\Traits\HeaderFooterData;

class BlogController extends Controller
{
    use HeaderFooterData;
    public function index()
    {
        // $header_footer_data = $this->getCollegeDetailsData();
        // $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        // $about_us = AboutUs::where('deleted_uq_code',1)->get();
        // $teachers = HumanResource::where('type',1)->where('deleted_uq_code',1)->orderBy('display_order','asc')->paginate(4);
        // $sayings = Saying::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        // $teacher_count=HumanResource::where('type',1)->where('deleted_uq_code',1)->orderBy('display_order','asc')->count();
        // $department_count=MstDepartmentType::where('deleted_uq_code',1)->orderBy('display_order','asc')->count();
        // $member_count=HumanResource::where('type',3)->where('deleted_uq_code',1)->count();
        // $course_count=Course::where('deleted_uq_code',1)->count();
        $this->data = [
            // 'menus' => $menus,
            // 'about_us' => $about_us,
            // 'header_footer_data' => $header_footer_data,
            // 'teachers' => $teachers,
            // 'sayings' => $sayings,
            // 'teacher_count' => $teacher_count,
            // 'department_count' => $department_count,
            // 'course_count' => $course_count,
            // 'member_count' => $member_count,
        ];
        return view('frontend.blog', $this->data);
    }

    public function introduction()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
        ];
        return view('frontend.introduction', $this->data);
    }

    public function ourTeam()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $teams = HumanResource::where('type',0)->orWhere('type',3)->where('deleted_uq_code',1)->paginate(12);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'teams' => $teams,
        ];
        return view('frontend.our_team', $this->data);
    }

    public function departmentType()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
        ];
        return view('frontend.department_type', $this->data);
    }

    public function class()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
        ];
        return view('frontend.class', $this->data);
    }

    public function feeType()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $courses = Course::where('deleted_uq_code',1)->paginate(10);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'courses' => $courses,
        ];
        return view('frontend.fee_type', $this->data);
    }

    public function scholarship()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $scholarships = Scholarship::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'scholarships' => $scholarships,
        ];
        return view('frontend.scholarship', $this->data);
    }

    public function agents()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $agents = AgentDetail::where('deleted_uq_code',1)->orderBy('display_order','asc')->paginate(6);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'agents' => $agents,
        ];
        return view('frontend.agents', $this->data);
    }

    public function agentDetail($id)
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $agent = AgentDetail::find($id);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'agent' => $agent,
        ];
        return view('frontend.agent_details', $this->data);
    }
    public function teamDetail($id){
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $team_detail = HumanResource::find($id);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'team_detail' => $team_detail,
        ];
        return view('frontend.team-details', $this->data);
    }
}

