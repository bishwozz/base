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

class HomeController extends Controller
{
    use HeaderFooterData;

    public function index()
    {
        $header_footer_data = $this->getApplicationSettingsData();
        // dd($header_footer_data);
        // $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $sliders = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $about_us = AboutUs::where('deleted_uq_code',1)->first();
        $this->data = [
            // 'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'about_us' => $about_us,
        ];
        return view('frontend.index', $this->data);
    }

    public function getData($slug)
    {
        $header_footer_data = $this->getApplicationSettingsData();
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

    public function about(){
        $header_footer_data = $this->getApplicationSettingsData();
        $this->data = [
            'header_footer_data' => $header_footer_data,
        ];
       return view('frontend.about', $this->data);
    }
    public function contactUs(){
        $this->data = [
            
        ];
       return view('frontend.contact', $this->data); 
    }

    public function team(){
        $this->data = [
            
        ];
       return view('frontend.our_team', $this->data); 
    }

    public function chooseUs(){
        $this->data = [
            
        ];
       return view('frontend.choose_us', $this->data); 
    }
    public function history(){
        $this->data = [
            
        ];
       return view('frontend.history', $this->data); 
    }
    public function faq(){
        $this->data = [
            
        ];
       return view('frontend.faq', $this->data); 
    }
    public function careers(){
        $this->data = [
            
        ];
       return view('frontend.careers', $this->data); 
    }

    public function gallery(){
        $this->data = [
            
        ];
       return view('frontend.gallery', $this->data); 
    }

    public function contactFormSend(){
        return true;
    }
}
