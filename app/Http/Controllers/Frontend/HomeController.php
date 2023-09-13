<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use App\Models\Event;
use App\Models\Games;
use App\Models\Course;
use App\Models\Header;
use App\Models\Saying;
use App\Models\Slider;
use App\Models\AboutUs;
use App\Models\Payment;
use App\Models\Services;
use App\Models\SlideShow;
use App\Models\NewsNotice;
use Illuminate\Http\Request;
use App\Models\FooterAddress;
use App\Models\HumanResource;
use App\Models\MstDepartmentType;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Base\Traits\HeaderFooterData;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        // dd('as');
        $sliders = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $games = Games::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $services = Services::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $payments = Payment::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $slideshows = SlideShow::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();

        $this->data = [
            'sliders' => $sliders,
            'games' => $games,
            'services' => $services,
            'payments' => $payments,
            'slideshows' => $slideshows,
        ];
        return view('frontend.index', $this->data);
    }
    public function review()
    {
        dd('asd');
        $review = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();


        $this->data = [
            'review' => $review,
          
        ];
        return view('frontend.index_review', $this->data);
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

    public function showLoginForm(){
        return view('frontend.auth.login');
    }

    public function login(Request $request){
        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            auth::login($user);
            return redirect('/');
        }

        return response()->json([
            'status' => 'error',
            'message' => 'failed',
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/'); // Redirect to the homepage or any other page after logout
    }
    public function payment()
    {
        return view('frontend.payment');
    }
}
