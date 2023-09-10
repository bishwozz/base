<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Event;
use App\Models\Course;
use App\Models\Header;
use App\Models\Saying;
use App\Models\Slider;
use App\Models\AboutUs;
use App\Models\NewsNotice;
use Illuminate\Http\Request;
use App\Models\FooterAddress;
use App\Models\HumanResource;
use App\Models\MstDepartmentType;
use Illuminate\Routing\Controller;
use App\Base\Traits\HeaderFooterData;
use Illuminate\Support\Facades\Redirect;

class ServicesController extends Controller
{
    use HeaderFooterData;

    public function index()
    {
        // $header_footer_data = $this->getCollegeDetailsData();
        // // $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        // $sliders = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        // $about_us = AboutUs::where('deleted_uq_code',1)->first();
        // $popular_courses = Course::where('deleted_uq_code',1)->orderBy('visit_counts', 'DESC')->limit(3)->get();
        // $teacher_count=HumanResource::where('type',1)->where('deleted_uq_code',1)->orderBy('display_order','asc')->count();
        // $department_count=MstDepartmentType::where('deleted_uq_code',1)->orderBy('display_order','asc')->count();
        // $member_count=HumanResource::where('type',3)->where('deleted_uq_code',1)->count();
        // $course_count=Course::where('deleted_uq_code',1)->count();
        // $events=Event::where('deleted_uq_code',1)->orderBy('display_order','asc')->limit(3)->get();
        // $news_notice=NewsNotice::where('deleted_uq_code',1)->orderBy('display_order','asc')->limit(3)->get();
        // $sayings=Saying::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            // 'menus' => $menus,
            // 'sliders' => $sliders,
            // 'header_footer_data' => $header_footer_data,
            // 'about_us' => $about_us,
            // 'popular_courses' => $popular_courses,
            // 'teacher_count' => $teacher_count,
            // 'department_count' => $department_count,
            // 'course_count' => $course_count,
            // 'member_count' => $member_count,
            // 'events' => $events,
            // 'news_notice' => $news_notice,
            // 'sayings' => $sayings,
        ];
        return view('frontend.services', $this->data);
    }

    public function getData($slug)
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('deleted_uq_code',1)->where('type_id','main')->orderBy('display_order','asc')->get();
        $pages = Page::where('deleted_uq_code',1)->where('slug',$slug)->first();
        $this->data = [
            'menus' => $menus,
            'pages' => $pages,
            'header_footer_data' => $header_footer_data,
        ];
        if($pages->external_redirect_url != null){
            return Redirect::away($pages->external_redirect_url); 
        }else{
            return view('frontend.general_page', $this->data);
        }
    }
}
