<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use HeaderFooterData;
    public function ourTeam()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $about_us = AboutUs::where('deleted_uq_code',1)->get();
        $teachers = HumanResource::where('type',1)->where('deleted_uq_code',1)->orderBy('display_order','asc')->paginate(4);
        $sayings = Saying::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'about_us' => $about_us,
            'header_footer_data' => $header_footer_data,
            'teachers' => $teachers,
            'sayings' => $sayings,
        ];
        return view('frontend.about', $this->data);
    }
}
